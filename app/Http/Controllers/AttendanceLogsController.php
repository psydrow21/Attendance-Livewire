<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\DeviceHelper;

class AttendanceLogsController extends Controller
{
    //
    public function get_devicename(){


        // dd(DeviceHelper::device_attendance_serial());


        // $device_attendance = DeviceHelper::device_attendance_serial()->attendance;


        return DeviceHelper::device_attendance_serial();
    }

    public function landing_page(){
        return view('skeleton.skeleton');
    }

    public function dashboard_page(){
        return view('page.dashboard');
    }

    public function sync_location_page(){
        return view('page.attendancesyncing');
    }

    public function attendance_display_page(){
        return view('page.attendancedisplay');
    }

}
