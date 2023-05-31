<?php

namespace App\Http\Controllers;

use App\Mail\contact as MailContact;
use App\Models\Contact;
use Illuminate\Http\Request;
use Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
     {
        //dd($request);

        // $request->validate($request-> all), [
        //     'name' => 'required',
        //     'email' => 'required|email',
        //     'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$|min:10',
        //     'subject' => 'required',
        //     'message' => 'required',
        // ]);

        //store data in database
        Contact::create($request->all());


        //  Send mail to Application Admin
        Mail::to("homebazzar.technology@gmail.com")->send(new MailContact( $request->name, $request->phone, $request->email, $request->subject, $request->message));

    //     Mail::send('mail', array(
    //         'name' => $request->get('name'),
    //         'email' => $request->get('email'),
    //         'phone' => $request->get('phone'),
    //         'subject' => $request->get('subject'),
    //         'user_query' => $request->get('message'),
    //         ),
    //         function($message) use ($request){
    //         $message->from($request->email);
    //         $message->to('rowida001@gmail.com', 'Admin')->subject($request->get('subject'));
    //         });
        return response()->json(['success' => 'we have recieved your message and would like to thank you for writing to us.']);

    //        return back()->with('success', 'we have recieved your message and
    //        would like to thank you for writing to us.');
      }

}
