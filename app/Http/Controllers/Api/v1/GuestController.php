<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\UserEvent;
use App\Models\UserEventCategory;

class GuestController extends Controller
{

    public function boothTracker()
    {

        $booths = UserEventCategory::whereCategorizableType('App\Models\Booth')->get();

        $boothTracks = UserEvent::whereIn('user_event_category_id', $booths->pluck('id'))->whereUserId(request()->user()->id)->whereLabel('visit')->where('created_at','>','2021-04-23 02:33:00')->get();

        $return = [];

        foreach ($booths as $booth) {
            $return[$booth->id] = [
                'name' => $booth->description,
                'visited' => boolval($boothTracks->where('user_event_category_id', $booth->id)->count()),
            ];
        }

        return $return;
    }

    public function zoomJoinMobile()
    {

        $program = Program::first();

        return $program->video_url;
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
        $userEmail = strpos($user->email, '@') ? $user->email : $user->email . '@gmail.com';

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
