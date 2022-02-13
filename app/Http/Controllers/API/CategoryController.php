<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{


    //upisivanje podataka o kategoriji u bazu
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',



        ]);
        if ($validator->fails()) {

            return response()->json([
                'status' => 400,
                'errors' => $validator->getMessageBag(),

            ]);
        } else {

            $category = new Category();
            $category->meta_title = $request->input('meta_title');
            $category->meta_keyword = $request->input('meta_keyword');
            $category->meta_descrip = $request->input('meta_descrip');
            $category->slug = $request->input('slug');
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->status = $request->input('status') == true ? '1' : '0';

            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'Uspesno ste dodali kategoriju!'
            ]);
        }
    }

    //prikaz svih kategorija
    public function index()
    {
        $category = Category::all();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }

    //vrava kategoriju koju treba prikazati ili izmeniti
    public function edit($id)
    {
        $category = Category::find($id);

        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ]);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'Kategorija nije pronadjena!',
            ]);
        }
    }

    //izmena podataka o kategoriji
    public function update(Request $request, $id)
    {
        //request su input polja ono sto unosimo

        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',



        ]);
        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'errors' => $validator->getMessageBag(),

            ]);
        } else {

            $category = Category::find($id);
            if ($category) {
                $category->meta_title = $request->input('meta_title');
                $category->meta_keyword = $request->input('meta_keyword');
                $category->meta_descrip = $request->input('meta_descrip');
                $category->slug = $request->input('slug');
                $category->name = $request->input('name');
                $category->description = $request->input('description');
                $category->status = $request->input('status') == true ? '1' : '0';

                $category->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Uspesno ste izmenili kategoriju!'
                ]);
            } else {

                return response()->json([
                    'status' => 404,
                    'message' => 'Nije pronadjena kategorija!'
                ]);
            }
        }
    }

    //brisanje kategorije
    public function destroy($id)
    {

        $category = Category::find($id);
        if ($category) {

            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Uspesno ste obrisali kategoriju!',
            ]);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'Kategorija nije pronadjena!',
            ]);
        }
    }


    //za kombo proizvode - kategorije koje imaju status 0 tj koje su prikazane
    public function allcategory()
    {

        $category = Category::where('status', 0)->get();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }
}
