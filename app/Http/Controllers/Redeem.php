<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Master_Item as Data_Master_Item;
use App\Models\Redeem as Data_Redeem;
use App\Models\HistoriRedeem;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Redeem extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function store($id){
        $user = Auth::user();
        
        $dataItem = Data_Master_Item::where('user_id', $user->id)->first();
        
        $dataItemByID = Data_Master_Item::where('id', $id)->first();
        if($dataItem){
            return response()->json([
                'success' => true,
                'message' => "You can't redeem this item, this is your item",
                'data' => $dataItem
            ], 201);

        }else{
            DB::beginTransaction();
            if(!$dataItemByID){
                return response()->json([
                    'success' => false,
                    'message' => "This item not found",
                    'data' => ''
                ], 400);
            }
            $sisaPoins = $user->poins - $dataItemByID->poins;
            if($sisaPoins < 0){
                return response()->json([
                    'success' => false,
                    'message' => "You don't have enough poins",
                    'data' => ''
                ], 400);
            }

            // create auto number increment
            $dataHistoryRedeem = HistoriRedeem::orderBy('id_seq', 'desc')->first();
            if($dataHistoryRedeem){
                $id_seq = $dataHistoryRedeem->id_seq + 1;
            }else{
                $id_seq = 1;
            }

            $dataItem = [ 
                'id' => Str::uuid()->toString(),
                'user_id' => $user->id, 
                'item_id'=> $dataItemByID->id, 
                'poins' => $dataItemByID->poins,
                'sisa_poins' => $sisaPoins,
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $master_Item = Data_Redeem::create($dataItem);

            $id_redeem = $master_Item->id;

            $dataHistory = [ 
                'id' => Str::uuid()->toString(),
                'id_redeem' => $id_redeem, 
                'poins' => $dataItemByID->poins,
                'sisa_poins' => $sisaPoins,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),
                'id_seq' => $id_seq,
            ];
            $historiRedeem = HistoriRedeem::create($dataHistory);
            
            $updateStock = Data_Master_Item::where('id', $id)->update([
                'stock' => $dataItemByID->stock - 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $updatePoins = User::where('id', $user->id)->update([
                'poins' => $sisaPoins,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Redeem item successfully",
                'data' => $master_Item
            ], 201);
        }
    }
}
