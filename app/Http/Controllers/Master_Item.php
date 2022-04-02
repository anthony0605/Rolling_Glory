<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Master_Item as Data_Master_Item;
use Illuminate\Support\Str;

class Master_Item extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function store(Request $request){
        $user = Auth::user();
        $this->validate($request, [
            'name_item' => 'required',
            'description' => 'required',
            'stock' => 'required',
            'poins' => 'required',
        ]);

        $dataItem = [
            'id' => Str::uuid()->toString(),
            'user_id' => $user->id,
            'name_item' => $request->input('name_item'),
            'description' => $request->input('description'),
            'stock' => $request->input('stock'),
            'poins' => $request->input('poins'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $master_Item = Data_Master_Item::create($dataItem);

        if($master_Item){
            return response()->json([
                'success' => true,
                'message' => 'Add data gift successfully',
                'data' => $master_Item
            ], 201);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Fail add data gift, please try again',
                'data' => ''
            ], 400);
        }
    }

    public function update(Request $request, $id){
        $user = Auth::user();
        $method = $request->method();
        if($method == "PUT"){
            $dataItem = [
                'user_id' => $user->id,
                'name_item' => $request->input('name_item'),
                'description' => $request->input('description'),
                'stock' => $request->input('stock'),
                'poins' => $request->input('poins'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }else if($method == "PATCH"){
            $data = Data_Master_Item::find($id);
            $dataItem = [
                'name_item' => $request->input('name_item') == "" ? $data->name_item : $request->input('name_item'),
                'description' => $request->input('description') == "" ? $data->description : $request->input('description'),
                'stock' => $request->input('stock') == "" ? $data->stock : $request->input('stock'),
                'poins' => $request->input('poins') == "" ? $data->poins : $request->input('poins'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        $master_Item = Data_Master_Item::where('id', $id)->update($dataItem);

        if($master_Item){
            return response()->json([
                'success' => true,
                'message' => 'Update data gift successfully',
                'data' => $dataItem
            ], 201);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Fail update data gift, please try again',
                'data' => ''
            ], 400);
        }
    }

    public function delete($id){
        $master_Item = Data_Master_Item::where('id', $id)->delete();

        if($master_Item){
            return response()->json([
                'success' => true,
                'message' => 'Delete data gift successfully',
                'data' => ''
            ], 201);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Fail delete data gift, please try again',
                'data' => ''
            ], 400);
        }
    }
}
