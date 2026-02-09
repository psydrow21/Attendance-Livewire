<?php

namespace App\Livewire;

use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Models\Attendance;
use App\Models\oms_employee;
use App\Models\oms_users;

use App\Exports\SimplifiedAttendanceExport;

use DB;

class SimplifiedAttendanceTable extends Component
{

    public $attendance;

    public $date_from;
    public $date_to;
    public $period = [];
    public $day = [];
    public $weekends_class;

    public $employees = [];
    public $selected_employees = [];


    public $att_per_employee = [];

    private $startofday = '00:00:00';
    private $first_in = '08:00:00';
    private $first_out = '11:59:59';
    private $last_in = '13:00:00';
    private $last_in_plus_two = '15:00:00';
    private $last_out = '17:15:00';
    private $end_of_day = '23:59:59';


    // OMS Connection
    public $oms_employees = [];

    protected $rules = [
        'selected_employees' => 'required|array|min:1',
    ];


    private function fetchattendance($datefrom, $dateto)
    {
        return Attendance::whereIN(DB::raw('DATE_FORMAT(logs,"%Y-%m-%d")'), [$datefrom , $dateto])->orderBy('emp_id');
    }

    /**
     * ----------------------------------------------------
     *  UTILITY FUNCTION: Build Date Period Array
     * ----------------------------------------------------
    */
    private function buildPeriod()
    {
        // $this->date_from = '2025-09-01';
        // $this->date_to = '2025-09-30';

        $from = Carbon::parse($this->date_from)->format('Y-m-d');
        $to = Carbon::parse($this->date_to)->format('Y-m-d');

        foreach(CarbonPeriod::create($from,$to) as $d)
        {
            $this->period[] = $d->format('Y-m-d');
            $this->day[] = $d->englishDayOfWeek;
        }

    }

    // For In Attendance logs and place it into an array
    private function buildLogsByDateIN($logs, $from, $to){
        return collect($logs)
                ->filter(fn ($item) =>
                    Carbon::parse($item['logs'])->between(
                        Carbon::parse($from),
                        Carbon::parse($to)
                    )
                )
                ->whereIN('type', [0,3])
                ->sortBy('logs')
                ->values();
    }

    // For Out Attendance logs and place it into an array
    private function buildLogsByDateOUT($logs, $from, $to){
        return collect($logs)
                ->filter(fn ($item) =>
                    Carbon::parse($item['logs'])->between(
                        Carbon::parse($from),
                        Carbon::parse($to)
                    )
                )
                ->whereIN('type', [1,2])
                ->sortBy('logs')
                ->values();
    }

    // For Late Attendance logs and place it into 1 value
    private function buildLateByIn($latefrom, $lateto){

        $from = Carbon::parse($latefrom);
        $to   = Carbon::parse($lateto);

        $diff = $from->diff($to);

        return $diff->format('%h hours %i minutes');
    }

    // For UnderTime Attendance logs and place it into 1 value
    private function buildUndertimeByOut($undertimefrom, $undertimeto){
        $from = Carbon::parse($undertimefrom);
        $to = Carbon::parse($undertimeto);

        $diff = $from->diff($to);

        return $diff->format('%h hours %i minutes');
    }

    public function filterEmployeeDate(){

        // $this->att_per_employee = [];
        $this->reset(['att_per_employee']);
        $this->reset(['day']);
        $this->reset(['period']);

        // dump($this->date_from);
        // dump($this->date_to);
        // dump($this->selected_employees);
        // dump( collect($this->selected_employees));

         // Build the period based on the filter
        $this->buildPeriod();

        $employee_attendance = Attendance::select('emp_id', 'logs', 'type',
        DB::raw('DATE_FORMAT(logs, "%Y-%m-%d") as newdate'),
        DB::raw('DATE_FORMAT(logs, "%H:%i:%s") as newtime'))
        // ->whereIN('emp_id', collect($this->selected_employees)->pluck('emp_id'))
        ->whereIN('emp_id', collect($this->selected_employees))
        ->whereBetween(DB::raw('DATE_FORMAT(logs, "%Y-%m-%d")'), [$this->date_from,$this->date_to])->orderBy('emp_id');

        $grouped = $employee_attendance->get()->groupBy(function($item){
            return $item->emp_id . '||' . $item->newdate;
        });

        // dd($employee_attendance->get());

        $allemployees = $this->selected_employees;

        foreach($allemployees as $employee){
            foreach($this->period as $key_per => $per){
                // $key = $employee['emp_id'] . '||' . $per;
                $key = $employee . '||' . $per;
                $logs = $grouped->get($key, collect([]));

                if($logs->isEmpty()){
                    $this->att_per_employee[] = array(
                        'period' => $per,
                        'day' => $this->day[$key_per],
                        'morning_in' => '-',
                        'morning_out' => '-',
                        'lunch_in' => '-',
                        'evening_out' => '-',
                        'undertime' => '-',
                        'late' => '-',
                        // 'emp_id' => $employee['emp_id']
                        'emp_id' => $employee
                    );
                }else{

                    $collect_logs = collect($logs)->sortBy('logs') ?? '';

                    //  For Fetching of Morning In
                    $morning_in = NULL;
                    $firstInDisplay = NULL;

                    // Fetch all Time in at 00:00:00 to 11:59:59
                    $morning_fromdate = $per . ' ' . $this->startofday;
                    $morning_todate = $per . ' ' . $this->first_out;

                    // Filter all attendance from the Time in Morning only
                    $newlogs = $this->buildLogsByDateIN($collect_logs, $morning_fromdate, $morning_todate);

                    // Display the first logs of that day
                    $morning_in = $newlogs->first()->logs ?? '';

                    // Format the Attendance into readable Time
                    if($morning_in != '' && $morning_in != NULL){
                        $firstInDisplay = Carbon::parse($morning_in)->format('h:i a');
                    }else{
                        $firstInDisplay = '-';
                    }

                    /* Start of the Lunch Out */
                    // For Fetching of Lunch Out
                    $lunch_out = NULL;
                    $firstOutDisplay = NULL;

                    // Fetch all Time out at 12:00:00 to 13:00:00
                    $lunch_fromdateout = $per . ' ' . $this->first_out;
                    $lunch_todateout = $per . ' ' . $this->last_in;

                    $lunch_out_logs = $this->buildLogsByDateOUT($collect_logs, $lunch_fromdateout, $lunch_todateout);

                    $lunch_out = $lunch_out_logs->first()->logs ?? '';

                    // Format the Attendance into readable Time
                    if($lunch_out != '' && $lunch_out != NULL){
                        $firstOutDisplay = Carbon::parse($lunch_out)->format('h:i a');
                    }else{
                        $firstOutDisplay = '-';
                    }

                    /* Start of the Lunch In */
                    // For Fetching of Lunch IN
                    $lunch_in = NULL;
                    $lunchInDisplay = NULL;

                    // Fetch all Time in at 12:00:00 to 15:00:00
                    $lunch_fromdatein = $per . ' ' . $this->first_out;
                    $lunch_todatein = $per . ' ' . $this->last_in_plus_two;

                    $lunch_in_logs = $this->buildLogsByDateIn($collect_logs, $lunch_fromdatein, $lunch_todatein);

                    $lunch_in = $lunch_in_logs->first()->logs ?? '';

                    // Format the Attendance into readable Time
                    if($lunch_in != '' && $lunch_in != NULL){
                        $lunchInDisplay = Carbon::parse($lunch_in)->format('h:i a');
                    }else{
                        $lunchInDisplay = '-';
                    }

                    /* Start of the Last Out */
                    // For Fetching of Last Out
                    $evening_out = NULL;
                    $eveningOutDisplay = NULL;

                    // Fetch all Time out at 13:00:00 at 23:59:59
                    $evening_fromdateout = $per . ' ' . $this->last_in;
                    $evening_todateout = $per . ' ' . $this->end_of_day;

                    // Format The Attendance readable
                    $evening_out_logs = $this->buildLogsByDateOut($collect_logs, $evening_fromdateout, $evening_todateout);

                    $evening_out = $evening_out_logs->last()->logs ?? '';

                    // Format the Attendance into readable
                    if($evening_out != '' && $evening_out != NULL){
                        $eveningOutDisplay = Carbon::parse($evening_out)->format('h:i a');
                    }else{
                        $eveningOutDisplay = '-';
                    }

                    /* Start of the Late for the day */
                    // Based on First In
                    $late_in = NULL;
                    $lateOutDisplay = NULL;

                    // Fetch all Time in at 08:01:00 to 12:00:00
                    $late_from_datein = $per . ' ' . $this->first_in;
                    $late_to_datein = $per . ' ' . $this->first_out;

                    // Fetch the attendance from that time onwards
                    $late_in_logs = $this->buildLogsByDateIN($collect_logs, $late_from_datein, $late_to_datein);

                    $late_in = $late_in_logs->first()->logs ?? '';

                    // Format the Attendance into readable
                    if($late_in != '' && $late_in != NULL){
                        $fetch_late = Carbon::parse($late_in)->format('h:i a');

                        $calculate_late = $this->buildLateByIn($late_from_datein, $fetch_late);

                        $lateOutDisplay = $calculate_late;
                    }else{
                        $lateOutDisplay = '-';
                    }

                    /* Start of the Undertime for the day */
                    // Based on Last Out
                    $undertime_out = NULL;
                    $undertimeDisplay = NULL;

                    // Fetch all Time Out at 13:00:00 to 17:15:00
                    $undertime_from_dateout = $per . ' ' . $this->last_in;
                    $undertime_to_dateout = $per . ' ' . $this->last_out;

                    $undertime_out_logs = $this->buildLogsByDateOUT($collect_logs, $undertime_from_dateout, $undertime_to_dateout);

                    $undertime_out = $undertime_out_logs->last()->logs ?? '';

                    // Format the Attendance into readable
                    if($undertime_out != '' && $undertime_out != NULL){
                        $fetch_undertime = Carbon::parse($undertime_out);

                        $calculate_undertime = $this->buildUndertimeByOut($fetch_undertime, $undertime_to_dateout);

                        $undertimeDisplay = $calculate_undertime;
                    }else{
                        $undertimeDisplay = '-';
                    }

                    $this->att_per_employee[] = array(
                        'period' => $per,
                        'day' => $this->day[$key_per],
                        'morning_in' => $firstInDisplay,
                        'morning_out' => $firstOutDisplay,
                        'lunch_in' => $lunchInDisplay,
                        'evening_out' => $eveningOutDisplay,
                        'undertime' => $undertimeDisplay,
                        'late' => $lateOutDisplay,
                        // 'emp_id' => $employee['emp_id']
                        'emp_id' => $employee
                    );
                }
            }
        }
    }

    public function exportSimplifiedAttendance($emp_id, $emp_name, $logs){


        return Excel::download(
            new SimplifiedAttendanceExport($emp_id,$emp_name,$logs),
            'Simplified-Attendance.xlsx'
        );
    }

    public function mount()
    {

        // New
        $display_full_name = NULL;
        $display_empid = NULL;

        $sql_oms_employee = oms_employee::select('emp_id', 'last_name','first_name', 'middle_name')->where('company', '!=', 'EverFirst');

        $sql_oms_users = oms_users::select('emp_id', 'last_name', 'first_name', 'middle_name')->where('company_id', 'not like' , '%5%');

        $sql_all_oms = $sql_oms_employee->union($sql_oms_users)->orderBy('emp_id')->get();

        // Readable Format of the query
        // $sql_all_oms = oms_employee::select('emp_id', 'last_name', 'first_name', 'middle_name')
        // ->where('company', '!=', 'EverFirst')
        // ->union(
        //     oms_users::select('emp_id', 'last_name', 'first_name', 'middle_name')
        //         ->where('company_id', 'not like', '%5%')
        // )
        // ->orderBy('emp_id')
        // ->get();

        $this->oms_employees = $sql_all_oms->sortBy('emp_id')->map(fn ($emp) => [
            'emp_id' => $emp->emp_id,
            'name'   => $emp->last_name . ', ' . $emp->first_name,
        ])->toArray();

    }

    public function render()
    {
        return view('livewire.simplified-attendance-table');
    }
}
