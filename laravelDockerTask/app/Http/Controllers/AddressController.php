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
        $segment = explode(".", $ip);
        $firstThreeSegments = implode('.', array_slice($segment, 0, 3));

        $matchingAddress = Address::where(DB)

        Address::create([
            "ip_address"=>$ip,
            "location"=>"PF Ground 1"
        ]);

        return response()->json(["Message"=>"We have registered your ip"]);
    }

    

    public function checkIn(Request $request) {
        $ip = $request->ip();

        $address = Address::where('ip_address', $ip)->first();
        // $location = $address->location ?? 'remote';
        // if ($address) {
            
        //     return response()->json($address);

        // }
        
        addressUsers::create([
            "ip_address"=>$ip,
            "checkIn_time"=>Carbon::now()
        ]);
        

        if ($address) {
            return response()->json(['Message'=>"Your office IP is " . $ip]);
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
        $address = Address::where('ip_address', $ip)->first();
        $location = $address->location ?? 'remote';
        $endTime = Carbon::now();

        $ipUser = addressUsers::where('ip_address', $ip)->first();
        $difference = $endTime->diffInHours($ipUser->checkIn_time);

        $this->handleLogic($difference, $ipUser, $endTime);            
        return response()->json($ipUser);
        // }

        // Logic for remote person
    }
}