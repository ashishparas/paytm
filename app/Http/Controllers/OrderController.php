<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
class OrderController extends Controller
{
    public function statusCheck(){
        $status = PaytmWallet::with('status');
        $status->prepare(['order' => '907379963']);
        $status->check();
        
        $response = $status->response(); // To get raw response as array

        dd($response);

        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=txn-status-api-description
        
        if($status->isSuccessful()){
          //Transaction Successful
        }else if($status->isFailed()){
          //Transaction Failed
        }else if($status->isOpen()){
          //Transaction Open/Processing
        }
        $status->getResponseMessage(); //Get Response Message If Available
        //get important parameters via public methods
        $status->getOrderId(); // Get order id
        $status->getTransactionId(); // Get transaction id
    }
}
