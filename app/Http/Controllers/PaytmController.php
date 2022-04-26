<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use Illuminate\Support\Facades\Auth;
class PaytmController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

    public function paytmPayment(Request $request)
    {
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => rand(),
          'user' => Auth::user()->name,
          'mobile_number' => '123456789',
          'email' => Auth::user()->email,
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
        $data = [
          'order_id' => $transaction->getOrderId(),
          'transaction_id' => $transaction->getTransactionId(),
          'user_id' => Auth::id(),
          'email' => Auth::user()->email,
          'name' => Auth::user()->name,
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
    public function paytmPurchase()
    {
        return view('paytm.payment-page');
    } 
}
