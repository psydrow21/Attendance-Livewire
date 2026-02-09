<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ZktecoFetchingController extends Controller
{

    public function multipayrollfilter(Request $request){
        $fromDate = $request->fd;
        $toDate =  $request->td;
        $sourceFiles = [];

        $randomvar = mt_rand(111111, 999999).date('YmdHms');
        ob_start();

        $allfilename = 'all-'. $randomvar;

        if($request->id){

            if(count($request->id) > 0){

                $zipchecker = 'importtext/' . Auth::user()->id . ".zip";
                if(file_exists($zipchecker)){
                    unlink('importtext/' . Auth::user()->id . ".zip");
                }

                foreach($request->id as $location){

                    $formatter = str_replace([" ", "/"], "-", $location);
                    //If the location is not null
                    if($locationformat){

                        $filechecker = 'importtext/' . $formatter . ".txt";

                        //If the path is existed the file will be delete
                        if(file_exists($filechecker)){
                            unlink('importtext/' . $formatter . ".txt");
                        }
                        //If the logs is not empty
                        if(count($query) > 0){
                            //Create or Recreate the file
                            $myfile = fopen($filechecker, "w") or die("Unable to open file!");
                            //Foreach to fetch all data into the excel file
                            foreach($query as $importtxt){
                                //If else to identify if the data is In or Out or Overtime in or Overtime out
                                if($importtxt->type == '0' || $importtxt->type == '3')
                                { $typetxt = 'IN';
                                }else if($importtxt->type=='1' || $importtxt->type=='2')
                                { $typetxt = 'OUT';
                                }elseif($importtxt->type=='4')
                                {$typetxt =  'Overtime In';
                                }elseif($importtxt->type=='5')
                                {$typetxt = 'Overtime Out';
                                }

                                $hourformat = date('H', strtotime($importtxt->logs));
                                if($hourformat < 10) {
                                    $formattime = substr(date("H:i:00", strtotime($importtxt->logs)),1);
                                }
                                else {
                                    $formattime = date("H:i:00", strtotime($importtxt->logs));
                                }

                                $datelogs = ''. date("m/d/Y", strtotime($importtxt->logs)) .'';

                                if($districtserial = DB::table('tbl_bioloc_list')->where('serialno', $importtxt->serial_no)->first()){
                                if($districtcode = DB::table('tbl_branch_list')->where('branch_code', $districtserial->branch_code)->first()){
                                    if($districtcode->district_code == '1' || $districtcode->district_code == '2' || $districtcode->district_code == '5' || $districtcode->district_code == '6'){

                                        $formattime = date("H:i:00", strtotime($importtxt->logs));
                                        //To format like an excel file. (\t) is stand for tab and (\n) stand for next line.
                                        $attendance = $importtxt->empid."\t".
                                        $typetxt ."\t  " .
                                        $datelogs ."\t".
                                        $formattime ."\r\n";
                                        //fwrite is to save the data in txt file.
                                        fwrite($myfile, $attendance);
                                    }else{
                                        //To format like an excel file. (\t) is stand for tab and (\n) stand for next line.
                                        $attendance = $importtxt->empid."\t".
                                        $typetxt ."\t" .
                                        $datelogs ."\t".
                                        $formattime ."\r\n";
                                        //fwrite is to save the data in txt file.
                                        fwrite($myfile, $attendance);
                                    }
                                }

                                }else{
                                    $formattime = date("H:i:00", strtotime($importtxt->logs));

                                        //To format like an excel file. (\t) is stand for tab and (\n) stand for next line.
                                        $attendance = $importtxt->empid."\t".
                                        $typetxt ."\t" .
                                        date("m/d/Y", strtotime($importtxt->logs)) ."\t".
                                        $formattime ."\r\n";
                                        //fwrite is to save the data in txt file.
                                        fwrite($myfile, $attendance);
                                }


                            }
                            //Closer of the file and save.
                            fclose($myfile);
                        }
                    }

                    $filechecker = 'importtext/' . $formatter . ".txt";

                    //Added File of single txt file



                    //Until HERE...


                    if(file_exists($filechecker)){
                        $sourceFiles[] = $filechecker;

                        $zip_file = 'importtext/' . Auth::user()->id .'-'. $randomvar .'.zip'; // Name of our archive to download
                        // Initializing PHP class
                        $zip = new \ZipArchive();
                        $zip->open($zip_file, \ZipArchive::CREATE);

                        $invoice_file = 'importtext/' . $formatter . ".txt";




                        // Adding file: second parameter is what will the path inside of the archive
                        // So it will create another folder called "storage/" inside ZIP, and put the file there.
                        $zip->addFile($invoice_file, $invoice_file);
                        $zip->close();
                    }
                }


                    // all branches import into one text file
                            //Added File of single txt file
                                // Destination file path
                                $destinationFile = 'importtext/'. Auth::user()->id .'-allbranches-'. $randomvar .'.txt';


                                $myfile = fopen($destinationFile, "w") or die("Unable to open file!");
                                $attendance = "\r\n";
                                fwrite($myfile, $attendance);
                                fclose($myfile);

                                // Open the destination file for writing (creates a new file if it doesn't exist)
                                    $destinationHandle = fopen($destinationFile, 'w');


                                    // Loop through each source file
                                    foreach ($sourceFiles as $sourceFile) {

                                                            // Open the source file for reading
                                                            $sourceHandle = fopen($sourceFile, 'r');

                                                            // Read the content of the source file
                                                            $content = fread($sourceHandle, filesize($sourceFile));

                                                            // Append the content to the destination file
                                                            fwrite($destinationHandle, $content);

                                                            // Close the source file handle
                                                            fclose($sourceHandle);



                                    }

                                    // Close the destination file handle
                                    fclose($destinationHandle);

                                    $zip_file2 = 'importtext/' . Auth::user()->id .'-'. $randomvar .'.zip'; // Name of our archive to download

                                    // Initializing PHP class
                                    $zip2 = new \ZipArchive();
                                    $zip2->open($zip_file2, \ZipArchive::CREATE);

                                    $invoice_file2 = $destinationFile;
                                    $zip2->addEmptyDir('singletext');

                                    $newpathsavingfolder = str_replace('importtext/', 'singletext/', $invoice_file2);
                                    // Adding file: second parameter is what will the path inside of the archive
                                    // So it will create another folder called "storage/" inside ZIP, and put the file there.
                                    $zip2->addFile($invoice_file2 , $newpathsavingfolder);
                                    $zip2->close();
                            // Until Here.....
                        // All branches in one text file ends HERE

                ob_end_flush();

                return response()->json(['Message' => 'https://www.acs.multi-linegroupofcompanies.com/'. $zip_file],200);
            }

        }
    }
}
