<?php

namespace App\Http\Controllers;

use App\Http\PigeonHelpers\imageHelper;
use App\Http\PigeonHelpers\otherHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // direct change parmission

//        $user = User::find(8);
//        $user->assignRole('developer');
//        dd($user->roles);
        $data['page_name']='Dashboard';
        $data['breadcumb']=array(
            array('Dashboard','active')
        );
        return view('admin.home',$data);
    }

    public function ckeditor_image_upload(Request $request){
        if($request->hasFile('upload')) {
            $picture = imageHelper::image_upload($request, 'upload', 'images/ckeditor', 'ck_'.rand(1,100000000000), true, false,'',true,700);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url= asset('storage/images/ckeditor/'.$picture);
            $msg="Image Uploaded Successfully.";
            $response="<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
