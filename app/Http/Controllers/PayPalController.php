<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Omnipay\Omnipay;



class PayPalController extends Controller
{
    public $gateway;
  
    public function __construct()
    {
        
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true); //set it to 'false' when go live
    }
    public function index()
    {
   
        $products = Products::all();
        return view('dashboard',[ 'products' => $products]);
    }
    public function payment(Request $request){
       // dd(env('APP_NAME'));
        $products = Products::find($request->id);
        $amount = $products->price * $request->quantity;
        $available_stock = $products->quantity - $request->quantity;

     
            try {
                $response = $this->gateway->purchase(array(
                    'amount' => $amount,
                    'items' => array(
                        array(
                            'name' => $products->name,
                            'price' => $products->price,
                            'description' => $products->description,
                            'quantity' => $request->quantity
                        ),
                    ),
                    'currency' => env('PAYPAL_CURRENCY'),
                    'returnUrl' => url('success'),
                    'cancelUrl' => url('error'),
                ))->send();
               // dd($response);
               
           
                if ($response->isRedirect()) {
                    Products::where('id', $request->id)->update(['quantity' => $available_stock]);
                    $response->redirect(); // this will automatically forward the customer
                } else {

                   
                   
                    $request->session()->flash('error', $response->getMessage());
                    return back();
                }
            } catch(Exception $e) {
                
                $request->session()->flash('error', $e->getMessage());
                return back();
            }
        
        

    
  

    }
    public function success(Request $request)
    {
        //dd($request);
        // Once the transaction has been approved, we need to complete it.
        if ($request->input('paymentId') && $request->input('PayerID'))
        {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));
            $response = $transaction->send();
          
            if ($response->isSuccessful())
            {
          
                $arr_body = $response->getData();

              
          
               $request->session()->flash('status', 'items successfully paid');
                return back();
              
          
            
            } else {
                $request->session()->flash('error', $response->getMessage());
                return back();
            }
        } else {
      
            $request->session()->flash('error', 'Transaction is declined');
            return back();
        }
    }
  
    public function error(Request $request)
    {
      
        $request->session()->flash('error', 'User is canceled the payment');
        return back();
    }
        

        
    
    //
}
