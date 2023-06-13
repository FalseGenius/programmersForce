<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\addressUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     */

    public function checkIn(Request $request) {
        // $ip =  $request->ip();
        $ip = $request->ip();

        $address = Address::where('ip_address', $ip)->first();
        
        if ($address) {
            addressUsers::create([
                "ip_address"=>$ip,
                "checkIn_time"=>Carbon::now()
            ]);

            return response()->json($address);


        }
        return response()->json(['Message'=>"It is a remote user. Your IP is " . $ip]);
    }

    public function checkout(Request $request) {
        $ip = $request->ip();
        $endTime = Carbon::now();
        $address = Address::where('ip_address', $ip)->first();
        if ($address) {
            // Calculate difference between $this->startTime and $endTime
            $ipUser = addressUsers::where('ip_address', $ip)->first();

            $difference = $endTime->diffInHours($ipUser->checkIn_time);
            $ipUser->checkout_time = $endTime;
            $ipUser->stay_duration = $difference;
            $ipUser->save();
            return response()->json([
                "ip_address"=> $ip,
                "location"=> $address->location,
                "stay_duration"=>$difference
            ]);
        }

    }
}