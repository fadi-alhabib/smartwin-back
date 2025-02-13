<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Privilege;
use App\Models\UserPrivilege;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{


    public function __construct()
    {
        // $this->middleware('auth:sanctum');
    }



    public function index()
    {
        $users = User::orderBy('points', 'DESC')->limit(5)->get();

        return view('home', ['users' => $users]);
    }
}
