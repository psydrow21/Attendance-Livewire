<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Biolocation;
use App\Models\Attendance;
use App\Helpers\DeviceHelper;

use App\Helpers\AttendanceHelper;
use DB;


class SyncAttendance extends Component
{
    public function syncAttendance(){


        $syncing_response = AttendanceHelper::AutomaticSyncingAttendance();

        if($syncing_response['success'] == true){
            $this->dispatch('swal:success');
        }else{
            $this->dispatch('swal:error',
                    title : 'error',
                    message : 'Connection failed please check the ip address and ttl status'
            );
        }

        return ;

        // dd(AttendanceHelper::AutomaticSyncingAttendance());
    }

    public function render()
    {
        return view('livewire.sync-attendance');
    }
}
