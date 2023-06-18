<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\addressUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     */


    public function registerIP(Request $request) {
        $ip = $request->ip();
        // explode is split of php. In js, we use split
        $segment = explode(".", $ip);
        // Implode is the join of php. In js, we use join.
        $firstThreeSegments = implode('.', array_slice($segment, 0, 3));

        $local = '';

        $matchingAddress = Address::where('ip_address', $firstThreeSegments)->first();
        if ($matchingAddress) {
            $local = 'PF Ground 1';
        } else {
            $local = 'remote';
        }

        Address::create([
            "ip_address"=>$ip,
            "location"=>$local,
        ]);

        return response()->json(["Message"=>"We have registered your IP. Your IP is ". $ip]);
    }

    // Save user ip/location
    private function registerUserLocIP($user, $ip, $location) {
        $user->addressUsers()->create([
            "ip_address"=>$ip,
            "checkIn_time"=>Carbon::now(),
            "location"=>$location
        ]);
    }

    public function checkIn(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $ip = $request->ip();

        $ipExists = $user->addressUsers()->where('ip_address', $ip)->whereNotNull('checkIn_time')->whereNull('checkout_time')->first();
        if ($ipExists) {
            return response()->json(["Message"=>"You have to check out first before checking in again!"]);
        }
        
        // explode is split of php. In js, we use split.
        $segment = explode(".", $ip);
        // Implode is the join of php. In js, we use join.
        $match = implode('.', array_slice($segment, 0, 3));
        $address = Address::where('ip_address', $match)->first();
        
        if ($address) {
            
            $this->registerUserLocIP($user, $ip, $address->location);

            return response()->json(['Message'=>"You have successfully checked in! Your IP is " . $ip]);
        }
        
        $this->registerUserLocIP($user, $ip, 'remote');
        return response()->json(['Message'=>"You have successfully checked in as a remote user. Your ip is " . $ip]);
        
    }


    private function handleLogic ($differenceInMinutes, $db, $endTime) {
        $differenceInHours = $differenceInMinutes / 60;
        if ($differenceInHours >= 5) {  
            $db->workday_status = "Working day complete";
        } else if ($differenceInHours < 3) {
            $db->workday_status = "Absent";
        } else if ($differenceInHours >= 3 && $differenceInHours < 5) {
            $db->workday_status = "Half day complete";
        } else {
            // Implement it later
        }
        
        $db->stay_duration = $differenceInHours;
        $db->checkout_time = $endTime;
        $db->save();

    }
 
    public function checkout(Request $request) {
        $ip = $request->ip();
        $user = JWTAuth::parseToken()->authenticate();
        $addressUser = $user->addressUsers()->where('ip_address', $ip)->whereNotNull('checkIn_time')->whereNull('checkout_time')->latest('checkIn_time')->first();
        
        if (!$addressUser) {
            return response()->json(["Message"=>"Check in first!"]);
        }

        $endTime = Carbon::now();
        $difference = $endTime->diffInMinutes($addressUser->checkIn_time);
        // $difference = $endTime->diffInHours($addressUser->checkIn_time);

        $this->handleLogic($difference, $addressUser, $endTime);            
        return response()->json($addressUser);
    }
}