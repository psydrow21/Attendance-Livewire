<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Illuminate\Support\Facades\Http;

use App\Models\BioLocation;

use App\Helpers\AttendanceHelper;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {


        $validate_connection = BioLocation::where('status', 1)->orderByDesc('id')->first()->ttl_option;

        // // Attendance Sync
        // if($validate_connection == 64){

        //     $schedule->call(function(){
        //         AttendanceHelper::AutomaticSyncingAttendance();
        //     })->name('automatic_sync')->everyMinute()->withoutOverlapping();

        // }elseif($validate_connection == 255){

        //     $schedule->call(function(){
        //         AttendanceHelper::ManualSyncingAttendance();
        //     })->name('manual_sync')->everyMinute()->withoutOverlapping();

        // }else{

        //     $schedule->call(function(){
        //         echo 'Error';
        //     })->name('error_sync')->everyMinute()->withoutOverlapping();

        // }

        // API Local attendance to Live Server hourly at 45 minutes every minute trigger
        $schedule->call(function(){
            try
            {
                Http::get('http://127.0.0.1:8000/sync_attendance_live');
            }
            catch(\Throwable $error_mess)
            {
                echo $error_mess->getMessage();
            }

            return ;

        })->name('sync_attendance_hourly')->hourlyAt('45')->withoutOverlapping();

        // API Local attendance to Live Server 5:16 pm onwards every minute trigger
        $schedule->call(function(){
            try
            {
                Http::get('http://127.0.0.1:8000/sync_attendance_live');
            }
            catch(\Throwable $error_mess)
            {
                echo $error_mess->getMessage();
            }

            return ;

        })->name('sync_attendance_after_out')
        // ->between('17:16', '20:00')->everyMinute()->withoutOverlapping();
        ->everyMinute()->withoutOverlapping(); // Use this if you want to sync manually the data

        // API Local Location Save to Live Server
        $schedule->call(function(){
            try
            {
                Http::get('http://127.0.0.1:8000/sync_location_live');
            }
            catch(\Throwable $error_mess)
            {
                echo $error_mess->getMessage();
            }

            return ;
        })->name('sync_location')
        // ->twiceDaily(9, 16)->withoutOverlapping();
        ->everyMinute()->withoutOverlapping(); // Use this if you want to sync manually the data

        // API from OMS users to Attendance users
        $schedule->call(function(){
            try
            {
                Http::get('http://127.0.0.1:8000/oms_users_pull');
            }
            catch(\Throwable $error_mess){
                echo $error_mess->getMessage();
            }

            return ;
        })
        // ->twiceDaily(9,16)->withoutOverlapping();
        ->everyMinute()->withoutOverlapping(); // Use this if you want to sync manually the data

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
