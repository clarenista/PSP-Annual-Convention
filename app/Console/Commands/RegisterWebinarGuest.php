<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserWebinar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RegisterWebinarGuest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:webinar-guests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $bearer = "Bearer ";
        $bearer .= "eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6Ilc4am01TXdKUnEtS1Nrd2puTTZGY2ciLCJleHAiOjE2MzczNTI2NjMsImlhdCI6MTYzNjc0Nzg2NX0.Tr1bSuwXBuzx0EGn10hQ6ln0_0XXS0s6GC7aPdLFt9w";
        $webinar_id = "81037064653";
        $webinar_topic = "PSP70 - DEV WEBINAR";

        $panelists = [
            // 'panell@panel.com',
            // '44444@4youtube.com',
            // '11@11.com',
            // '22@22.com',
        ];

        $this->register_panelists($webinar_id, $webinar_topic, $bearer, $panelists);
        $this->register_registrants($webinar_id, $webinar_topic, $bearer, $panelists);
        return 0;
    }

    private function register_panelists($webinar_id, $webinar_topic, $bearer, $emails)
    {

        $webinar_role = "panelist";
        $panelists_api = "https://api.zoom.us/v2//webinars/{$webinar_id}/panelists";
        $client = Http::withHeaders(['Accept' => 'application/json', 'Authorization' => $bearer]);
        // $response = $client->delete($panelists_api);
        // $response = $response->json();
        // echo var_dump($response);
        $response = $client->get($panelists_api);
        $response = $response->json();
        $registered_panelists = $response['panelists'];
        $registered_panelists = collect($registered_panelists);
        echo var_dump($response);
        $guests = User::withTrashed()->whereIn('email', $emails)->get();
        $panelists = [];
        foreach ($guests as $guest) {
            if (!$registered_panelists->firstWhere('email', $guest->email))
                $panelists[]  = [
                    'email' => $guest->email,
                    'name' => $guest->first_name . " " . $guest->last_name,
                ];
        }
        if (count($panelists)) {
            $response = $client->post($panelists_api, compact('panelists'));
            $response = $response->json();
            echo var_dump($response);
        }
        $response = $client->get($panelists_api);
        $response = $response->json();
        foreach ($response['panelists'] as $panelist) {
            $user = User::whereEmail($panelist['email'])->first();
            $webinar = [
                'registrant_id' => $panelist['id'],
                "webinar_id" => $webinar_id,
                "topic" => $webinar_topic,
                'role' => $webinar_role,
                'join_url' => $panelist['join_url']
            ];
            if ($user->webinars->count()) {
                $user->webinars()->update($webinar);
            } else {
                $user->webinars()->create($webinar);
            }
        }
    }

    private function register_registrants($webinar_id, $webinar_topic, $bearer, $panelists)
    {

        $webinar_role = "registrant";
        $registrants_api = "https://api.zoom.us/v2//webinars/{$webinar_id}/registrants";
        $client = Http::withHeaders(['Accept' => 'application/json', 'Authorization' => $bearer]);
        $response = $client->get($registrants_api);
        $registrants = $response->json()['registrants'];
        $guests = User::withTrashed()->whereNotIn('email', $panelists)->get();
        foreach ($guests as $guest) {
            if (strpos($guest->email, "@")) {
                $registrants = collect($registrants);
                $registered = $registrants->firstWhere('email', $guest->email);
                if ($registered) {
                    $response = [
                        "registrant_id" => $registered['id'],
                        "id" => $webinar_id,
                        "topic" => $webinar_topic,
                        "join_url" => $registered['join_url'],
                        'registered' => true,
                    ];
                } else {
                    $registrants_api = "https://api.zoom.us/v2//webinars/{$webinar_id}/registrants";
                    $post = [
                        'email' => $guest->email,
                        'first_name' => $guest->first_name,
                        'last_name' => $guest->last_name,
                    ];
                    $response = $client->post($registrants_api, $post);
                    $response = $response->json();
                }
                if (isset($response['code']) && $response['code'] == 300) {
                    dd($response, $guest->toArray());
                }
                $registered = UserWebinar::whereRegistrantId($response['registrant_id'])->first();
                $data = [
                    'registrant_id' => $response['registrant_id'],
                    'webinar_id' => $response['id'],
                    'topic' => $response['topic'],
                    'role' => $webinar_role,
                    'join_url' => $response['join_url'],
                ];
                if ($registered) {
                    $registered->update($data);
                } else {
                    $guest->webinars()->create($data);
                }
            }
        }
    }
}
