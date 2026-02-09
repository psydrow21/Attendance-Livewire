<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

use App\Models\Attendance;

class ImportZktecoFile extends Component
{
    use WithFileUploads;

    public $zkteco_file;
    public $rows = [];
    public $insertData = [];


    public $progress = 0;
    public $totalLines = 0;
    public $processed = 0;

    // Required headers
    protected $requiredHeaders = [
        'department',
        'name',
        'no.',
        'date/time',
        'status'
    ];

    public function importzkteco(){

        $this->validate([
            'zkteco_file' => 'required|file|max:51200'
        ]);


        $batch = [];

        // ✅ Livewire temporary uploaded file
        $path = $this->zkteco_file->getRealPath(); // This should work if you have WithFileUploads trait

        $this->rows = Excel::toArray([], $path)[0];

        $headers = array_map(fn($h) => strtolower(trim($h)), $this->rows[0]);

          // 🔐 HEADER VALIDATION
        $missing = array_diff($this->requiredHeaders, $headers);

        if(count($missing) > 0){
            $this->addError('file', 'Invalid Excel headers. Missing: ' . implode(', ', $missing));
            dd('Invalid Format');
            return;
        }

        // Remove header row (optional)
        unset($this->rows[0]);

        foreach($this->rows as $row){
            $datetime = floatval($row[3]);
            $formatted = Date::excelToDateTimeObject($datetime)->format('Y-m-d H:i:s');

            $type = match ($row[4]) {
                'C/In' => '0',
                'C/Out', 'Out Back' => '1',
                default => $row[4],
            };

            $insertData[] = [
                'bio_location_id' => 1,
                'location_name' => 'La Union',
                'serial_number' => '~SerialNumber=ZKM6245100258',
                'emp_id' => $row[2],
                'type' => $type,
                'logs' => $formatted,
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d h:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d h:i:s'),
            ];
        }


        $existing = Attendance::whereIn('emp_id', collect($insertData)->pluck('emp_id'))
        ->whereIn('logs', collect($insertData)->pluck('logs'))
        ->get()
        ->map(fn ($item) => $item->emp_id . '|' . $item->logs)
        ->toArray();

        $insertData = collect($insertData)
        ->reject(function ($item) use ($existing) {
            return in_array($item['emp_id'] . '|' . $item['logs'], $existing);
        })
        ->unique(fn ($item) => $item['emp_id'] . '|' . $item['logs']) // removes duplicates inside file
        ->values()
        ->toArray();


        Attendance::insert($insertData);

        dump('Imported Successfully');
        dd($insertData);

    }

    public function render()
    {
        return view('livewire.import-zkteco-file');
    }
}
