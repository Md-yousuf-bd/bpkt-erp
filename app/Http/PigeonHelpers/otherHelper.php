<?php


namespace App\Http\PigeonHelpers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DateTime;


trait otherHelper
{

    public static function split_array($data,$key){
        $result=array();
        foreach ($data as $d)
        {
            if(is_array($data))
            {
                array_push($result,$d[$key]);
            }
            else if(is_object($data))
            {
                array_push($result,$d->{$key});
            }

        }
        return $result;
    }

    public static function get_dates_by_range($date1, $date2, $format = 'Y-m-d' ) {
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while( $current <= $date2 ) {
            $dates[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        return $dates;
    }

    public static function change_date_format($orginal_date,$day_name=true,$changed_format='d-M-Y')
    {
        if(isset($orginal_date)&&$orginal_date!=null) {
            $newDate = date($changed_format, strtotime($orginal_date));
            $day = '';
            if ($day_name) {
                $day = date('l', strtotime($orginal_date)) . ', ';
            }

            return $day . $newDate;
        }
        else{
            return '';
        }
    }

    public static function calculate_age($dob,$today='')
    {
        $bday = new DateTime($dob); // Your date of birth
        if($today=='')
        {
            $today = new Datetime(date('Y-m-d'));
        }
        else
        {
            $today=new Datetime($today);
        }
        $diff = $today->diff($bday);
        return $diff->y .' years,'.$diff->m .' months,'. $diff->d.' days';
    }

    public static function calculate_age_year($dob,$today='')
    {
        $bday = new DateTime($dob); // Your date of birth
        if($today=='')
        {
            $today = new Datetime(date('Y-m-d'));
        }
        else
        {
            $today=new Datetime($today);
        }
        $diff = $today->diff($bday);
        return $diff->y;
    }

    public static function  taka_format($amount = 0)
    {
        $minus='';
        if($amount<0){
            $minus='-';
        }
        $amount = abs($amount);
        if ($amount != 0) {
            $tmp = explode('.', $amount); // for float or double values
            $strMoney = '';
            $divide = 1000;
            $amount = $tmp[0];

            if($amount<100)
            {
                $strMoney .= str_pad($amount % $divide, 2, '0', STR_PAD_LEFT);
            }
            else
            {
                $strMoney .= str_pad($amount % $divide, 3, '0', STR_PAD_LEFT);
            }
            $amount = (int)($amount / $divide);
            $i = 1;
            while ($amount > 0) {
                $divide = 100;
                if ($i <= 3) {
                    $i++;
                    $strMoney = str_pad($amount % $divide, 2, '0', STR_PAD_LEFT) . '' . $strMoney;
                } elseif($i <= 7) {
                    $strMoney = str_pad($amount % $divide, 2, '0', STR_PAD_LEFT) . $strMoney;;
                }
                $amount = (int)($amount / $divide);
            }

            if (substr($strMoney, 0, 1) == '0')
            {
                $strMoney = substr($strMoney, 1);
            }

            if (isset($tmp[1])) { // if float and double add the decimal digits here.
                if (strlen($tmp[1]) >= 2) {
                    return $minus.$strMoney . '.' . substr($tmp[1], 0, 2);
                }
                else {
                    return $minus.$strMoney . '.' . $tmp[1].'0';
                }
            } else {
                return $minus.$strMoney;
            }

            return $minus.$strMoney;
        } else {
            return '0.00';
        }
    }

    public static $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০","সোম","মঙ্গল","বুধ","বৃহস্পতি","শুক্র","শনি","রবি","জানুয়ারি","ফেব্রুয়ারি","মার্চ","এপ্রিল","মে","জুন","জুলাই","আগস্ট","সেপ্টেম্বর","অক্টোবর","নভেম্বর","ডিসেম্বর");
    public static $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

    public static function bn2en($number) {
        return str_replace(self::$bn, self::$en, $number);
    }

    public static function en2bn($number) {
        return str_replace(self::$en, self::$bn, $number);
    }

    public static function shorten_long_text($str,$btn_id,$max_length=100)
    {
        if(strlen($str)<=$max_length){
            return $str;
        }
        else{
            $length = strlen($str);
            $output[0] = substr($str, 0, $max_length);
            $output[1] = substr($str, $max_length, $length );
            return $output[0].'<span class="collapse" id="'.$btn_id.'">'.$output[1].'</span><span><a href="#'.$btn_id.'" data-toggle="collapse" style="font-size: 10px; color:white; padding:0px 3px !important;" class="btn btn-info btn-sm" onclick="changeMoreText(this);">More</a></span>';
        }
    }

    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
    public  static function  ymd2dmy($d)
    {
        return (((string)$d == '' || (string)$d == '0000-00-00') ? '' : date("d-m-Y", strtotime($d)));
    }

   public  static function dmy2ymd($d)
    {
        if ($d == '' || $d == '0000-00-00') {
            return '';
        }
        list($d, $m, $Y) = explode("-", $d);
        return Date("Y-m-d", mktime(0, 0, 0, $m, $d, $Y));
    }
}
