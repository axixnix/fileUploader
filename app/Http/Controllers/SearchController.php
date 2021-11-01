<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Upload;

class SearchController extends Controller
{
    //
    public function search(Request $request){
        $string = $request->input('string');
        $result = Upload::where('name','LIKE','%'.$string.'%')->get();

         if($result){
            return response(['result'=>$result],200);
         }else{
             return response(['message'=>'no files match your search'],400);
         }



    }
}
