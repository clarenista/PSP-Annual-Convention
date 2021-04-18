<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Booth;
use App\Models\QuestionAnswer;
use App\Models\Question;

class BoothController extends Controller
{

    public function get()
    {

        return Booth::with('assets')->get();
    }

    public function show($booth_id)
    {

        $booth = Booth::whereId($booth_id)
            ->with(['assets', 'hotspots', 'hotspots.assets', 'questionnaire'])
            ->first();

        $return = [
            'id' => $booth->id,
            
            'name' => $booth->name,
            'panorama_location' => $booth->panorama_location,
        ];

        foreach ($booth->assets as $asset) {
            $return[$asset->category] = $asset->url;
        }

        foreach ($booth->hotspots as $hotspot) {
            $return['hotspots'][$hotspot->name] = $hotspot;
            $return['hotspots'][$hotspot->name]['questions'] = $booth->questionnaire->questions;
            $return['hotspots'][$hotspot->name]['quiz_taken'] = QuestionAnswer::with('question')->where('user_id', request()->user()->id)->get();
        }

        return $return;

    }

    public function storeMessage($booth_id)
    {

        return request()
            ->user()
            ->boothMessages()
            ->create(
                \request()->merge([
                    'booth_id' => $booth_id,
                ])->validate([
                    'booth_id' => 'required',
                    'subject' => 'nullable',
                    'name' => 'nullable',
                    'affiliation' => 'nullable',
                    'mobile_number' => 'nullable',
                    'email' => 'nullable',
                    'interest' => 'nullable',
                    'message' => 'nullable',
                ])
            );
    }

    public function showQuestionnaire(Booth $booth)
    {

        return $booth->questionnaire()->with('questions')->first();
    }

    public function storeQuestionnaireAnswerSubmit($booth_id)
    {
        $sent_at = date("Y-m-d H:i:s");

        request()->validate([
            'answers' => 'required'
        ]);

        foreach (json_decode(request()->answers) as $question_id => $answer) {
            $question = Question::find($question_id);
            QuestionAnswer::create([
                'user_id' => request()->user()->id,
                'question_id' => $question_id,
                'answer' => $answer,
                'correct' => $question['answer'] == $answer ? 1 : 0,
                'sent_at' => $sent_at,
            ]);
        }

        $answers = QuestionAnswer::with('question')->where('user_id', request()->user()->id)->get();

        return response(['answers' =>$answers], 201);
    }

}
