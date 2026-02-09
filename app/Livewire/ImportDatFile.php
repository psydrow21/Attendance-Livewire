<?php

namespace App\Livewire;


use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

use App\Models\Attendance;
use App\Models\BioLocation;

class ImportDatFile extends Component
{
    use WithFileUploads;

    public $active_location;

    public $dat_file;
    public $progress = 0;
    public $totalLines = 0;
    public $processed = 0;

    public $attendanceArray = [];

    public function getTotalData(){

        $active_location = BioLocation::with('company')->where('status', 1)->orderByDesc('id')->first();

        $this->validate([
            'dat_file' => 'required|file|max:51200'
        ]);

        $batch = [];

        // ✅ Livewire temporary uploaded file
        $path = $this->dat_file->getRealPath(); // This should work if you have WithFileUploads trait

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $this->totalLines = iterator_count(File::lines($path));

        $chunkSize = 2000;

        Attendance::get();

        foreach (File::lines($path) as $line) {
            if (trim($line) === '') continue;

                $c = preg_split('/\s+/', trim($line));
                if (count($c) < 6) continue;

            $batch[] = [
                'bio_location_id' => $active_location->id,
                'location_name' => 'La Union',
                'serial_number' => '~SerialNumber=ZKM6245100258',
                'type' => $c[4] ?? null,
                'status' => 1,
                'emp_id'   => $c[0],
                'logs'     => $c[1].' '.$c[2],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // 🔹 When chunk is full → insert
            if (count($batch) === $chunkSize) {
                Attendance::insert($batch);
                $this->processed += count($batch);
                $batch = [];

                // 🔹 progress bar
                $this->progress = round(($this->processed / $this->totalLines) * 100, 2);
            }

        }

        // 🔹 Insert remaining rows
        if (!empty($batch)) {
            Attendance::insert($batch);
            $this->processed += count($batch);
        }


        // 🔹 Final progress
        $this->progress = 100;

        dump($batch);
        dump($this->totalLines);
        dd($lines);

    }


    public function render()
    {
        return view('livewire.import-dat-file');
    }
}
