<?php

namespace App\Helpers;
use Rats\Zkteco\Lib\Zkteco;

use App\Models\Attendance;
use App\Models\oms_employee;
use App\Models\oms_users;
use App\Models\Biolocation;
use App\Helpers\DeviceHelper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use DB;

class AttendanceHelper
{
    public static function AutomaticSyncingAttendance(){

        try{
            // 🔹 Fetch existing attendance keys
            $existing = Attendance::orderBy('logs')->pluck(DB::raw("CONCAT(emp_id,'|',logs)"))->toArray();

            $existingMap = array_flip($existing); // for fast lookup

            $fetch_active_location = Biolocation::where('status', 1)->orderByDesc('id')->first();

            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($bio_ip)) {
                throw new \Exception('Biometric device is offline');
            }

            if($fetch_active_location){
                // Fetch Attendance from the biometrics
                $fetch_bio_attendance = DeviceHelper::device_attendance($fetch_active_location->location, $fetch_active_location->serial_number, $fetch_active_location->ip);

                // If Connection is success
                if($fetch_bio_attendance['success'] == true){
                    // DB::transaction for safe saving of data in the database
                    DB::transaction(function() use($fetch_active_location, $existingMap, $fetch_bio_attendance){

                        $fetcher = [];

                        collect($fetch_bio_attendance['attendance'])
                        ->chunk(2000)
                        ->each(function($chunk) use($fetch_active_location, $existingMap, &$fetcher){

                            foreach($chunk as $item){

                                $uniqueKey = $item['id'] . '|' . $item['timestamp'];

                                // ❌ Skip if already exists
                                if(isset($existingMap[$uniqueKey])){
                                    continue;
                                }

                                $fetcher[] = array(
                                    'bio_location_id' => $fetch_active_location->id,
                                    'location_name' => $fetch_active_location->location,
                                    'serial_number' => $fetch_active_location->serial_number,
                                    'emp_id' => $item['id'],
                                    'type' => $item['type'],
                                    'logs' => $item['timestamp'],
                                    'status' => $item['state'],
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                );
                            }

                            // Trigger the save in the database
                            if(!empty($fetcher)){
                                Attendance::insert($fetcher);
                            }
                        });

                    });

                    // Successfully saved in the local pc database
                    return [
                        'success' => true,
                        'message' => 'All Attendance is save into the database',
                        'status_code' => 200
                    ];
                }
                else // If Connection is not connected
                {
                    return [
                        'success' => false,
                        'message' => 'The Biometric is not connected in the network or wrong connection in the system',
                        'status_code' => 404
                    ];
                }

            }else{
                // There is no registered ip address and location save in the database
                return [
                    'success' => false,
                    'message' => 'There is no save Biometrics in the system. Sync the IP and biometrics in the system first before fetching the attendance',
                    'status_code' => 404
                ];

            }
        }catch(\Exception $e){
                // There is no registered ip address and location save in the database
                return [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'status_code' => 500
                ];
        }

    }

    // For using Zkteco Attendance Management
    public static function ManualSyncingAttendance(){
        //Locating the .mdb file to access its database
        $dbName = "C:\Program Files (x86)\ZKTeco\att2000.mdb";
        //If the file is not exist the or not found then this error will be show
        if (!file_exists($dbName)) {
            die("Could not find database file.");
        }

        try
        {
        //Connect the to the database using this connection
            $db = odbc_connect("Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$dbName", "", "");
            //If the db is not connected then the error will be show
            if (!$db) {
                exit("Connection failed: " . odbc_errormsg());
            }

            $zkteco_query = "
                SELECT
                    *
                FROM CHECKINOUT c
                INNER JOIN USERINFO u ON c.USERID = u.USERID
            ";

            // Retrieve data from a table
            $result = odbc_exec($db, $zkteco_query);
            //Check if the result return
            if (!$result) {
                exit("Error in SQL query: " . odbc_errormsg());
            }
            //User empty array to store the data using this variable
            $fetchall = [];
            // Print out the data
            while ($row = odbc_fetch_array($result)) {
                //Store the data in variable
                    $fetchall[] = $row;
            }

            /**
             * 🔹 Load existing attendance ONCE (fast lookup)
             */
            $existing = Attendance::pluck(DB::raw("CONCAT(emp_id,'|',logs)"))
                ->flip()
                ->toArray();

            $biolocation = BioLocation::where('status', 1)->orderByDesc('id')->first();



            DB::transaction(function() use($biolocation, $existing, $fetchall){
                $store = [];

                collect($fetchall)
                ->chunk(2000)
                ->each(function($chunk) use($biolocation, $existing, &$store){
                    foreach($chunk as $attrecords){
                        // Intiantiate the attendance record from the zkteco attendance management
                        $checktype= NULL;
                        $serial_no = $attrecords['sn'];
                        $empid = $attrecords['Badgenumber'];
                        $timein = $attrecords['CHECKTIME'];
                        $type = $attrecords['CHECKTYPE'];

                        // Map check type
                        $checktype = match ($type) {
                            'O' => 1,
                            'I' => 0,
                            default => null
                        };

                        if ($checktype === null) {
                            continue;
                        }

                        // Fast existence check
                        $key = $empid . '|' . $timein;

                        if (isset($existing[$key])) {
                            continue;
                        }

                        $store[] = array(
                            'bio_location_id' => $biolocation->id,
                            'location_name' => $biolocation->location,
                            'serial_number' => '~SerialNumber='. $serial_no,
                            'emp_id' => $empid,
                            'type' => $checktype,
                            'logs' => $timein,
                            'status' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        );
                    }

                    Attendance::insertOrIgnore($store);
                });
            });

            return [
                'success' => true,
                'message' => 'The attendance is saved in the database.',
            ];
        }
        catch(PDOException $error_mess)
        {
            return [
                'success' => false,
                'message' => $error_mess->getMessage()
            ];
        }

    }

    // For getting the lates count
    public static function LatesCountPerDay($emp_id, $date_from, $date_to){

        $startofday = '00:00:00';
        $first_in = '08:00:00';
        $first_out = '11:59:59';
        $last_in = '13:00:00';
        $last_in_plus_two = '15:00:00';
        $last_out = '17:15:00';
        $end_of_day = '23:59:59';

        $late_date = array();
        $undertime_out = array();
        $ontime_date = array();
        $allperiod = array();

        // Start for in and lates
        $get_in_attendance = Attendance::where('emp_id', $emp_id)
        ->whereBetween(DB::raw('DATE(logs)'), [$date_from, $date_to])
        ->whereIn('type', [0, 3])
        ->get();

        foreach($get_in_attendance as $in_att){

            $employee_first_time_in = Carbon::parse($in_att->logs)->format('H:m:s');
            $employee_first_date_in = Carbon::parse($in_att->logs)->format('Y-m-d');

            if($in_att){
                if($employee_first_time_in >= $startofday && $employee_first_time_in <= $first_out){
                    if(!in_array($employee_first_date_in, $late_date)){
                        $late_date[] = $employee_first_date_in;
                    }

                }
            }
        }

        // Start for out and undertime
        $get_out_attendance = Attendance::where('emp_id', $emp_id)
        ->whereBetween(DB::raw('DATE(logs)'), [$date_from, $date_to])
        ->whereIn('type', [1, 2])
        ->get();

        foreach($get_out_attendance as $out_att){
            $employee_last_time_out = Carbon::parse($out_att->logs)->format('H:m:s');
            $employee_last_date_out = Carbon::parse($out_att->logs)->format('Y-m-d');

             if($out_att){
                if($employee_last_time_out >= $last_in && $employee_last_time_out <= $end_of_day){
                    if(!in_array($employee_last_date_out, $undertime_out)){
                        $undertime_out[] = $employee_last_date_out;
                    }
                }
            }
        }

        // Start for Ontime
        $build_period = CarbonPeriod::create($date_from, $date_to);

        foreach($build_period as $period){

            $validate_period = $period->format('Y-m-d');

            if($period->format('D') != 'Sat' && $period->format('D') != 'Sun'){
                if(!in_array($validate_period, $late_date) && !in_array($validate_period,$undertime_out)){
                    $allperiod[] = array($validate_period);
                }
            }

        }

        return [
            'emp_id' => $emp_id,
            'late_array' => $late_date,
            'late_count' => count($late_date),
            'under_time_array' => $undertime_out,
            'under_time_count' => count($undertime_out),
            'all_period_array' => $allperiod,
            'all_period_count' => count($allperiod),

        ];
    }
}
