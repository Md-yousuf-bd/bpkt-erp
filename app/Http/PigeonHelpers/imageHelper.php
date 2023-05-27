<?php


namespace App\Http\PigeonHelpers;


use Illuminate\Http\Request;
trait imageHelper
{

    public static function image_upload(Request $request,$name,$folder,$filename=null,$time=true,$is_unlink=false,$old_image='',$is_resize=false,$max_resolution=400,$fixed_height=null,$fixed_width=null,$multiple=false,$mult_key=null)
    {
        if (!file_exists('storage/' .$folder)) {
            mkdir('storage/' .$folder, 0777, true);
        }
        //Handle File Upload
        // if($request->hasFile($name)||$multiple)
        // {
        //     if($multiple && isset($mult_key))
        //     {
        //         $fileNameWithExt=$request->file($name)[$mult_key]['file']->getClientOriginalName();
        //     }
        //     else
        //     {
        //         $fileNameWithExt=$request->file($name)->getClientOriginalName();
        //     }
        //     //Get File Name With Extension

        //     //Get Just File Name
        //     if(!isset($filename))
        //     {
        //         $filename=pathinfo($fileNameWithExt,PATHINFO_FILENAME);
        //     }

        //     //Get Just ext
        //     if($multiple && isset($mult_key))
        //     {
        //         $extension=$request->file($name)[$mult_key]['file']->getClientOriginalExtension();
        //     }
        //     else
        //     {
        //         $extension=$request->file($name)->getClientOriginalExtension();
        //     }


        //     //File Name to Store
        //     $fileNameToStore=$filename;
        //     if($time)
        //     {
        //         $fileNameToStore.='_'.time();
        //     }

        //     $fileNameToStore.='.'.$extension;

        //     //Upload Image

        //     if($multiple && isset($mult_key))
        //     {
        //         $path=$request->file($name)[$mult_key]['file']->storeAs('public/'.$folder,$fileNameToStore);
        //     }
        //     else
        //     {
        //         $path=$request->file($name)->storeAs('public/'.$folder,$fileNameToStore);
        //     }

        // }
        // else
        // {
        //     $fileNameToStore='noimage.jpg';
        // }

        if($request->hasFile($name))
        {
            if($multiple && isset($mult_key))
            {
                $fileNameWithExt=$request->file($name)[$mult_key]->getClientOriginalName();
            }
            else
            {
                $fileNameWithExt=$request->file($name)->getClientOriginalName();
            }
            //Get File Name With Extension

            //Get Just File Name
            if(!isset($filename))
            {
                $filename=pathinfo($fileNameWithExt,PATHINFO_FILENAME);
            }

            //Get Just ext
            if($multiple && isset($mult_key))
            {
                $extension=$request->file($name)[$mult_key]->getClientOriginalExtension();
            }
            else
            {
                $extension=$request->file($name)->getClientOriginalExtension();
            }


            //File Name to Store
            $fileNameToStore=$filename;
            if($time)
            {
                $fileNameToStore.='_'.time();
            }

            $fileNameToStore.='.'.$extension;

            //Upload Image

            if($multiple && isset($mult_key))
            {
                $path=$request->file($name)[$mult_key]->storeAs('public/'.$folder,$fileNameToStore);
            }
            else
            {
                $path=$request->file($name)->storeAs('public/'.$folder,$fileNameToStore);
            }

        }
        else
        {
            $fileNameToStore='noimage.jpg';
        }

        if($is_unlink)
        {
            self::image_unlink($folder,$old_image);
        }

        if($is_resize)
        {
            self::image_resize($folder,$fileNameToStore,$max_resolution,$fixed_height,$fixed_width);
        }

        return $fileNameToStore;
    }

    public static function image_unlink($folder,$file)
    {
        if(isset($file)&&$file!=''&&isset($folder)&&$folder!='')
        {
            $file_address='storage/' .$folder.'/'.$file;
            if(file_exists($file_address)){
                unlink($file_address);
        }
        }
    }

    public static function image_resize($folder,$file,$max_resolution,$fixed_height,$fixed_width)
    {
        $file_address='storage/' .$folder.'/'.$file;
        if(file_exists($file_address))
        {
            $x = explode('/', $file_address);
            $i=end($x);
            $ext=pathinfo($i, PATHINFO_EXTENSION);
            if($ext=='jpg'||$ext=='jpeg'||$ext=='JPG'||$ext=='JPEG') {
                $orginal_image = imagecreatefromjpeg($file_address);
                $orginal_width = imagesx($orginal_image);
                $orginal_height = imagesy($orginal_image);

                //echo $orginal_width."*".$orginal_height;

                if(isset($fixed_height)&&isset($fixed_width)&&!isset($max_resolution)){
                    $new_height = $fixed_height;
                    $new_width = $fixed_width;
                }
                elseif((!isset($fixed_height)||!isset($fixed_width))&&isset($max_resolution)){
                    $ratio = $max_resolution / $orginal_width;
                    $new_width = $max_resolution;
                    $new_height = $orginal_height * $ratio;

                    if ($new_height > $max_resolution) {
                        $ratio = $max_resolution / $orginal_height;
                        $new_height = $max_resolution;
                        $new_width = $orginal_width * $ratio;
                    }
                }
                else{
                    $new_height = $orginal_height;
                    $new_width = $orginal_width;
                }

                //echo '<br>'. $new_width."*".$new_height;

                if ($orginal_image) {
                    $new_image = imagecreatetruecolor($new_width, $new_height);
                    imagecopyresampled($new_image, $orginal_image, 0, 0, 0, 0, $new_width, $new_height, $orginal_width, $orginal_height);
                    imageJpeg($new_image, $file_address, 100);
                }
            }
            elseif ($ext=='png'||$ext=='PNG')
            {
                $orginal_image = imagecreatefrompng($file_address);
                $orginal_width = imagesx($orginal_image);
                $orginal_height = imagesy($orginal_image);

                //echo $orginal_width."*".$orginal_height;

                if(isset($fixed_height)&&isset($fixed_width)&&!isset($max_resolution)){
                    $new_height = $fixed_height;
                    $new_width = $fixed_width;
                }
                elseif((!isset($fixed_height)||!isset($fixed_width))&&isset($max_resolution)){
                    $ratio = $max_resolution / $orginal_width;
                    $new_width = $max_resolution;
                    $new_height = $orginal_height * $ratio;

                    if ($new_height > $max_resolution) {
                        $ratio = $max_resolution / $orginal_height;
                        $new_height = $max_resolution;
                        $new_width = $orginal_width * $ratio;
                    }
                }
                else{
                    $new_height = $orginal_height;
                    $new_width = $orginal_width;
                }

                //echo '<br>'. $new_width."*".$new_height;

                if ($orginal_image) {

//                    $new_image = imagecreatetruecolor($new_width, $new_height);
//                    imagecopyresampled($new_image, $orginal_image, 0, 0, 0, 0, $new_width, $new_height, $orginal_width, $orginal_height);
//                    imagepng($new_image, $file_address, 0);


                    $new_image = imagecreatetruecolor($new_width, $new_height);
                    imagealphablending($new_image, false);
                    imagesavealpha($new_image,true);
                    $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
                    imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
                    imagecopyresampled($new_image, $orginal_image, 0, 0, 0, 0, $new_width, $new_height,
                        $orginal_width, $orginal_height);
                    imagepng($new_image, $file_address, 0);
                }
            }
        }
    }

    public static function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir")
                        self::rrmdir($dir."/".$object);
                    else unlink  ($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
