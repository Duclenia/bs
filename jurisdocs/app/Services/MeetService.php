<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class MeetService
{

    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('app/google/credentials.json'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        $this->client->setAccessType('offline');
    }

    public function createMeeting($summary = "Reunião Meet")
    {
        $service = new Google_Service_Calendar($this->client);

        $event = new Google_Service_Calendar_Event([
            'summary' => $summary,
            'start' => ['dateTime' => now()->toRfc3339String(), 'timeZone' => 'Africa/Luanda'],
            'end'   => ['dateTime' => now()->addHour()->toRfc3339String(), 'timeZone' => 'Africa/Luanda'],
            'conferenceData' => [
                'createRequest' => [
                    'requestId' => uniqid(),
                ]
            ]
        ]);

        $event = $service->events->insert('primary', $event, ['conferenceDataVersion' => 1]);

        return $event->hangoutLink;
    }
}
