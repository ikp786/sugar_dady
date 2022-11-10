<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use Session;
use Stripe;
   
class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('stripe');
    }
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
      $data =  Stripe\Charge::create ([
                "amount" => 100 * 100,
                "currency" => "inr",
                "source" => $request->stripeToken,
                "description" => "Test payment from itsolutionstuff.com." 
        ]);

      print_r($$data);die;
  
        Session::flash('success', 'Payment successful!');
                  return redirect()->route('test')->with('Payment successful!');

        return back();
    }

    public function test()
    {
        print_r($_REQUEST);
    }
}