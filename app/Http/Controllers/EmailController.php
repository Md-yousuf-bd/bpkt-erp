<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public static function send($mail_data)
    {
        //
        //$view_name,$view_data,$from_mail,$to_mail,$from_name,$to_name,$subject,$mail_purpose,$mail_type
        Mail::send($mail_data['view_name'], $mail_data['view_data'], function ($m) use($mail_data){
            $m->from($mail_data['from_mail'], $mail_data['from_name']);
            $m->to($mail_data['to_mail'], $mail_data['to_name'])->subject($mail_data['subject']);
        });
        $email=new Email();
        $email->content=view($mail_data['view_name'], $mail_data['view_data']);
        $email->from=$mail_data['from_mail'];
        $email->to=$mail_data['to_mail'];
        $email->from_name=$mail_data['from_name'];
        $email->to_name=$mail_data['to_name'];
        $email->subject=$mail_data['subject'];
        $email->mail_type=$mail_data['mail_type'];
        $email->mail_purpose=$mail_data['mail_purpose'];
        $email->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function edit(Email $email)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Email $email)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email)
    {
        //
    }
}
