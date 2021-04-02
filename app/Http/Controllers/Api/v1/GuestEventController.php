<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserEventCategory;
use Illuminate\Support\Str;
use App\Models\User;

class GuestEventController extends Controller
{

    public function push()
    {

        $user = User::where('api_token', \request()->api_token)->first();
        $input = \request()->validate([
            'type' => 'in:ping,event',
            'category' => 'required',
            'label' => 'string',
        ]);

        $category = UserEventCategory::whereName($input['category'])->firstOrCreate([
            'name' => Str::slug($input['category']),
        ], [
            'description' => $input['category'],
        ]);

        $input['user_event_category_id'] = $category->id;
        $input['sent_at'] = date('Y-m-d H:i:s');

        $user->events()->create($input);

        return \response(null, 201);
    }
}