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
            $user = $request->input('user');//I am trying to get the user attribute that was added during authentication
            $upload = new Upload;
            $file = $request->file('file');
            $destination_path ="public/files";
            $file_name=$file->getClientOriginalName();

            $name = time().'__'.$file_name;
            $filePath= $request->file('file')->storeAs($destination_path,$file_name);

            //$file=time().'__'.$request->file->getClientOriginalName();
            $upload->name=$name;
            var_dump($user);
            $upload->user_id = $user;

            $upload->save();

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
