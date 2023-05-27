<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Lang;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function __construct() {
        date_default_timezone_set("Asia/Dhaka");

    }

    function db_backup(){
        set_time_limit(6000);
        if(File::isDirectory(public_path('storage\\' . str_replace(' ','-',Config::get('app.name'))))) {
            $files = File::files(public_path('storage\\' . str_replace(' ','-',Config::get('app.name'))));
            $file_arr = array();
            foreach ($files as $file) {
                $file_address = 'storage/' . str_replace(' ','-',Config::get('app.name')) . '/' . $file->getFilename();
                if (file_exists($file_address)) {
                    unlink($file_address);
                }
            }
        }
        Artisan::queue('backup:run --only-db');
        $files = File::files(public_path('storage\\'.str_replace(' ','-',Config::get('app.name'))));
        $file_arr=array();
        foreach($files as $file){
            array_push($file_arr,$file->getFilename());
        }
        if(count($file_arr)>0) {
            $filePath = public_path() . '/storage/' . str_replace(' ', '-', Config::get('app.name')) . '/' . $file_arr[0];
            if (file_exists($filePath)) {
                return Response::download($filePath);
            } else {
                return back();
            }
        }
        else{
            return back();
        }
    }
    function files_backup(){
        set_time_limit(6000);
        if(File::isDirectory(public_path('storage\\' . str_replace(' ','-',Config::get('app.name'))))) {
            $files = File::files(public_path('storage\\' . str_replace(' ','-',Config::get('app.name'))));
            $file_arr = array();
            foreach ($files as $file) {
                $file_address = 'storage/' . str_replace(' ','-',Config::get('app.name')) . '/' . $file->getFilename();
                if (file_exists($file_address)) {
                    unlink($file_address);
                }
            }
        }
        Artisan::queue('backup:run --only-files');
        $files = File::files(public_path('storage\\'.str_replace(' ','-',Config::get('app.name'))));
        $file_arr=array();
        foreach($files as $file){
            array_push($file_arr,$file->getFilename());
        }
        if(count($file_arr)>0) {
            $filePath = public_path() . '/storage/' . str_replace(' ', '-', Config::get('app.name')) . '/' . $file_arr[0];
            if (file_exists($filePath)) {
                return Response::download($filePath);
            } else {
                return back();
            }
        }
        else{
            return back();
        }
    }
    function all_backup(){
        set_time_limit(6000);
        if(File::isDirectory(public_path('storage\\' . str_replace(' ','-',Config::get('app.name'))))) {
            $files = File::files(public_path('storage\\' . str_replace(' ','-',Config::get('app.name'))));
            $file_arr = array();
            foreach ($files as $file) {
                $file_address = 'storage/' . str_replace(' ','-',Config::get('app.name')) . '/' . $file->getFilename();
                if (file_exists($file_address)) {
                    unlink($file_address);
                }
            }
        }
        Artisan::queue('backup:run');
        $files = File::files(public_path('storage\\'.str_replace(' ','-',Config::get('app.name'))));
        $file_arr=array();
        foreach($files as $file){
            array_push($file_arr,$file->getFilename());
        }
        if(count($file_arr)>0) {
            $filePath = public_path() . '/storage/' . str_replace(' ', '-', Config::get('app.name')) . '/' . $file_arr[0];
            if (file_exists($filePath)) {
                return Response::download($filePath);
            } else {
                return back();
            }
        }
        else{
            return back();
        }
    }

    function set_locale($locale){
        Session::put('locale',$locale);
        return redirect()->back();
    }
}
