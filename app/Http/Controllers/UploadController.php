<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Exists;
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
            $user_id = $request->user()->id;
            /*
            use Illuminate\Http\Request;
                public function update(Request $request)
                {
                    $request->user(); //returns an instance of the authenticated user...
                    $request->user()->id; // returns authenticated user id.
                }

                or use the auth helper function

                auth()->user();  //returns an instance of the authenticated user...
                auth()->user()->id ; // returns authenticated user id.

            */
            $upload = new Upload;
            $file = $request->file('file');
            $destination_path ="public/files";
            $file_name=$file->getClientOriginalName();

            $name = time().'__'.$file_name;
            $filePath= $request->file('file')->storeAs($destination_path,$file_name);

            //$file=time().'__'.$request->file->getClientOriginalName();
            $upload->name=$name;
            var_dump($user_id);
            $upload->user_id = $user_id;

            $upload->save();

            return Response(['message'=>'upload successful'],200);
        }else{
            return response(['message'=>'invalid file format']);
        }

    }

    public function downloadFile(Request $request){


        $path = public_path('file.png');
        return response()->download($path);
      /*  if(Storage::disk('public')->exists("storage/files/$request->file")){
            $path = Storage::disk('public')->path("storage/files/$request->file");
            $content = file_get_contents($path);
            return response($content)->withHeaders([
                "content-type"=>mime_content_type($path)
            ]);

        }

        return response(['message'=>'file not found in database'],404);*/

       /* $fileName = $request->input('file_name');
        return Storage::download($fileName);*/
    }

    public function deleteFiles(Request $request){
        Storage::delete('file.jpg');
    }

    public function viewAllFiles(){
        return Upload::all()->get('name');
    }
}
