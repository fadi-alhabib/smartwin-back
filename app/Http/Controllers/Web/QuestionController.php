<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Answer;
use App\Services\Common\Contracts\ImageServiceInterface;
use Carbon\Carbon;

class QuestionController extends Controller
{
    public function __construct(private readonly ImageServiceInterface $imagesService)
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $questions = Question::latest()->get();

        return view('questions.question', ['questions' => $questions]);
    }

    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        // Optional: Validate your inputs including the image file
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required',
        //     'correct_answer' => 'required',
        //     'wrong_answer' => 'required',
        //     'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        // ],
        // [
        //     'title.required' => 'هذا الحقل مطلوب',
        //     'correct_answer.required' => 'هذا الحقل مطلوب',
        //     'wrong_answer.required' => 'هذا الحقل مطلوب',
        // ]);
        // if($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }

        // Handle file upload if an image is provided
        $imagePath = null;
        if ($request->file('image')) {
            $imagePath = $this->imagesService->uploadImage($request->file('image'), '/questions');
        }
        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $filename = time() . '.' . $file->getClientOriginalExtension();
        //     // Move the file to the public/images folder
        //     $file->move(public_path('images'), $filename);
        //     $imagePath = 'images/' . $filename;
        // }

        $question_id = Question::insertGetId([
            'title'      => $request->title,
            'image'      => $imagePath, // Save image path or null
            'admin_id'    => Auth::id(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Answer::insert(
            [
                [
                    'title'      => $request->correct_answer,
                    'question_id' => $question_id,
                    'is_correct' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title'      => $request->wrong_answer_1,
                    'question_id' => $question_id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title'      => $request->wrong_answer_2,
                    'question_id' => $question_id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title'      => $request->wrong_answer_3,
                    'question_id' => $question_id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]
        );

        return redirect('question/create');
    }

    public function show($id)
    {
        $question = Question::where('id', $id)->get();

        $correct_answers = Answer::where('question_id', $id)->where('is_correct', 1)->get();

        $wrong_answers = Answer::where('question_id', $id)->where('is_correct', 0)->get();

        return view('questions.show', [
            'question'         => $question,
            'correct_answers'  => $correct_answers,
            'wrong_answers'    => $wrong_answers
        ]);
    }

    public function edit($id)
    {
        $question = Question::where('id', $id)->get();

        $correct_answer = Answer::where('question_id', $id)->where('is_correct', true)->get();

        $wrong_answer = Answer::where('question_id', $id)->where('is_correct', false)->get();

        return view('questions.edit', [
            'question'         => $question,
            'correct_answer'   => $correct_answer,
            'wrong_answer'     => $wrong_answer,
            'id'               => $id
        ]);
    }

    public function update(Request $request, $id)
    {
        // Optional: Validate your inputs including the image file
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required',
        //     'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        // ]);
        // if($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }

        // Prepare the data to update
        $data = [
            'title'      => $request->title,
            'updated_at' => Carbon::now(),
        ];

        // Check if a new image file is uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['image'] = 'images/' . $filename;
        }

        Question::where('id', $id)->update($data);

        // Remove old answers and re-insert new ones
        Answer::where('question_id', $id)->delete();

        Answer::insert(
            [
                [
                    'title'      => $request->correct_answer,
                    'question_id' => $id,
                    'is_correct' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title'      => $request->wrong_answer_1,
                    'question_id' => $id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title'      => $request->wrong_answer_2,
                    'question_id' => $id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title'      => $request->wrong_answer_3,
                    'question_id' => $id,
                    'is_correct' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]
        );

        return redirect('question');
    }

    public function destroy($id)
    {
        Answer::where('question_id', $id)->delete();

        Question::where('id', $id)->delete();

        return redirect('question');
    }

    public function status(Request $request)
    {
        Question::where('id', $request->question_id)->update([
            'status' => $request->status
        ]);

        return back();
    }
}
