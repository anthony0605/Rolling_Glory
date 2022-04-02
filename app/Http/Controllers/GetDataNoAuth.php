<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Master_Item as Data_Master_Item;
use Illuminate\Support\Str;

class GetDataNoAuth extends Controller
{
    public function index(Request $request){
        $data = Data_Master_Item::select('master_item.*', DB::raw('count(reviews.id) as count_review'), DB::raw('SUM(reviews.rating) as sum_review'))
        ->leftJoin('reviews', 'master_item.id', '=', 'reviews.item_id')
        ->groupBy('master_item.id')
        ->orderBy('master_item.created_at', 'desc');

        if($request->input('pagination') != ""){
            $data = $data->paginate($request->input('pagination'));
        }else{
            $data = $data->paginate(10);
        }
        foreach($data as $key => $value){
            $data[$key]->rating = $value->sum_review / $value->count_review;
        }
        if($request->input('rating') != ""){
            $data->sortBy(['rating' => $request->input('rating')]);
        }
        return response()->json([
            'success' => true,
            'message' => "Get data master item successfully",
            'data' => $data
        ], 200);

    }

    public function indexBy(Request $request, $id){
        $data = Data_Master_Item::select('master_item.*', DB::raw('count(reviews.id) as count_review'), DB::raw('SUM(reviews.rating) as sum_review'))
        ->leftJoin('reviews', 'master_item.id', '=', 'reviews.item_id');
        if($id != ""){
            $data = $data->where('master_item.id', $id);
        }
        $data = $data->groupBy('master_item.id')
        ->orderBy('master_item.created_at', 'desc');

        if($request->input('pagination') != ""){
            $data = $data->paginate($request->input('pagination'));
        }else{
            $data = $data->paginate(10);
        }
        foreach($data as $key => $value){
            $data[$key]->rating = $value->sum_review / $value->count_review;
        }
        if($request->input('rating') != ""){
            $data->sortBy(['rating' => $request->input('rating')]);
        }
        return response()->json([
            'success' => true,
            'message' => "Get data master item successfully",
            'data' => $data
        ], 200);

    }
}