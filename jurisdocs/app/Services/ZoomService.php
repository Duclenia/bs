<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZoomService {
    private $accountId;
    private $clientId;
    private $clientSecret;

    public function __construct() {
        $this->accountId = config('services.zoom.account_id');
        $this->clientId = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
    }

    private function getAccessToken() {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("https://zoom.us/oauth/token", [
                'grant_type' => 'account_credentials',
                'account_id' => $this->accountId,
            ]);

        return $response->json()['access_token'];
    }

    public function createMeeting($topic, $startTime, $duration = 60) {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("https://api.zoom.us/v2/users/me/meetings", [
                "topic" => $topic,
                "type" => 2, 
                "start_time" => $startTime,
                "duration" => $duration,
                "settings" => [
                    "host_video" => true,
                    "participant_video" => true,
                ]
            ]);

        return $response->json();
    }
}
