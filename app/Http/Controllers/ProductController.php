<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //dd(Client::where('company_id', base64_decode($company))->get());
         $products = Product::join('providers', 'products.provider_id', '=', 'providers.id')
         ->select('providers.razonsocial as nameprovider', 'providers.id as idprovider', 'products.*')
         ->get();
            return view('products.index', array(
                "products" => $products
            ));
    }

    public function getproductid($id){
        $provider = Product::join('providers', 'products.provider_id', '=', 'providers.id')
        ->select('products.id as productid', 'products.name as productname', 'products.*')
        ->where('products.id', '=', base64_decode($id))
        ->get();
        return response()->json($provider);
    }

    public function getproductall(){
        $product = Product::all();
        return response()->json($product);
    }

    public function getproductbyid($id){
        $product = Product::find($id);
        return response()->json($product);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->state = 1;
        $product->cfiscal = $request->cfiscal;
        $product->type = $request->type;
        $product->price = $request->price;
        $product->provider_id = $request->provider;
        $product->description = $request->description;
        if($request->hasFile("image")){
            $imagen = $request->file("image");
            $nombre =  time()."_".$imagen->getClientOriginalName();
            Storage::disk('products')->put($nombre,  File::get($imagen));
           }
        $product->image = $nombre;
        $product->save();
        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product = Product::findOrFail($request->idedit);
        $product->name = $request->nameedit;
        $product->cfiscal = $request->cfiscaledit;
        $product->type = $request->typeedit;
        $product->price = $request->priceedit;
        $product->provider_id = $request->provideredit;
        $product->description = $request->descriptionedit;
        if($request->hasFile("image")){
            $imagen = $request->file("image");
            if($imagen->getClientOriginalName()!=$request->imageeditoriginal){
                $nombre =  time()."_".$imagen->getClientOriginalName();
            Storage::disk('products')->put($nombre,  File::get($imagen));
            }else{
                $nombre = $request->imageeditoriginal;
            }
           }else{
                $nombre = $request->imageeditoriginal;
           }
        $product->image = $nombre;
        $product->save();
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         //dd($id);
         $Product = Product::find(base64_decode($id));
         $Product->delete();
         return response()->json(array(
             "res" => "1"
         ));
    }
}
