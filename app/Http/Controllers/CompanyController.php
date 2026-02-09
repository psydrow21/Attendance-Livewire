<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Models\Company;
use App\Models\TtlOptions;
use App\Models\BiometricsModel;

class CompanyController extends Controller
{
    //

    public function insert_default_company(){
        // 1st Idea of insertion
        // $companies = array(
        //     "Filipinas Multi-Line Corporation",
        //     "EverFirst",
        //     "Multi-Line Building Incorporation",
        //     "Multi-Line System Corporation",
        //     "WorldCraft"
        // );

        // DB::beginTransaction();

        // foreach($companies as $ckey => $cval){
        //     Company::create(['company_name' => $cval]);
        // }

        // DB::commit();

        // DB::beginTransaction(function(){
        //     collect([
        //         "Filipinas Multi-Line Corporation",
        //         "EverFirst",
        //         "Multi-Line Building Incorporation",
        //         "Multi-Line System Corporation",
        //         "WorldCraft"
        //     ])->each(fn($name) => Company::create(['company_name' => $name]));
        // });

        // Maximazing the Laravel Functions using the DB Transaction and Collect with advance foreach
        DB::transaction(function () {
            collect([
                ['name' => "Filipinas Multi-Line Corporation", 'acronym' => 'FMLC'],
                ['name' => "EverFirst", 'acronym' => 'EF'],
                ['name' => "Multi-Line Building Incorporation", 'acronym' => 'MBI'],
                ['name' => "Multi-Line System Corporation", 'acronym' => 'MSC'],
                ['name' => "WorldCraft", 'acronym' => 'WC']
            ])->each(function($item) {
                // Validate Company
                $validate_company = Company::where('company_name', $item['name'])->exists();

                if(!$validate_company){
                    Company::create(['company_name' => $item['name'], 'acronym' => $item['acronym']]);
                    dump("Inserted: " . $item['name'] . '<br> Acronym :' . $item['acronym']);
                }

            });
        });

        DB::transaction(function(){
            collect([
                ['ttl_respond' => "64", 'system_action' => "AUTOMATIC", 'status' => '1'],
                ['ttl_respond' => "255", 'system_action' => "MANUAL", 'status' => '1']
            ])->each(function($item){
                // Validate Ttl Options
                $validate_ttl = TtlOptions::where('ttl_respond', $item['ttl_respond'])->exists();

                if(!$validate_ttl){
                    TtlOptions::create(['ttl_respond' => $item['ttl_respond'], 'system_action' => $item['system_action'], 'status' => $item['status']]);
                    dump("Inserted: " . $item['ttl_respond'] . '<br> System Action :' . $item['system_action'] . '<br> Status: ' . $item['status']);
                }

            });
        });

        DB::transaction(function(){
            collect([
                ['biometrics_model' => 'B3C', 'status' => '1'],
                ['biometrics_model' => 'K14', 'status' => '1']
            ])->each(function($item){
                // Validate Biometrics Model
                $validate_biometrics_model = BiometricsModel::where('biometrics_model', $item['biometrics_model'])->exists();

                if(!$validate_biometrics_model){
                    BiometricsModel::create(['biometrics_model' => $item['biometrics_model'], 'status' => $item['status']]);
                    dump("Inserted: ".$item['biometrics_model']. '<br> Status :' . $item['status']);
                }
            });
        });

        $display_company = Company::all();
        $display_ttl = TtlOptions::all();
        $display_biometricsmodel = BiometricsModel::all();

        dump($display_company);
        dump($display_ttl);
        dump($display_biometricsmodel);

    }
}
