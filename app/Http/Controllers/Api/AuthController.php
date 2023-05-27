<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\LogController as Logs;
use App\Http\Controllers\UserDetailController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name'=>'required',
            'username'=>'email|required|unique:users',
            'email'=>'email|required|unique:users',
            //'password'=>'required|confirmed|min:8',
        ]);
        $validateData['password']=bcrypt('1111');
        $validateData['decrypted_password']='1111';

        $user=User::create($validateData);
       // $user->attachRole(Role::where('name','Temporary')->first());
       // $accessToken=$user->createToken('authToken')->accessToken;

        return response(['user'=>$user]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);
        if(!auth()->attempt($loginData)) {
            return response(['message'=>'Password does not match. ']);
        }
        $accessToken =auth()->user()->createToken('authToken')->accessToken;
        $user=auth()->user();
        $role = $user->roles()->first();
        $user->role=$role->name;
        return response(['user'=>$user,'access_token'=>$accessToken]);
    }

    public function get_users()
    {
        $user=auth()->user();
        //$users=User::all();
        return response(['user'=>$user->id]);
    }

    public function logoutApi()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }
    }

    public function updatePasswordApi(Request $request)
    {
        $user=User::find(auth()->user()->id);
        if($request->input('new_password')!='1111') {
            if (Hash::check($request->input('old_password'), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
                $user->decrypted_password = $request->input('new_password');
                $user->save();
                if($request->input('old_password')=='1111'){
                    $roles=$user->roles()->get();
                    foreach ($roles as $role)
                    {
                        //$user->detachRole(Role::where('name', $role->name)->first());
                    }
                   // $user->attachRole(Role::where('name', 'Normal')->first());
                }
                Logs::store($user->name . ' has Changed Password ', 'Update Password', 'success', $user->id);
                return response(['status'=>1,'message'=> 'Password has been Changed!']);
            } else {
                return response(['status'=>0,'message'=> 'Password has not Matched!']);
            }
        }
        else
        {
            return response(['status'=>0,'message'=> 'Changed password should not be 1111!']);
        }
    }
}
