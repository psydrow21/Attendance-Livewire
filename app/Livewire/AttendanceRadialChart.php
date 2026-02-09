<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\oms_employee;
use App\Models\om_users;

use App\Helpers\AttendanceHelper;

use Carbon\Carbon;
use Carbon\CarbonPeriod;


class AttendanceRadialChart extends Component
{
    public $filter_undertime_count;

    public $filter_late_count;
    public $filter_period_late_percentage;
    public $filter_period_undertime_percentage;

    public $filter_period_count;
    public $filter_period_percentage;


    public function mount(){
        $emp_id = '1';
        $filter_from = '2025-12-16';
        $filter_to = '2025-12-20';

        $get_late = AttendanceHelper::LatesCountPerDay($emp_id, $filter_from, $filter_to);

        // Based on filter
        $period = CarbonPeriod::create($filter_from, $filter_to);

        $dayCount = $period->count();

        // Fetch Late
        $filter_late_computation = ($get_late['late_count'] / $dayCount) * 100;
        $this->filter_late_count = intval($get_late['late_count']);
        $this->filter_period_late_percentage = intval($filter_late_computation);

        // Fetch Undertime
        $filter_undertime_computation = ($get_late['under_time_count'] / $dayCount) * 100;
        $this->filter_undertime_count = intval($get_late['under_time_count']);
        $this->filter_period_undertime_percentage = intval($filter_undertime_computation);

        // Fetch the Period Days
        $filter_period_computation = ($get_late['all_period_count'] / $dayCount) * 100;
        $this->filter_period_count = intval($get_late['all_period_count']);
        $this->filter_period_percentage = intval($filter_period_computation);

    }

    public function render()
    {
        return view('livewire.attendance-radial-chart');
    }
}
