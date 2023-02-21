<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hotspot;
use App\Models\Payment;
use Session;
use Auth;
use GuzzleHttp;

class PaymentsHotspot extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->is_admin){
            $payments = Payment::all();
            $hotspots = Hotspot::all();
        }
        else{
            $payments = Payment::where("user_id", '=', Auth::user()->id)->get();
            $hotspots = Hotspot::where("owner_id", '=', Auth::user()->id)->get();
        }


        $client = new GuzzleHttp\Client();

        $year = date('Y');
        $last_month = date('m');
        
        // $monthlyEarning = array($last_month);
        // for($i = 0; $i < $last_month + 1; $i++)
        //   $monthlyEarning[$i] = 0;


        // Get Monthly Earnings

            
        for($month = 1; $month <= $last_month - 1; $month++){

            $payment = Payment::where('during', '=', $year . '-'. $month)->where('user_id', Auth::user()->id)->first();
            
            if($payment)
                continue;

            
            $payment = new Payment();
            $payment->user_id = Auth::user()->id;
            $payment->during = $year . '-'. $month;
            $payment->amount = 0;
            $payment->random = $this->generateRandomString(6);
            $payment->status_id = 1;
            $payment->paid_at = null;
            $payment->save();
                    
            foreach($hotspots as $key => $hotspot){
            
                $min_time = date("Y-m-d", strtotime($year . '-' . $month . '-01'));
                $max_time = date("Y-m-t", strtotime($year . '-' . $month));
        
                $url ='https://api.helium.io/v1/hotspots/' 
                . $hotspot["address"] . '/rewards/sum?'
                . 'min_time=' . $min_time . '&max_time=' . $max_time;
                    
                $monthlyEarning = json_decode($client->request('GET', $url, [
                'headers' => [
                    'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
                ]
                ])->getBody()->getContents())->data->total;
                
                $payment->amount += $monthlyEarning;
                $payment->save();
            }
        }


        if(Auth::user()->is_admin){
            $payments = Payment::all();
        }
        else{
            $payments = Payment::where("user_id", '=', Auth::user()->id)->get();
        }

        return view('content.payments.payments-all', compact('payments'));
    }

    function markPaid(Request $request){
        $payment = Payment::find($request->id);
        $payment->status_id = 2;
        $payment->paid_at = date("Y-m-d H:i:s");
        $payment->save();
        return back();
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}