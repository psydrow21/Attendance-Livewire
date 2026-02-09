<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\BioLocation;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Project For ATTENDANCE FMC
// Sync Location FMC
Route::post('/sync_location_fmc', function(Request $request){

    // Validate the location of serial number and location if existed
    $validate_bioloc = BioLocation::where('location', $request->location)->where('serial_number', trim($request->serial_number))->exists();

    $update_old_bio = BioLocation::where('location', $request->location)->where('status', 1)->update(['status' => 0]);

    if(!$validate_bioloc){

        $insert_bioloc = BioLocation::create([
            'location' => $request->location,
            'serial_number' => trim($request->serial_number),
            'ip' => $request->ip,
            'ttl_option' => $request->ttl_option,
            'biometrics_model' => $request->biometrics_model,
            'status' => $request->status,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at
            ]);

        $new_id_location = BioLocation::where('location', $request->location)->where('status', 1)->orderByDesc('id')->first()->id;

        // Successfully inserted
        return ['new_id' => $new_id_location ,'message' => 'The Location is updated in the database', 'statusCode' => 200];

    }else{

        $update_query = BioLocation::where('location', $request->location)->where('serial_number', trim($request->serial_number))->update(['status' => 1, 'updated_at' => Carbon::now()]);
        $new_id_location = BioLocation::where('location', $request->location)->where('status', 1)->orderByDesc('id')->first()->id;

        // The Location is existed in the database
       return ['new_id' => $new_id_location, 'message' => 'The Location and location is still the same', 'statusCode' => 404];
    }

});

// Sync Attendance Fmc
Route::post('/sync_attendance_fmc', function(Request $request){


    $payload = $request->all();

    // Ensure bulk array payload
    if (!is_array($payload) || empty($payload)) {
        return response()->json(['error' => 'Invalid payload'], 400);
    }

    DB::beginTransaction();

    try {
        $inserted = DB::table('tbl_attendance_fmc_list')
            ->insertOrIgnore($payload);

        DB::commit();

        return response()->json([
            'success'  => true,
            'inserted' => $inserted, // actual inserted rows
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }

    // DB::table('tbl_attendance_fmc_list')->insert($request->all());

    // return $request->all();

});
