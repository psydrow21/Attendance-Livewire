<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth');
    // }

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

    public function biometrics_display_page(){
        return view('page.biolocationdisplay');
    }

    public function company_display_page(){
        return view('page.companydisplay');
    }

    public function uploading_display_page(){
        return view('page.uploadingdisplay');
    }

    public function phoenix_format_page(){
        return view('page.phoenixformat');
    }

    public function device_action_page(){
        return view('page.deviceactiondisplay');
    }

    public function simplified_attendance_page(){
        return view('page.simplifiedattendancedisplay');
    }

    public function biometrics_model_display_page(){
        return view('page.biometricsmodeldisplay');
    }

    public function ttl_option_page(){
        return view('page.ttloptiondisplay');
    }

    public function daily_sync_page()
    {
        return view('page.dailysyncpage');
    }

    public function skeleton(){
        return view('skeleton.skeleton');
    }
}
