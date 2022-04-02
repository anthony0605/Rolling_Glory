<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Master_Item as Data_Master_Item;
use App\Models\Redeem as Data_Redeem;
use App\Models\HistoriRedeem;
use App\Models\User;
use App\Models\Reviews as Data_Reviews;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Reviews extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function store(Request $request, $id){
        $user = Auth::user();
        
        $dataRedeem = Data_Redeem::where('user_id', $user->id)->where('item_id', $id)->first();

        if(!$dataRedeem){
            return response()->json([
                'success' => true,
                'message' => "You can't review this item, you not redeem this item",
                'data' => ''
            ], 201);

        }else{
            $dataReviews = Data_Reviews::where('user_id', $user->id)->where('item_id', $id)->first();
            if($dataReviews){
                return response()->json([
                    'success' => false,
                    'message' => "You can't review this item, you already review this item",
                    'data' => ''
                ], 400);
            }
            
            $dataReviews = [
                'id' => Str::uuid()->toString(),
                'item_id' => $id,
                'user_id' => $user->id,
                'rating' => $request->input('rating'),
                'comend' => $request->input('comend'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $reviews = Data_Reviews::create($dataReviews);
            if($reviews){
                return response()->json([
                    'success' => true,
                    'message' => "Add data review successfully",
                    'data' => $reviews
                ], 201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => "Fail add data review, please try again",
                    'data' => ''
                ], 400);
            }
        }
    }
}
