<?php

namespace App\Console;
use Carbon\Carbon;
use App\Models\addressUsers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{



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

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->call(function() {
        //     $inactivityThreshold = Carbon::now()->subMinutes(30);
        //     $inactiveUsers = addressUsers::whereNull('checkout_time')->where('checkIn_time', '<', $inactivityThreshold)->get();

        //     foreach($inactiveUsers as $user) {
        //         $endTime = Carbon::now();
        //         $difference = $endTime->diffInMinutes($user->checkIn_time);
        //         $this->handleLogic($difference, $user, $endTime);
        //     }

        // })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
