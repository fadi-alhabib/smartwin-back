<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $stores = Store::all();
        if ($request->search) {
            $stores = Store::where("name", "LIKE", '%' . $request->search . '%')->get();
        }
        return view("store.index", ["stores" => $stores]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('', ["users" => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "type" => "required|string",
            "country" => "required|string",
            "address" => "required|string",
            "phone" => "required|string",
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        } else {
            $store =  Store::create([
                "name" => $request->name,
                "type" => $request->type,
                "country" => $request->country,
                "address" => $request->address,
                "phone" => $request->phone,
                "points" => 0,
                "user_id" => $request->user_id
            ]);
            return view();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $store = Store::find($id);
        $products = Product::where('store_id', $id)->get();
        return view('', ['store' => $store, "products" => $products]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $store = Store::find($id);
        return view('store.edit', ['store' => $store]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $store = Store::find($id);
        $store->name = $request->input('name');
        $store->type = $request->input('type');
        $store->country = $request->input('country');
        $store->address = $request->input('address');
        $store->phone = $request->input('phone');
        $store->points = $request->input('points');
        $store->is_active = $request->input('is_active') == "on";
        $store->save();
        return redirect()->route('store.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = Store::find($id);
        $store->delete();
        return redirect()->route('store.index');
    }
}
