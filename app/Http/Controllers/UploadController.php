<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

use function GuzzleHttp\Promise\all;

class UploadController extends Controller
{
    //
    public function store(Request $request){
        $validator= Validator::make($request->all(),[
            'file'=>'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf|max:1999'

        ]);

        if($validator){
            $file = new Upload;

            $name = time().'__'.$request->file->getClientOriginalName();
            $filePath= $request->file('file')->storeAs('uploads',$name,'public');

            //$file=time().'__'.$request->file->getClientOriginalName();
            $file->file='/storage/'.$filePath;
            $file->name= $name;
            $file->save();

            return Response(['message'=>'upload successful'],200);
        }else{
            return response(['message'=>'invalid file format']);
        }

    }

    public function downloadFile(Request $request){
        $fileName = $request->input('file_name');
        return Storage::download($fileName);
    }

    public function deleteFiles(Request $request){
        Storage::delete('file.jpg');
    }

    public function viewAllFiles(){
        return Upload::all()->get('name');
    }
}
