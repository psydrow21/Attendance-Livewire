<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\DeviceHelper;

use App\Models\BioLocation;
use App\Models\TtlOptions;
use App\Models\BiometricsModel;

class SyncLocation extends Component
{

    // Fetch and set the text input
    public $status;
    public $serialnumber;
    public $ip = '';
    public $location = '';
    public $ttl = '';
    public $biomodel = '';

    // For Input Options
    public $ttl_option = [];
    public $biomodel_options = [];


    // For Validation
    protected $rules = [
        'ip' => 'required|ip',
        'location' => 'required',
        'ttl' => 'required',
        'biomodel' => 'required'
    ];

    // For Validation Error Message
    protected $messages = [
        'ip.required' => 'IP address is required',
        'ip.ip' => 'Incorrect Format of IP Address',
        'location.required' => 'Location is required',
        'ttl.required' => 'TTL is required',
        'biomodel.required' => 'Biometrics model is required'
    ];

    public function mount(){
        $this->ttl_option = TtlOptions::all();
        $this->biomodel_options = BiometricsModel::all();
    }


    // IP Address checking and fetch the serialnumber and send the details of connection
    public function ipAdressChecking(){

        $fetch_serial_bio = DeviceHelper::device_serial_number($this->ip);
        $this->serialnumber= $fetch_serial_bio['serialnumber'];
        $this->status = $fetch_serial_bio['message'];

    }

    // Save the Location And Serial Number
    public function bioLocationSave(){
        $save_serial = null;
        $validated_bio =  $this->validate();

        if($validated_bio){
            if($this->serialnumber){
                $save_serial = $this->serialnumber;

                    Biolocation::query()->update(['status' => 0]);

                    Biolocation::create([
                        'company_id' => '2',
                        'serial_number' => $save_serial,
                        'location' =>  $validated_bio['location'],
                        'ip' => $validated_bio['ip'],
                        'ttl_option' => $validated_bio['ttl'],
                        'biometrics_model' => $validated_bio['biomodel']
                    ]);

                    $this->dispatchBrowserEvent('swal:success', [
                        'id' => 1,
                        'title' => 'Are you sure?',
                        'text' => 'This action cannot be undone!',
                        'icon' => 'warning',
                    ]);



            }else{
                dump('Cannot connect to the device.');
                dump('Check the Following:');
                dump('1. Wrong IP');
                dump('2. Wrong port (default 4370)');
                dump('3. Device is offline');
                dump('4. Network block by firewall');
            }
        }
    }

    public function bioLocationClearForm(){
        $this->serialnumber = '';
        $this->ip = '';
        $this->location = '';
        $this->ttl = '';
        $this->biomodel = '';
        $this->messages = [];
    }

    public function render()
    {
        return view('livewire.sync-location');
    }


}
