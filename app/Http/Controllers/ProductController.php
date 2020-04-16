<?php

namespace App\Http\Controllers;

use Auth;
use App\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProduct;
use App\Http\Requests\UpdateProduct;

class ProductController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return $this->successResponse($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProduct $request)
    {
        $data = $request->all();     

        $product = Product::create([
            'name' => $data['name'],
            'status' => $data['status']
        ]);

        if($data['newImage']) {
            $product = $this->saveImage($data['image'], $product);
        }

        $product->save();

        return $this->successResponse($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        $product = Product::findOrfail($product);

        return $this->successResponse($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProduct $request, $product)
    {
        $data = $request->all(); 

        $product = Product::findOrfail($product);
        
        $product->fill([
            'name' => $data['name'],
            'status' => $data['status'] ?? $product->status
        ]);

        if($data['newImage']) {
            $product = $this->saveImage($data['image'], $product);
        }

        $product->save();

        return $this->successResponse($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $product = Product::findOrfail($product);

        $product->delete();

        return $this->successResponse($product);
    }

    private function validatePermission($data) 
    {
        Validator::extend('user_permission', function($attribute, $value, $parameters, $validator) {
            
            if($value == 3 || $value == 4){
                if(Auth::user()->user_group == 2){
                    return true;
                }else{
                    return false;
                }
            }
            return true;
        });

        $validate = Validator::make($data, [
            'status' => 'user_permission'
        ], ['user_permission' => 'Usuário sem permissão!']);

        if($validate->fails()) {
            return $validate->errors();
        }
    }

    /**
     * Saving an image to the product
     * @param  base64 $image   
     * @param  \App\Product $product 
     * @return \App\Product        
     */
    private function saveImage($image, $product) {

        $ext = substr($image, 11, strpos($image, ';') - 11);
        $urlImage = $product->id.'.'.$ext;

        $file = str_replace('data:image/'.$ext.';base64,', '', $image);
        $file = base64_decode($file);

        if($product->image && Storage::disk('public')->exists('products/'.$product->image)){
            Storage::disk('public')->delete('products/'.$product->image);
        }

        Storage::disk('public')->put('products/'.$urlImage, $file);

        $product->image = $urlImage;

        return $product;
    }
}
