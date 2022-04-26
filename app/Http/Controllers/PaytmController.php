<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class PaytmController extends Controller
{

  // public function __construct()
  // {
  //   $this->middleware('auth');
  // }

    public function paytmPayment(Request $request)
    {
      Session::put('UserData', $request->all());
      // dd($request->all());
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => rand(),
          'user' => $request->user_id,
          'mobile_number' => $request->mobile_no,
          'email' => $request->email,
          'amount' => $request->amount,
          'callback_url' => route('paytm.callback'),
        ]);
        
        return $payment->receive();
    }


    /**
     * Obtain the payment information.
     *
     * @return Object
     */
    public function paytmCallback()
    {
          
        $transaction = PaytmWallet::with('receive');
        
        $response = $transaction->response(); // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm
        
        if($transaction->isSuccessful()){
          $userData = Session::get('UserData');
        
        $data = [
          'order_id' => $transaction->getOrderId(),
          'transaction_id' => $transaction->getTransactionId(),
          'user_id' => $userData['user_id'],
          'email' => $userData['email'],
          'name' => $userData['name'],
          'mobile_no' => $userData['mobile_no']
        ];
        // dd($data);
          return view('paytm.paytm-success-page', compact('data'));
          
        }else if($transaction->isFailed()){
          //Transaction Failed
          return view('paytm.paytm-fail');
        }else if($transaction->isOpen()){
          //Transaction Open/Processing
          return view('paytm.paytm-fail');
        }
        $transaction->getResponseMessage(); //Get Response Message If Available
        //get important parameters via public methods
        $transaction->getOrderId(); // Get order id
        $transaction->getTransactionId(); // Get transaction id
        
    }

    /**
     * Paytm Payment Page
     *
     * @return Object
     */
    public function paytmPurchase($user_id,$name,$email,$mobile_no)
    {
      $data = [
        'user_id' => $user_id,
        'email'  => $email,
        'name' => $name,
        'mobile_no' => $mobile_no
      ];
        return view('paytm.payment-page', compact('data'));
    } 
}
