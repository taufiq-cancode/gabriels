<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class TrackApiRequests
{

    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $userId = auth()->id();
            $endpoint = $request->route()->getName(); // Adjust as needed to get the endpoint name
            $currentTimestamp = now()->timestamp;

            // Define Redis keys for daily and weekly tracking
            $dailyKey = "user:{$userId}:endpoint:{$endpoint}:daily";
            $weeklyKey = "user:{$userId}:endpoint:{$endpoint}:weekly";

            // Use Redis sorted sets to track timestamps
            Redis::zadd($dailyKey, $currentTimestamp, $currentTimestamp);
            Redis::zadd($weeklyKey, $currentTimestamp, $currentTimestamp);

            // Set expiration for Redis keys (1 day for daily, 7 days for weekly)
            Redis::expire($dailyKey, 86400);
            Redis::expire($weeklyKey, 604800);
        }

        $response = $next($request);

        // Calculate the counts for last day and last week
        $dailyCount = $this->countRequestsInTimeframe($dailyKey, 86400);  // 1 day
        $weeklyCount = $this->countRequestsInTimeframe($weeklyKey, 604800); // 7 days

        // Attach counts to the response
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $data = $response->getData(true);
            $data['request_counts'] = [
                'last_day' => $dailyCount,
                'last_week' => $weeklyCount,
            ];
            $response->setData($data);
        }

        return $response;

        return $next($request);
    }

    /**
     * Count requests in a given timeframe.
     */
    public function countRequestsInTimeframe($key, $seconds)
    {
        $start = Carbon::now()->subSeconds($seconds)->timestamp;
        $end = now()->timestamp;

        return Redis::zcount($key, $start, $end);
    }
}
