<?php
namespace App\Http\Controllers;
use Endroid\QrCode\QrCode;
class QRController extends Controller{

    public function makeQrCode($text){
        header("Content-Type: image/png");
        $qrcode= new \Endroid\QrCode\QrCode($text);
        echo $qrcode->writeString();
    }
}
