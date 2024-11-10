<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            $endpoint = $request->path();

            // Define Redis keys
            $dayKey = "api_request_count:day:{$user->id}:{$endpoint}";
            $weekKey = "api_request_count:week:{$user->id}:{$endpoint}";

            // Increment counters
            Redis::incr($dayKey);
            Redis::incr($weekKey);

            // Set expiration times if keys are new
            Redis::expire($dayKey, 86400); // 1 day
            Redis::expire($weekKey, 604800); // 1 week

            // Retrieve counts
            $lastDayCount = Redis::get($dayKey);
            $lastWeekCount = Redis::get($weekKey);

            // Add counts to response
            $response->setData(array_merge($response->getData(true), [
                'request_count_last_day' => $lastDayCount,
                'request_count_last_week' => $lastWeekCount,
            ]));
        }

        return $response;
    }
}
