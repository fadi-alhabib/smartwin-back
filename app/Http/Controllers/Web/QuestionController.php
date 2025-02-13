<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Answer;
use Carbon\Carbon;

class QuestionController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index()
    {
        $questions = Question::get();

        return view('questions.question', ['questions' => $questions]);
    }


    public function create()
    {
        return view('questions.create');
    }


    public function store(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'title' => 'required',
        //     'correct_answer' => 'required',
        //     'wrong_answer' => 'required',
        // ],
        // [
        //     'title.required' => 'هذا الحقل مطلوب',
        //     'correct.required' => 'هذا الحقل مطلوب',
        //     'wrong.required' => 'هذا الحقل مطلوب',
        // ]
        // );


        // if($validator->fails())
        // {
        //     return back()->withErrors($validator)->withInputs($request->all());
        // }



        $question_id = Question::insertGetId([
            'title' => $request->title,
            'user_id' => Auth::id(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);





        // $correct = json_decode($request->correct_answer);

        // $correct_answer = [];


        // foreach($correct as $title)
        // {
        //     $answer = [];

        //     $answer['title'] = $title;
        //     $answer['question_id'] = $question_id;
        //     $answer['is_correct'] = true;

        //     array_push($correct_answer, $answer);
        // }

        // Answer::insert($correct_answer);






        // $wrong = json_decode($request->wrong_answer);

        // $wrong_answer = [];


        // foreach($wrong as $title)
        // {
        //     $answer = [];

        //     $answer['title'] = $title;
        //     $answer['question_id'] = $question_id;
        //     $answer['is_correct'] = false;

        //     array_push($wrong_answer, $answer);
        // }

        // Answer::insert($wrong_answer);





        Answer::insert(
            [
                [
                    'title' => $request->correct_answer,
                    'question_id' => $question_id,
                    'is_correct' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title' => $request->wrong_answer_1,
                    'question_id' => $question_id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title' => $request->wrong_answer_2,
                    'question_id' => $question_id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title' => $request->wrong_answer_3,
                    'question_id' => $question_id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]
        );


        return redirect('question/create');


        // dd($request);
    }


    public function show($id)
    {
        $question = Question::where('id', $id)->get();

        $correct_answers = Answer::where('question_id', $id)->where('is_correct', 1)->get();

        $wrong_answers = Answer::where('question_id', $id)->where('is_correct', 0)->get();

        return view('questions.show', ['question' => $question, 'correct_answers' => $correct_answers, 'wrong_answers' => $wrong_answers]);
    }


    public function edit($id)
    {
        $question = Question::where('id', $id)->get();

        $correct_answer = Answer::where('question_id', $id)->where('is_correct', true)->get();

        $wrong_answer = Answer::where('question_id', $id)->where('is_correct', false)->get();

        return view('questions.edit', ['question' => $question, 'correct_answer' => $correct_answer, 'wrong_answer' => $wrong_answer, 'id' => $id]);
    }


    public function update(Request $request, $id)
    {


        // $validator = Validator::make($request->all(), [
        //     'title' => 'required'
        // ]);

        Question::where('id', $id)->update([
            'title' => $request->title
        ]);

        Answer::where('question_id', $id)->delete();


        Answer::insert(
            [
                [
                    'title' => $request->correct_answer,
                    'question_id' => $id,
                    'is_correct' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title' => $request->wrong_answer_1,
                    'question_id' => $id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title' => $request->wrong_answer_2,
                    'question_id' => $id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title' => $request->wrong_answer_3,
                    'question_id' => $id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]
        );


        return redirect('question');



        // return back();

        // dd($request);
    }


    public function destroy($id)
    {
        Answer::where('question_id', $id)->delete();

        Question::where('id', $id)->delete();

        return redirect('question');
    }




    public function status(Request $request)
    {
        Question::where('id', $request->question_id)->UPDATE([
            'status' => $request->status
        ]);

        return back();
    }
}
