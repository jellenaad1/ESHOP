<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {


        if (auth('sanctum')->check()) {

            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $qty = $request->product_qty;

            $productCheck = Product::where('id', $product_id)->first();

            if ($productCheck) {
                if (Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists()) {
                    return response()->json([
                        'status' => 409,
                        'message' => $productCheck->name . ' je vec dodat u korpu!'
                    ]);
                } else {

                    $cartitem = new Cart;
                    $cartitem->user_id = $user_id;
                    $cartitem->product_id = $product_id;
                    $cartitem->qty = $qty;
                    $cartitem->save();




                    return response()->json([
                        'status' => 201,
                        'message' => 'Uspesno ste dodali proizvod u korpu!'
                    ]);
                }
            } else {

                return response()->json([
                    'status' => 404,
                    'message' => 'Proizvod nije pronadjen!'
                ]);
            }
        } else {

            return response()->json([
                'status' => 401,
                'message' => 'Morate biti prijavljeni da biste dodali proizvod u korpu!'
            ]);
        }
    }

    public function viewcart()
    {

        if (auth('sanctum')->check()) {

            $user_id = auth('sanctum')->user()->id;
            $cartItems = Cart::where('user_id', $user_id)->get();
            return response()->json([
                'status' => 200,
                'cart' => $cartItems,

            ]);
        } else {

            return response()->json([
                'status' => 401,
                'message' => 'Morate biti prijavljeni da biste videli korpu!'
            ]);
        }
    }


    public function updateQuantity($cart_id, $scope)
    {

        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $cartItem = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();

            if ($scope == "inc") {
                $cartItem->qty += 1;
            } else if ($scope == "dec") {
                $cartItem->qty -= 1;
            }

            $cartItem->update();
            return response()->json([
                'status' => 200,
                'message' => 'Uspesno ste promenili kolicinu!',
            ]);
        } else {

            return response()->json([
                'status' => 401,
                'message' => 'Prijavite se da biste nastavili!'
            ]);
        }
    }

    public function deleteCartItem($cart_id)
    {

        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $cartItem = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();

            if ($cartItem) {
                $cartItem->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Uspesno ste uklonili proizvod iz korpe!',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Nije pronadjeno!'
                ]);
            }
        } else {

            return response()->json([
                'status' => 401,
                'message' => 'Prijavite se da biste nastavili!'
            ]);
        }
    }
}
