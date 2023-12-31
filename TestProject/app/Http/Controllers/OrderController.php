<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDeatiles;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function orderDeatiles($id)// تفاصيل طلبية ما
    {
       // $o1=Order::get()->where('order_id',$id)->product;
        $o1=Order::findorfail($id)->products;
        $q=OrderDeatiles::where('order_id',$id)->get();
         
        foreach($o1 as $key=>$o){
            $pay[$key]=['order'=>$o,
            'quntity'=>$q[$key]];
        }
       
        return response()->json([
            'data'=> $pay
        ],200);
    }

    public function orders()//  عرض الطلبات الخاصة ب صيدلاني
    {
        $order=Order::where('user_id',auth()->user()->id)->get();
        return response()->json([
            'data'=>$order,
        ],200);
    }

    public function allOrders()//برجع جميع الفواتير مع اصحابها admin
    {
        $o1=Order::with('user')->get();
        return $o1;
    }


    /**
     * Show the form for creating a new resource.
     */

     
    public function createbill(Request $req)
    {
            
        $o=Order::create([
            'user_id'=>auth()->user()->id,
            'price_all'=>0
        ]);
    // $price_all=0; 
    // $price_all=Self::store($o->id,$req);

    // if($price_all ||$price_all>0){
    //     $o->price_all=$price_all;
    //     $o->save();
    //     return response()->json(['price_all'=>$o->price_all,'message'=>'bill created successfully'],200);
    // }
    // else{

            return response()->json(['order_id'=>$o->id,'message'=>'bill created successfully'],200);
       // }
     } 
   ///////////////////////////////
   public function store2(Request $req){
     
    $validator = Validator::make($req->all(), [

        'product_id'=>'required',
        'quntity'=>'required|gt:0|',
    ]);
    
    if($validator->fails()){
        return response()->json($validator->errors()->toJson(),400);
    }
    $var=0;
   
    $order=Order::findorfail($req->order_id);
    $p=Product::findorfail($req->product_id);

    if($p->amount< $req->quntity){
        return response()->json([
                    'message'=>'there is no amount that u want',
                ],400);
        }

        $od=OrderDeatiles::create([
            'order_id'=> $req->order_id,
            'product_id'=>$req->product_id,
            'quntity'=>$req->quntity,
            'price'=>$p->price
        ]);
     $p->amount-=$req->quntity;
     $p->save();
    $order->price_all+=($p->price*$req->quntity);
    $order->save();

    return response()->json(['price'=>$order->price_all,'message'=>'Add successfully'],200);
    
   }



   ///////////////////////////////

   ///////////////////
    public function store($order_id,Request $req)
    {
        $price_all=0;

        $order=Order::findorfail($order_id);
         
        foreach($req->product_id as $key=>$product){

            $p=Product::findorfail($product);

            // if(($req->quntity[$key])<=0.0){
            //     $order->delete();
            //     return response()->json([
            //         'message'=>'Quantity should not be less than or equal zero',
            //     ],404);
            // }

            if($p->amount< $req->quntity[$key]){
            return response()->json([
                        'message'=>'there is no amount that u want',
                    ],404);
            }
         else{ 

            $od=OrderDeatiles::create([
            'order_id'=> $order_id,
            'product_id'=>$product,
            'quntity'=>$req->quntity[$key],
            
        ]);
       
        $price_all+=($p->price*$req->quntity[$key]);
        }
    }

        return $price_all;
    }

    
    public function status(Request $request)
    {
        $request->validate([
            'id'=>'required',
            'status'=>'required'
        ]);
        $order=Order::find($request->id);
        $order->status=$request->status;
        $order->save();

        return response()->json([
            'status'=>$order,
        ]);
    }

    public function DeleteOrder(Request $request){

        $validator = Validator::make($request->all(), [
            'order_id'=>'required',
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $order=Order::findorfail($request->order_id);

        // if(auth()->user()->id!=$order->user_id){
        //     return response()->json([
        //         'message'=>'not belong to u'
        //     ],400);
        // }

        if($order->status>0){
            return response()->json([
                'message'=>'can not deleted'
            ],400);
        }
        
        $orderdeatiles=OrderDeatiles::where('order_id',$order->id)->get();

        foreach($orderdeatiles as $o){
            self::DeleteOrder2($o);
        }

         $order->delete();

        return response()->json([
            'message'=>'Deleted Done!!'
        ],200);

    }
    
    public function DeleteOrder2(OrderDeatiles $order){

         $p=Product::where('id',$order->product_id)->first();
         $p->amount+=$order->quntity;   
         $p->save();
    }


    public function PaymentStatus(Request $request){

        $validator = Validator::make($request->all(), [
            'order_id'=>'required',
            'status'=>'required'
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $order=Order::findorfail($request->order_id);

        $order->payment_status=$request->status;
        $order->save();

        return response()->json([
            'message'=>'Changed Payment Status'
        ],200);

    }
   
}
