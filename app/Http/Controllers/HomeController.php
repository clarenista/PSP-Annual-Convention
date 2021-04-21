<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        return view("cms.home");
    }

    public function registration()
    {
        return view("home.registration");
    }

    public function gallery()
    {
        return view("home.gallery");
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::with('booth')->where('email', $request->email)->first();
        $user2 = User::with('booth')->where('email', $request->email)->whereLoginCode($request->password)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password) || $user2) {
                if (!$user) {
                    $user = $user2;
                }
                if(!$user->api_token){

                    $user->update(['api_token' => hash('sha256', Str::random(80))]);
                }
                Auth::login($user);
                return response()->json([
                    'status' => 'ok',
                    'user' => $user,
                    'access_token' => $user->api_token,
                ]);
            } else {

                return response()->json([
                    'status' => 'failed',
                ]);
            }
        } else {
            $guzzle = new \GuzzleHttp\Client;

            $token = $guzzle->post(config('app.domain') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => config('app.passport.grant_type'),
                    'client_id' => config('app.passport.client_id'),
                    'client_secret' => config('app.passport.client_secret'),
                    'scope' => '*',
                ],
            ]);
            // return json_decode((string) $token->getBody(), true)['access_token'];

            $response = $guzzle->post(config('app.domain') . '/api/user', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . json_decode((string) $token->getBody(), true)['access_token'],
                ],
                'form_params' => [
                    'email' => $request->email,
                    'password' => $request->password,
                ],
            ]);
            $result = json_decode((string) $response->getBody(), true);
            if ($result) {

                $password2 =
                    [
                    'A' => 'A1000',
                    'B' => 'B2000',
                    'C' => 'C3000',
                    'D' => 'D4000',
                    'E' => 'E5000',
                    'F' => 'F6000',
                    'G' => 'G7000',
                    'H' => 'H8000',
                    'I' => 'I9000',
                    'J' => 'J1000',
                    'K' => 'K1100',
                    'L' => 'L1200',
                    'M' => 'M1300',
                    'N' => 'N1400',
                    'O' => 'O1500',
                    'P' => 'P1600',
                    'Q' => 'Q1700',
                    'R' => 'R1800',
                    'S' => 'S1900',
                    'T' => 'T2000',
                    'U' => 'U2100',
                    'V' => 'V2200',
                    'W' => 'W2300',
                    'X' => 'X2400',
                    'Y' => 'Y2500',
                    'Z' => 'Z2600',
                ];
                $user = User::firstOrCreate(
                    [
                        'registrant_id' => $result['user']['id'],
                    ],
                    [
                        'registrant_id' => $result['user']['id'],
                        'api_token' => hash('sha256', Str::random(80)),
                        'name' => $result['user']['first_name'] . " " . $result['user']['last_name'],
                        'first_name' => $result['user']['first_name'],
                        'last_name' => $result['user']['last_name'],
                        'mobile_number' => $result['user']['contact'],
                        'email' => $result['user']['username'],
                        'password' => $result['user']['password'],
                        'classification' => $result['user']['classification'],
                        'login_code' =>  $password2[\strtoupper($result['user']['first_name'][0])],
                    ]
                );

                // $user->assignRole('guest');
                return response()->json([
                    'status' => 'ok',
                    'user' => $user,
                    'access_token' => $user->api_token,
                ]);
            } else {

                return response()->json([
                    'status' => 'failed',
                ]);
            }
        }

        // $user = User::where('email', $request->email)->first();
        // if($user){
        //     if(Hash::check($request->password, $user->password)){
        //         $token = ApiTokenController::update($user);
        //         return response()->json([
        //             'status' => 'ok',
        //             'user' => $user,
        //             'permissions' => $user->getPermissionsViaRoles()->pluck('name'),
        //             'api_token' => $token
        //         ]);
        //     }
        // }else{

        //     return response()->json([
        //         'status' => 'failed',
        //     ]);
        // }
        // if(Hash::check($request->password, $user->password)){
        // }
    }

}
