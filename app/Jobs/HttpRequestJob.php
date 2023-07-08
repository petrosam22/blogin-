<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class HttpRequestJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */





     public function handle()
{
    // $response = Http::get('https://randomuser.me/api/');

    // if ($response->ok()) {
    //     Log::info('API request successful with status code: ' . $response->status());
    //     $responseData = $response->json();
    //     Log::info('Response from API:', $responseData);
    // } else {
    //     Log::error('API request failed with status code: ' . $response->status());
    // }


    $response = Http::acceptJson()
    ->timeout(10)
    ->withToken('...')
    ->get('https://randomuser.me/api/');

if ($response->failed() && $response->status() == 429) {
    return $this->release(30);
}
}


}
