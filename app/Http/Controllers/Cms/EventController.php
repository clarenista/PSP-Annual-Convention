<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function update(){

        $input = request()->validate([
            'start_at' => 'required',
            'end_at' => 'required',
            'title' => 'nullable',
            'description' => 'nullable',
        ]);

        $event = Event::first();
        $event->update($input);

        return redirect()->route('cms.event-management.index');
    }
}
