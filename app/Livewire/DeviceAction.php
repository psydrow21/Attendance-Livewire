<?php

namespace App\Livewire;

use App\Models\Biolocation;

use App\Helpers\DeviceHelper;

use Livewire\Component;

class DeviceAction extends Component
{

    public $fetch_active_location;
    public $active_ip;
    public $active_ttl;
    public $location;

    public function mount(){
        $this->fetch_active_location = Biolocation::where('status', 1)->orderByDesc('id')->first();
        $this->active_ip = $this->fetch_active_location->ip;
        $this->active_ttl = $this->fetch_active_location->ttl_option;
        $this->location = $this->fetch_active_location->location;
    }

    public function render()
    {
        return view('livewire.device-action');
    }

    // Working
    public function deviceTestVoice(){

        if($this->fetch_active_location){
            $fetch_test_voice_response = DeviceHelper::device_test_voice($this->fetch_active_location->ip);

            if($fetch_test_voice_response['success'] == true){
                $this->dispatch('swal:success',
                    title : 'success',
                    message : 'Device Connected and Tested Successfully',

                );
            }else{
                $this->dispatch('swal:error',
                    title : 'error',
                    message : 'Connection failed please check the ip address and ttl status'
                );
            }

        }else{

            $this->dispatch('swal:error',
                title : 'error',
                message : 'There is no registered active biometrics connection in the system'
            );

        }


    }

    // Working
    public function devicePowerOff(){
        if($this->fetch_active_location){

            $fetch_test_poweroff_response = DeviceHelper::device_power_off($this->fetch_active_location->ip);

            if($fetch_test_poweroff_response['success'] == true){
                $this->dispatch('swal:success',
                    title : 'success',
                    message : 'Device Connected and Power Off Successfully',

                );
            }else{
                $this->dispatch('swal:error',
                    title : 'error',
                    message : 'Connection failed please check the ip address and ttl status'
                );
            }
        }else{
            $this->dispatch('swal:error');

            return [
                'success' => false,
                'message' => 'There is no registered active biometrics connection in the system'
            ];
        }
    }

    // Working
    public function deviceRestart(){

         if($this->fetch_active_location){

            $fetch_test_restart_response = DeviceHelper::device_restart($this->fetch_active_location->ip);

            if($fetch_test_restart_response['success'] == true){
                $this->dispatch('swal:success',
                    title : 'success',
                    message : 'Device Connected and Restart Successfully',

                );
            }else{
                $this->dispatch('swal:error',
                    title : 'error',
                    message : 'Connection failed please check the ip address and ttl status'
                );
            }
        }else{
            $this->dispatch('swal:error');

            return [
                'success' => false,
                'message' => 'There is no registered active biometrics connection in the system'
            ];
        }


    }

    // Working
    public function deviceSerialNumber(){
        if($this->fetch_active_location){
            $fetch_serial_number_response = DeviceHelper::device_serial_number($this->fetch_active_location->ip);

            if($fetch_serial_number_response['success'] == true){
                $this->dispatch('swal:success',
                    title : 'success',
                    header : $fetch_serial_number_response['serial_number'],
                    message : $fetch_serial_number_response['message'],

                );
            }else{
                $this->dispatch('swal:error',
                    title : 'error',
                    message : 'Connection failed please check the ip address and ttl status'
                );
            }

        }else{

            $this->dispatch('swal:error',
                title : 'error',
                message : 'There is no registered active biometrics connection in the system'
            );

        }

        return DeviceHelper::device_serial_number($this->fetch_active_location->ip);
    }

    // Working
    public function deviceOsVersion(){

        if($this->fetch_active_location){
            $fetch_os_version_response = DeviceHelper::device_os_version($this->fetch_active_location->ip);

            if($fetch_os_version_response['success'] == true){
                $this->dispatch('swal:success',
                    title : 'success',
                    header : $fetch_os_version_response['os_version'],
                    message : $fetch_os_version_response['message'],

                );
            }else{
                $this->dispatch('swal:error',
                    title : 'error',
                    message : 'Connection failed please check the ip address and ttl status'
                );
            }

        }else{

            $this->dispatch('swal:error',
                title : 'error',
                message : 'There is no registered active biometrics connection in the system'
            );

        }

    }

    // Working
    public function deviceName(){

        if($this->fetch_active_location){
            $fetch_device_name_response = DeviceHelper::device_name($this->fetch_active_location->ip);

            if($fetch_device_name_response['success'] == true){
                $this->dispatch('swal:success',
                    title : 'success',
                    header : $fetch_device_name_response['device_name'],
                    message : $fetch_device_name_response['message'],

                );
            }else{
                $this->dispatch('swal:error',
                    title : 'error',
                    message : 'Connection failed please check the ip address and ttl status'
                );
            }

        }else{

            $this->dispatch('swal:error',
                title : 'error',
                message : 'There is no registered active biometrics connection in the system'
            );

        }
    }

    public function deviceUser(){
        dd(DeviceHelper::device_user($this->fetch_active_location->ip)->list_user);
    }

    public function deviceClearAttendance(){
        dd(DeviceHelper::clear_device_attendance($this->fetch_active_location->ip));
    }

}
