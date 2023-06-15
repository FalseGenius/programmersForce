<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\addressUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



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

    

    public function checkIn(Request $request) {
        $ip = $request->ip();

        $ipExists = addressUsers::where('ip_address', $ip)->first();
        if (!empty($ipExists->checkIn_time)) {
            return response()->json(["Message"=>"You have to check out first before checking in again!"]);
        }
        
        // explode is split of php. In js, we use split.
        $segment = explode(".", $ip);
        // Implode is the join of php. In js, we use join.
        $match = implode('.', array_slice($segment, 0, 3));


        $address = Address::where('ip_address', $match)->first();
        

        if ($address) {
            
            addressUsers::create([
                "ip_address"=>$ip,
                "checkIn_time"=>Carbon::now()
            ]);

            return response()->json(['Message'=>"You have successfully checked in! Your IP is " . $ip]);
        }
        
        return response()->json(['Message'=>"You are a remote user. Your ip is " . $ip]);

        
    }


    private function handleLogic ($difference, $db, $endTime) {
        if ($difference > 5) {  
            $db->workday_status = "Working day complete";
        } else if ($difference < 3) {
            $db->workday_status = "Absent";
        } else if ($difference >= 3 && $difference < 5) {
            $db->workday_status = "Half day complete";
        } else {
            // Implement it later
        }
        
        $db->stay_duration = $difference;
        $db->checkout_time = $endTime;
        $db->save();

    }
 
    public function checkout(Request $request) {
        $ip = $request->ip();
        $addressUser = addressUsers::where('ip_address', $ip)->orderBy('id', 'desc')->first();
        
        if (!$addressUser || $addressUser->checkout_time !== null) {
            return response()->json(["Message"=>"Check in first!"]);
        }

        $endTime = Carbon::now();
        $ipUser = addressUsers::where('ip_address', $ip)->first();
        $difference = $endTime->diffInHours($ipUser->checkIn_time);

        $this->handleLogic($difference, $ipUser, $endTime);            
        return response()->json($ipUser);
        // }

        // Logic for remote person
    }
}