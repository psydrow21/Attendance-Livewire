<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BioLocation;
use App\Models\Attendance;
use Carbon\Carbon;

class PhoenixFormatTable extends Component
{

    public $active_location = [];
    public $selected_location = [];
    public $date_from;
    public $date_to;

    public $selected_export;

    public $attendance_formatted_logs = [];

    // On load of the page
    public function mount(){

        $date_today_day = Carbon::now()->format(06);

        if($date_today_day >= 7 && $date_today_day <= 21){
            $this->date_from = Carbon::now()->format('Y-m-07');
            $this->date_to = Carbon::now()->format('Y-m-21');
        }else{
            $this->date_from = Carbon::now()->format('Y-m-22');
            $this->date_to = Carbon::now()->addMonth()->format('Y-m-06');
        }

        $active_location = BioLocation::where('status', 1)->get()->values('id','location');

        $this->active_location = $active_location;

    }

    // Back Button Cutoff Date
    public function subtractMonth(){
        $new_date_from = NULL;
        $new_date_to = NULL;

        // Fetch the Inputed and insert it into carbon to slice the date
        $fetch_from_filter = Carbon::parse($this->date_from);

        // Slice the date into Day,Month,Year
        $get_last_date_day = $fetch_from_filter->format('d');
        $get_last_date_month = $fetch_from_filter->format('m');
        $get_last_date_year = $fetch_from_filter->format('Y');

        // Cutoff Date Condition by the day of the month
        if($get_last_date_day >= 22 || $get_last_date_day <= 6){
            // For Same Month
            $new_date_day_from = '07';
            $new_date_day_to = '21';

            $new_date_from = $get_last_date_year . '-' . $get_last_date_month . '-' . $new_date_day_from;
            $new_carbon_from = Carbon::parse($new_date_from)->format('Y-m-d');

            $new_date_to = $get_last_date_year . '-' . $get_last_date_month . '-' . $new_date_day_to;
            $new_carbon_to = Carbon::parse($new_date_to)->format('Y-m-d');

            // Change the inputed value in the front end
            $this->date_from = $new_carbon_from;
            $this->date_to = $new_carbon_to;

        }
        else // Cutoff Date for Back Month
        {
            $new_date_day_from = '22';
            $new_date_day_to = '06';

            $new_date_from = $get_last_date_year . '-' . $get_last_date_month . '-' . $new_date_day_from;
            $new_carbon_from = Carbon::parse($new_date_from)->subMonth()->format('Y-m-d');

            $new_date_to = $get_last_date_year . '-' . $get_last_date_month . '-' . $new_date_day_to;
            $new_carbon_to = Carbon::parse($new_date_to)->format('Y-m-d');

            // Change the inputed value in the front end
            $this->date_from = $new_carbon_from;
            $this->date_to = $new_carbon_to;
        }
    }

    // Add Month Button Cutoff Date
    public function addMonth(){
        $new_date_from = NULL;
        $new_date_to = NULL;

        // Fetch the Inputed and insert it into carbon to slice the date
        $fetch_to_filter = Carbon::parse($this->date_to);

        // Slice the date into Day,Month,Year
        $get_last_date_day = $fetch_to_filter->format('d');
        $get_last_date_month = $fetch_to_filter->format('m');
        $get_last_date_year = $fetch_to_filter->format('Y');

        // Cutoff Date Condition by the day of the month
        if($get_last_date_day >= 22 || $get_last_date_day <= 6){
            // For Same Month
            $new_date_day_from = '07';
            $new_date_day_to = '21';

            $new_date_from = $get_last_date_year . '-' . $get_last_date_month . '-' . $new_date_day_from;
            $new_carbon_from = Carbon::parse($new_date_from)->format('Y-m-d');

            $new_date_to = $get_last_date_year . '-' . $get_last_date_month . '-' . $new_date_day_to;
            $new_carbon_to = Carbon::parse($new_date_to)->format('Y-m-d');

            // Change the inputed value in the front end
            $this->date_from = $new_carbon_from;
            $this->date_to = $new_carbon_to;

        }
        else // Cutoff Date for Back Month
        {
            $new_date_day_from = '22';
            $new_date_day_to = '06';

            $new_date_from = $get_last_date_year . '-' . $get_last_date_month . '-' . $new_date_day_from;
            $new_carbon_from = Carbon::parse($new_date_from)->format('Y-m-d');

            $new_date_to = $get_last_date_year . '-' . $get_last_date_month . '-' . $new_date_day_to;
            $new_carbon_to = Carbon::parse($new_date_to)->addMonth(1)->format('Y-m-d');

            // Change the inputed value in the front end
            $this->date_from = $new_carbon_from;
            $this->date_to = $new_carbon_to;
        }
    }

    public function filterPayrollFormat(){
        if($this->selected_location){
            $this->attendance_formatted_logs = [];

            $fetch_from_filter_startOfDay = Carbon::parse($this->date_from)->startOfDay();
            $fetch_to_filter_endOfDay = Carbon::parse($this->date_to)->endOfDay();

            $attendance_logs_sql = Attendance::select('bio_location_id', 'location_name', 'emp_id', 'type', 'logs')
            ->whereBetween('logs', [$fetch_from_filter_startOfDay, $fetch_to_filter_endOfDay])
            ->where('location_name', $this->selected_location)
            ->orderBy('emp_id')
            ->get();



            if(count($attendance_logs_sql) > 0){
                foreach($attendance_logs_sql as $att){
                    $type = in_array($att->type,[0,3]) ? 'IN' : 'OUT';

                    $this->attendance_formatted_logs[]= array(
                        'bio_location_id' => $att->bio_location_id,
                        'location_name' => $att->location_name,
                        'emp_id' => $att->emp_id,
                        'type' => $type,
                        'logs' => $att->logs,
                        'datelogs' => Carbon::parse($att->logs)->format('m/d/Y'),
                        'timelogs' => Carbon::parse($att->logs)->format('H:m:s'),
                    );
                }

                $this->dispatch('filter_payroll_format',
                                success : 'true',
                                message : 'Proceed Filter');
            }
        }else{

            $this->dispatch('filter_payroll_format',
                    success : 'false',
                    message : 'Select Location First Before Filter',
            );
        }
    }

    public function exportTextFile(){

        if($this->selected_location){

            $new_file = $this->createTextFile();



            $this->dispatch('download_payroll_format',
                success : 'true',
                message : 'Proceed the download',
                // file : $this->filechecker,
                file : $new_file['filechecker'],
            );

        }else{

            $this->dispatch('download_payroll_format',
                success : 'false',
                message : 'Select Location First Before Filter',
                file : NULL,
            );
        }

    }

    public function createTextFile(){
        $userid = '1';
        $datefrom = $this->date_from;
        $dateto = $this->date_to;
        $location_name = $this->selected_location;

        $file_format = $userid . '-' . $location_name;

        $filechecker = 'importtext/' . $file_format . ".txt";

        //If the path is existed the file will be delete
        if(file_exists($filechecker)){
            unlink('importtext/' . $file_format . ".txt");
        }

        // Get Attendnace
        $query = Attendance::whereBetween('logs', [Carbon::parse($datefrom)->startOfDay(),Carbon::parse($dateto)->endOfDay(),])->orderBy('emp_id', 'asc')->get();

        //If the logs is not empty
        if(count($query) > 0){
            //Create or Recreate the file
            $myfile = fopen($filechecker, "w") or die("Unable to open file!");
            //Foreach to fetch all data into the excel file
            foreach($query as $importtxt){

                //If else to identify if the data is In or Out or Overtime in or Overtime out
                $typetxt = match ($importtxt->type){
                    '0', '3' => 'IN',
                    '1', '2' => 'OUT',
                    '4' => 'Overtime In',
                    '5' => 'Overtime Out',
                    default => 'UNKNOWN',
                };

                $datelogs = date("m/d/Y", strtotime($importtxt->logs));

                $formattime = date("H:i:00", strtotime($importtxt->logs));

                //To format like an excel file. (\t) is stand for tab and (\n) stand for next line.
                $attendance = $importtxt->emp_id."\t".
                $typetxt ."\t" .
                $datelogs ."\t".
                $formattime ."\r\n";

                //fwrite is to save the data in txt file.
                fwrite($myfile, $attendance);

            }
                //Closer of the file and save.
                fclose($myfile);
        }else{

            $attendance = NULL;
            //Create or Recreate the file
            $myfile = fopen($filechecker, "w") or die("Unable to open file!");

            //fwrite is to save the data in txt file.
            fwrite($myfile, $attendance);

            //Closer of the file and save.
            fclose($myfile);
        }


        return [
            'filechecker' => $filechecker
        ];

    }



    // Render the template
    public function render()
    {
        // $active_location = BioLocation::where('status', 1)->get()->values('id','location');

        // return view('livewire.phoenix-format-table', compact('active_location'));

        return view('livewire.phoenix-format-table');
    }
}
