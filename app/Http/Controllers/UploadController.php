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
            $user = $request->user();
            $request->file('file')->store('files2');
            $path = $request->file('file')->store('files2');
            $file=$request->file('file');
            $file_name=$file->getClientOriginalName();
            $upload = new Upload();
            $upload->url = $path;
            $upload->user_id = $user->id;
            $upload->name = time().'__'.$file_name;
            $upload->save();
            /* this was working to upload, let's try something else
            $user = $request->user();
            $path = $request->file('file')->store('files','public');
            $file=$request->file('file');
            $file_name=$file->getClientOriginalName();
            $upload = new Upload();
            $upload->url = $path;
            $upload->user_id = $user->id;
            $upload->name = time().'__'.$file_name;
            $upload->save();*/
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
            /*
            //code below worked
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

            $upload->save();*/

            return Response(['message'=>'upload successful'],200);
        }else{
            return response(['message'=>'invalid file format']);
        }

    }

    public function downloadFile(Request $request){
        $id=auth()->user()->id ;
        
        $file = Upload::where('name',$request->input('name'))->first();
        $file_id =$file->user_id;
        if($file &&($id==$file_id)){
            $url = $file->url;
            //dd($url);
            //var_dump($url);
           return Storage::download($url);//really need to lookup the asset helper
    }return response(['message'=>'file not in records'],400);
            
    }

    public function deleteFiles(Request $request){
        $name=$request->input('name');
        $file = Upload::where('name',$request->input('name'))->first();
        $id=auth()->user()->id ;
        $file_id =$file->user_id;
        if($file && ($id==$file_id)){
            $url = $file->url;
        Storage::delete($url);
        $file->delete();
        return response(["message"=>"file  {$name} deleted" ],200);

        }
        return response(['message'=>'file not in records']);
        
    }

    public function viewAllFiles(){
        $id=auth()->user()->id ;
        //$result = Upload::all('name')->where('user_id','=',$id);//if I had left the parenthesis empty all fields would be displayed
        $result = Upload::where('user_id','=',$id)->get('name');//if didn't specify name here it would return all fields that fit the query criteria
        if($result==[[]]){
            return response(['message'=>'no files in records']);

        }
        return response([$result],200);
    }
}
