<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
    public function index()
    {
        $products = Product::with(['store', 'images'])->get();
        return view('product.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('');
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
            "name" => "required",
            "description" => "required",
            "price" => "required",
            "image" => "required|image",
            "images" => "nullable|array|max:3",
            "images.*" => "image"
        ]);
        if ($validator->fails()) {
            return back()->withError($validator)->withInput($request->all());
        } else {
            $user = $request->user();
            $store = Store::where("user_id", $user->id)->get();
            $productImage = time() . "_"  . uniqid() . "." . $request->image->extension();
            $request->image->move(public_path("storage/products"), $productImage);
            $product = Product::create([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "store_id" => $store->id,
                "image" => "/storage/products/$productImage"
            ]);
            if (!empty($request->images)) {
                foreach ($request->images as $image) {
                    $productImage = time() . "_"  . uniqid() . "." . $image->extension();
                    $image->move(public_path("storage/products"), $productImage);
                    Image::create(["image" => "storage/products/$productImage", "product_id" => $product->id]);
                }
            }
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
        $product = Product::find($id);
        $dbimages = Image::where('product_id', $product->id)->select('image')->get();
        $images = [];
        foreach ($dbimages as $image) {
            array_push($images, $image->image);
        }
        array_unshift($images, $product->image);
        return view('product.show', ["images" => $images, "product" => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        return view('product.edit', ['product' => $product]);
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
        $product = Product::find($id);
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->save();
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('product.index');
    }
}
