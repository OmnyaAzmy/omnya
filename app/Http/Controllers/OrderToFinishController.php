<?php

namespace App\Http\Controllers;

use App\Mail\CustomerMail;
use App\Mail\ordertofinish as Mailordertofinish;
use App\Models\OrderToFinish;
use App\Models\Product;
use Illuminate\Http\Request;
use Mail;

class OrderToFinishController extends Controller
{
    public function send(Request $request)
     {
        $product =Product::find($request->product_id);
       // $productPrice = Product::find($product->newprice);
       // dd($product);

       $offer = 10;
       if($product){
        OrderToFinish::create($request->all());


        $product->newprice = $product->oldPrice - ($offer / 100) * $product->oldPrice;

        $product->save();



        //  Send mail to Application Admin
        Mail::to("homebazzar.technology@gmail.com")->send(new Mailordertofinish( $request->name, $request->product_id, $request->email, $request->address, $request->phone_number, $request->description));

        //  send mail from admin to customer
        Mail::to($request->email)->send(new CustomerMail( $request->name, $request->email, $request->address, $request->phone_number, $request->description, $product->newPrice));
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
    }
    else{
        return response()->json(['Error' => 'Please send your order Again']);
    }

       }
}
