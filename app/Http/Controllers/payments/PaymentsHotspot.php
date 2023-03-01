<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hotspot;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Session;
use Auth;
use GuzzleHttp;

use App\Jobs\ProcessUpdatePaymentAPI;

class PaymentsHotspot extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->is_admin){
            $payments = Payment::orderBy('during','DESC')->get();
            $hotspots = Hotspot::all();
        }
        else{
            $payments = Payment::where("user_id", '=', Auth::user()->id)->orderBy('during','DESC')->get();
            $hotspots = Hotspot::where("owner_id", '=', Auth::user()->id)->get();
        }

        // Verify Jobs are running

        if(count(DB::table('jobs')->get()->all())){
            return view('content.payments.payments-all', compact('payments'));
        }
        $payment_monthly = [];
        // Get current month and last updated month

        foreach ($hotspots as $key => $hotspot) {
            # code...

            // If payment_table is not empty, calculate the pay_month
            $user_id = $hotspot->owner_id;
            if($payments->first()) {
                $begin = date_create($payments->first()->during);
            }
            else {
                $begin = date_create($hotspot->created_at);
            }
            $end = date_create('now');

            $start = $begin;
            $daterange = new DatePeriod($begin,new DateInterval("P1M"), $end);

            foreach($daterange as $date) {
                $next = clone $start;
                $next->modify('first day of next month 00:00:00');
                if($next > $end)
                    break;
                
                // Call API to get reward for one Month

                ProcessUpdatePaymentAPI::dispatch($start, $next, $hotspot->address, $user_id);
                $start = $next;
            }
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