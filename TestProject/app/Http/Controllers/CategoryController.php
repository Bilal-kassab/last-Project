<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function createCategory(Request $request){

        $request->validate([
            'name'=>'required'
       ]);

       $category=Category::create([
        'name'=>$request->name
       ]);

       return response()->json([
            'data'=>$category
       ],200);

    }

    public function showCategories(){
        $categories=Category::get();

        return response()->json([
            'data'=>$categories
        ],200);
    }
}
