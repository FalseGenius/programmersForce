<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CalculateDailySessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-daily-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $users = User::all();
        foreach($users as $user) {
            $totalStayDuration = $user->addressUsers()->whereDate("checkIn_time", Carbon::today())->sum('stay_duration');
            $user->dailySessions()->create([
                'date'=>Carbon::yesterday(),
                'total_stay_duration'=>$totalStayDuration
            ]);
        }


    }
}
