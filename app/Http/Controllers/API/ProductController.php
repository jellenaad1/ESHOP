<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'category_id' => 'required|max:190',
            'meta_title' => 'required|max:190',

            'slug' => 'required|max:190',
            'name' => 'required|max:190',

            'brand' => 'required|max:190',
            'selling_price' => 'required|max:20',
            'original_price' => 'required|max:20',
            'qty' => 'required|max:4',
            //'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->getMessageBag(),
            ]);
        } else {
            $product = new Product;
            $product->category_id = $request->input('category_id');
            $product->meta_title = $request->input('meta_title');
            $product->meta_keyword = $request->input('meta_keyword');
            $product->meta_descrip = $request->input('meta_descrip');
            $product->slug = $request->input('slug');
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->brand = $request->input('brand');
            $product->selling_price = $request->input('selling_price');
            $product->original_price = $request->input('original_price');
            $product->qty = $request->input('qty');


            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $file->move('uploads/product/', $fileName);
                $product->image = 'uploads/product/' . $fileName;
            }

            $product->featured = $request->input('featured') == true ? '1' : '0';
            $product->popular = $request->input('popular') == true ? '1' : '0';
            $product->status = $request->input('status') == true ? '1' : '0';

            $product->save();

            return response()->json([
                'status' => 200,
                'message' => 'Uspesno ste dodali proizvod!',
            ]);
        }
    }


    public function index()
    {

        $products = Product::all();
        return response()->json([
            'status' => 200,
            'products' => $products,
        ]);
    }


    public function edit($id)
    {

        $product = Product::find($id);

        if ($product) {
            return response()->json([
                'status' => 200,
                'product' => $product,
            ]);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'Proizvod nije pronadjen!',
            ]);
        }
    }

    public function update(Request $request, $id)
    {



        $validator = Validator::make($request->all(), [
            'category_id' => 'required|max:190',
            'meta_title' => 'required|max:190',

            'slug' => 'required|max:190',
            'name' => 'required|max:190',

            'brand' => 'required|max:190',
            'selling_price' => 'required|max:20',
            'original_price' => 'required|max:20',
            'qty' => 'required|max:4',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->getMessageBag(),
            ]);
        } else {
            $product = Product::find($id);

            if ($product) {
                $product->category_id = $request->input('category_id');
                $product->meta_title = $request->input('meta_title');
                $product->meta_keyword = $request->input('meta_keyword');
                $product->meta_descrip = $request->input('meta_descrip');
                $product->slug = $request->input('slug');
                $product->name = $request->input('name');
                $product->description = $request->input('description');
                $product->brand = $request->input('brand');
                $product->selling_price = $request->input('selling_price');
                $product->original_price = $request->input('original_price');
                $product->qty = $request->input('qty');


                if ($request->hasFile('image')) {

                    $path = $product->image;
                    if (File::exists($path)) {
                        File::delete($path);
                    }

                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $fileName = time() . '.' . $extension;
                    $file->move('uploads/product/', $fileName);
                    $product->image = 'uploads/product/' . $fileName;
                }

                $product->featured = $request->input('featured');
                $product->popular = $request->input('popular');
                $product->status = $request->input('status');

                $product->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Uspesno ste izmenili proizvod!',
                ]);
            } else {

                return response()->json([
                    'status' => 404,
                    'message' => 'Proizvod nije pronadjen!',
                ]);
            }
        }
    }
}
