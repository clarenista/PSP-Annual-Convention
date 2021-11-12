<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\UserEvent;
use App\Models\UserEventCategory;
use Illuminate\Support\Facades\Http;

class GuestController extends Controller
{

    public function boothTracker()
    {

        $booths = UserEventCategory::whereCategorizableType('App\Models\Booth')->get();

        $boothTracks = UserEvent::whereIn('user_event_category_id', $booths->pluck('id'))->whereLabel('visit')->get();

        $return = [];

        foreach ($booths as $booth) {
            $return[$booth->id] = [
                'name' => $booth->name,
                'visited' => boolval($boothTracks->where('user_event_category_id', $booth->categorizable->id)->count()),
            ];
        }

        return $return;
    }


    private function checkRegistrants($email, $webinar_id = "81037064653")
    {
        $bearer = "Bearer ";
        $bearer .= "eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6Ilc4am01TXdKUnEtS1Nrd2puTTZGY2ciLCJleHAiOjE2MzczNTI2NjMsImlhdCI6MTYzNjc0Nzg2NX0.Tr1bSuwXBuzx0EGn10hQ6ln0_0XXS0s6GC7aPdLFt9w";

        $registrants_api = "https://api.zoom.us/v2//webinars/{$webinar_id}/registrants";
        $client = Http::withHeaders(['Accept' => 'application/json', 'Authorization' => $bearer]);
        $response = $client->get($registrants_api);
        $registrants = $response->json()['registrants'];
    }

    public function zoomJoinMobile($webinar_id = "81037064653", $webinar_topic = "PSP70 - WEBINAR")
    {

        $user = request()->user();
        $webinar = $user->webinars()->where('webinar_id', $webinar_id)->first();

        if (!$webinar->count()) {
            $registered = $this->checkRegistrants($user->email);
            $webinar = $user->webinars()->create([
                "registrant_id" => $registered['id'],
                "webinar_id" => $webinar_id,
                "topic" => $webinar_topic,
                "join_url" => $registered['join_url'],
                'registered' => true,
            ]);
        }

        return $webinar->join_url;
        // $program = Program::first();
        // return $program->video_url;
    }

    public function zoomJoin($meetingNumber = "78803086236")
    {

        $program = Program::first();
        $meetingNumber = $program->unique_id;

        $apiKey = '9srj55u0SxGqM9F2dUU1nA';
        $secret = 'GRa14V4N2ukM2Ci6yu8NSnzLmftWJnOf97BB';
        $passWord = $program->unique_code;
        $user = request()->user();
        $signature = $this->zoomSignature($apiKey, $secret, $meetingNumber);
        $userName = $user->first_name . " " . $user->last_name;
        $userEmail = $user->email . '@gmail.com';
        return compact('signature', 'meetingNumber', 'userName', 'apiKey', 'userEmail', 'passWord');
    }

    private function zoomSignature($api_key, $api_secret, $meeting_number, $role = 0)
    {

        date_default_timezone_set("UTC");
        $time = time() * 1000 - 30000;
        $data = base64_encode($api_key . $meeting_number . $time . $role);
        $hash = hash_hmac('sha256', $data, $api_secret, true);
        $_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);

        return rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');
    }
}
