<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Services\Common\Contracts\ImageServiceInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{


    public function __construct(private readonly ImageServiceInterface $imageService)
    {
        $this->middleware('auth:admin');
    }


    // Check the date difference when create or edit ads


    public function index()
    {
        $ads = Advertisement::get();

        return view('advertisements.advertisement', ['ads' => $ads]);
    }


    public function create()
    {
        return view('advertisements.create-advertisement');
    }


    public function store(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'file' => 'required',
                'home_ad' => 'required',
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ],
            [
                'title.required' => 'حقل العنوان مطلوب',
                'file.required' => 'حقل الملف مطلوب',
                'home_ad.required' => 'حقل نوع الاعلان مطلوب',
                'from_date.required' => 'حقل تاريخ البداية مطلوب',
                'to_date.required' => 'حقل تاريخ الانتهاء مطلوب'
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        // check the date difference


        if ($request->file('file')) {
            $file = $request->file('file');
            $file_name = date('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();

            // $path = $file->storeAs('images', $file_name, 'public');
            $path = $this->imageService->uploadImage($file, '/ads');

            // check if the file img or vid

            $is_img = true;

            $video_format = ['mp4', 'ogg', 'ogv', 'ogm', 'avi'];

            if (in_array($file->getClientOriginalExtension(), $video_format)) {
                $is_img = false;
            }
        }



        Advertisement::create([
            'title' => $request->title,
            'path' => $path,
            'home_ad' => $request->home_ad,
            'is_img' => $is_img,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date
        ]);

        return redirect('advertisement');
    }


    public function show($id)
    {
        $ad = Advertisement::where('id', $id)->get();

        return view('advertisement.show-advertisement', ['ad' => $ad]);
    }


    public function edit($id)
    {
        $ad = Advertisement::where('id', $id)->get();

        return view('advertisements.edit-advertisement', ['ad' => $ad]);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'home_ad' => 'required',
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ],
            [
                'title.required' => 'حقل العنوان مطلوب',
                'home_ad.required' => 'حقل نوع الاعلان مطلوب',
                'from_date.required' => 'حقل تاريخ البداية مطلوب',
                'to_date.required' => 'حقل تاريخ الانتهاء مطلوب'
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $data = [
            'title' => $request->title,
            'home_ad' => $request->home_ad,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date
        ];


        if ($request->file('file')) {
            $ad = Advertisement::where('id', $id)->get();

            // Storage::disk('public')->delete($ad[0]->path);

            $file = $request->file('file');
            $file_name = date('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();


            $path = $this->imageService->uploadImage($file, '/ads');

            $data['path'] = $path;
        }


        Advertisement::where('id', $id)->UPDATE($data);

        return redirect('advertisement');
    }


    public function destroy($id)
    {
        $ad = Advertisement::where('id', $id)->get();

        // Storage::disk('files')->delete($ad[0]->path);


        Advertisement::where('id', $id)->delete();

        return redirect('advertisement');
    }


    public function status(Request $request)
    {
        $ad = Advertisement::where('id', $request->id)->first();

        $is_active = !$ad['is_active'];


        Advertisement::where('id', $request->id)->update([
            'is_active' => $is_active
        ]);

        return back();
    }
}
