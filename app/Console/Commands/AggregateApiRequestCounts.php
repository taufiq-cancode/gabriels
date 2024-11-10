<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AggregateApiRequestCounts extends Command
{
    protected $signature = 'api:aggregate-request-counts';
    protected $description = 'Aggregate API request counts from Redis to the database';

    public function handle()
    {
        // Define the endpoints to aggregate; could retrieve dynamically if preferred
        $endpoints = Redis::keys('api_request_count:*');

        foreach ($endpoints as $key) {
            // Parse key format: "api_request_count:day|week:userId:endpoint"
            [$prefix, $period, $userId, $endpoint] = explode(':', $key);

            // Get the count from Redis
            $count = Redis::get($key);

            // Determine period start date based on "day" or "week"
            $periodStart = ($period === 'day') ? Carbon::now()->startOfDay() : Carbon::now()->startOfWeek();

            // Save to the database
            ApiRequestStatistic::updateOrCreate(
                [
                    'user_id' => $userId,
                    'endpoint' => $endpoint,
                    'period_start' => $periodStart->toDateString(),
                ],
                ['request_count' => $count]
            );

            // Reset the Redis counter after saving
            Redis::del($key);
        }

        $this->info('API request counts have been aggregated and stored in the database.');
    }
}
