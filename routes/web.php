<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\DeviceActionController;

use App\Http\Controllers\ZktecoFetchingController;
use App\Http\Controllers\AttendanceLogsController;

use App\Http\Controllers\CompanyController;

use App\Helpers\DeviceHelper;
use App\Helpers\AttendanceHelper;

use Illuminate\Support\Facades\Http;

use App\Models\Attendance;
use App\Models\BioLocation;
use App\Models\oms_employee;
use App\Models\oms_users;
use App\Models\User;

use App\Mail\AttendanceMail;

use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('page.dashboard_page');
})->name('dashboard_page');

// Dashboard Page
Route::controller(PagesController::class)->group(function(){
    Route::get('/landing_page', 'landing_page')->name('landing_page');

    Route::get('/dashboard_page', 'dashboard_page')->name('dashboard_page');

    Route::get('/sync_location_page', 'sync_location_page')->name('sync_location_page');

    Route::get('/attendance_display_page', 'attendance_display_page')->name('attendance_display_page');

    Route::get('/biometrics_display_page', 'biometrics_display_page')->name('biometrics_display_page');

    Route::get('/company_display_page', 'company_display_page')->name('company_display_page');

    Route::get('/uploading_display_page', 'uploading_display_page')->name('uploading_display_page');

    Route::get('/phoenix_format_page', 'phoenix_format_page')->name('phoenix_format_page');

    Route::get('/device_action_page', 'device_action_page')->name('device_action_page');

    Route::get('/simplified_attendance_page', 'simplified_attendance_page')->name('simplified_attendance_page');

    Route::get('/biometrics_model_display_page', 'biometrics_model_display_page')->name('biometrics_model_display_page');

    Route::get('/ttl_option_page', 'ttl_option_page')->name('ttl_option_page');

    Route::get('/daily_sync_page', 'daily_sync_page')->name('daily_sync_page');

    Route::get('/skeleton', 'skeleton')->name('skeleton');
});

// Insert default company
Route::controller(CompanyController::class)->group(function(){
    Route::get('/insert_default_company', 'insert_default_company')->name('insert_default_company');
});

// Email Testing
Route::get('/emailtest', function(){
    $adminmail = ['name' => 'mark'];
    Mail::to('psydrow21@gmail.com')->send(new AttendanceMail($adminmail));
});

// Exportation of attendance
Route::get('/generatephoenix', function(){

        $userid = '1';
        $datefrom = '2025-09-01';
        $dateto = '2025-12-17';
        $serial_no = '~SerialNumber=ZKM6245100258';

        $serial_no_sql = BioLocation::where('serial_number', $serial_no)->orderByDesc('id')->first();


        $file_format = $userid . '-' .$serial_no_sql->location;

        ob_start();

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
        }


        $filechecker = 'importtext/' . $file_format . ".txt";

        dd($filechecker);


})->name('generatephoenix');


// Sync the attendance by zkteco Attendance Management. Note: The zkteco attendance management must be close
Route::get('autoimport', function(){
	dd(AttendanceHelper::ManualSyncingAttendance());
});

// Autosyncing of location in the live once per day trigger
Route::get('/sync_location_live', function(){

    $active_location = BioLocation::where('status', 1)->orderByDesc('id')->first();

    DB::beginTransaction();

            //Check if the data checker is null or not transported in the live server
            if($active_location){
                try {

                    //Throw the data in the live server
                    $response  = Http::post('https://www.acs.multi-linegroupofcompanies.com/api/sync_location_fmc',
                    [
                        'location'              => $active_location->location,
                        'serial_number'         => trim($active_location->serial_number),
                        'ip'                    => $active_location->ip,
                        'ttl_option'            => $active_location->ttl_option,
                        'biometrics_model'      => $active_location->biometrics_model,
                        'status'                => $active_location->status,
                        'created_at'            => Carbon::now()->format('Y-m-d h:i:s'),
                        'updated_at'            => Carbon::now()->format('Y-m-d h:i:s'),
                    ]);

                    $fetch_response = json_decode($response->getBody());

                    if (! $response->successful()) {
                        throw new \Exception('API upload failed');
                    }

                    DB::commit();

                } catch (\Throwable $e) {

                    //if the connection has found some error then the inserted data will be reverted.
                    DB::rollback();

                    // Optional: log the error for debugging
                    logger()->error('Attendance upload failed', [
                        'id'    => $active_location->id,
                        'error' => $e->getMessage(),
                    ]);

                }
            }
});

// Autosyncing of attendance in the live server
Route::get('/sync_attendance_live', function(){

    $fetchNotSync = Attendance::whereNull('api_checker')
                    ->orderBy('id')
                    ->chunk(200, function($attendance){

                        $count_sync = 0;
                        $store = [];

                        foreach($attendance as $att)
                        {

                            $store[] = array(
                                'location_name' => $att->location_name,
                                'serial_number' => $att->serial_number,
                                'emp_id' => $att->emp_id,
                                'type' => $att->type,
                                'logs' => $att->logs,
                                'status' => $att->status,
                                'imported_type' => 'Branch Sync',
                                'created_at' => Carbon::parse($att->created_at)->format('Y-m-d h:i:s'),
                                'updated_at' => Carbon::parse($att->updated_at)->format('Y-m-d h:i:s'),
                            );

                            $count_sync++;
                        }

                        if (empty($store)) {
                            return;
                        }


                        $response  = Http::timeout(30)->post('https://www.acs.multi-linegroupofcompanies.com/api/sync_attendance_fmc',$store);

                        // dump(json_decode($response->getBody()));

                        // dump($response->json());

                        // ✅ Mark records as synced ONLY if API succeeded
                        if ($response->successful()) {
                            Attendance::whereIn('id', $attendance->pluck('id'))
                                ->update(['api_checker' => 1]);
                        }
                    });


});

Route::get('/oms_users_pull', function(){

    try {
        $array_value = array();

        $oms_users = oms_users::select('first_name', 'middle_name', 'last_name', 'emp_id', 'email', 'company_id', 'username', 'password')->whereNotIn('emp_id', User::pluck('emp_id'))->get();

        foreach($oms_users as $o_users) {

            $array_value[] = array(
                'first_name' => $o_users->first_name ?? NULL,
                'middle_name' => $o_users->middle_name ?? NULL,
                'last_name' => $o_users->last_name ?? NULL,
                'emp_id' => $o_users->emp_id ?? NULL,
                'email' => $o_users->email ?? NULL,
                'company_id' => $o_users->company_id ?? NULL,
                'username' => $o_users->username ?? NULL,
                'password' => $o_users->password ?? NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:m:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:m:s'),
            );

        }

        dump('All Users from OMS are registered to the Attendance users');
        User::insert($array_value);

        dump('Successfully inserted');
        dd($array_value);

    }catch(\Throwable $th){

        dd($th->getMessage());

    }

});
