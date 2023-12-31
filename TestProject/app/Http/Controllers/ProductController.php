<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index()
    {
        $products=Product::get();
        return response()->json([
            'data'=>$products,
        ],200);
    }
/////////////////////////////
public function product_details($id){
    $product=Product::where('id',$id)->get();
    return response()->json([
        'data'=>$product,
    ],200);
}
////////////////////////////
    public function createProduct(Request $req)
    {
        $req->validate([
            'name'=>'required',
            'trade_name'=>'required',
            'date'=>'required',
            'company'=>'required',
            'price'=>'required',
            'amount'=>'required',
            'category_id'=>'required',
        ]);

        if($req->price<0){
            return response()->json(['price can not be under zero'],400);
        }
        
        $product=Product::create([
            'name'=>$req->name,
            'trade_name'=>$req->trade_name,
            'date'=>$req->date,
            'company'=>$req->company,
            'price'=>$req->price,
            'amount'=>$req->amount,
            'category_id'=>$req->category_id,
            'user_id'=>auth()->user()->id,
        ]);
        
        return response()->json([
            'data'=>$product,
        ],200);

    }

  
    public function search(Request $request)
    {
        $request->validate([
            'name'=>'required'
        ]);
    $product_name=Product::where('name','like','%'.$request->name.'%')->get();
    $category=Category::where('name','like','%'.$request->name.'%')->first();

        if(count($product_name)>0){
            return response()->json([
                'data'=>$product_name,
               ],200);
        } 
        else if($category){
            $product=Product::where('category_id',$category->id)->get();

            if(count($product)>0){
                return response()->json([
                    'data'=>$product,
                   ],200);
            } 

            return response()->json([
                'message'=>'product not found ',
               ],400);
        } 
    

       return response()->json([
        'message'=>'this not found ',
       ],400);
    }


    public function productCategories(Request $request){// إعادة أدوية حسب التصنيف
        $request->validate([
            'id'=>'required'
        ]);
        $products=Product::where('category_id',$request->id)->get();
        return response()->json([
            'data'=>$products,
           ],200);
    }

//////////////////////////////////////
    public function editPrice(Request $request){
        $request->validate([
            'id'=>'required',
            'new_price'=>'required|gt:0|'
        ]);
        $product=Product::where('id',$request->id)->first();
        $product->price=$request->new_price;
        $product->save();
        
        return response()->json([
            'data'=>$product,
           ],200);
    }

///////////////////////////

public function editAmount(Request $request){
    $request->validate([
        'id'=>'required',
        'new_amount'=>'required|gt:0|'
    ]);
    $product=Product::where('id',$request->id)->first();
    $product->amount+=$request->new_amount;
    $product->save();
    
    return response()->json([
        'data'=>$product,
       ],200);
}




   
}
