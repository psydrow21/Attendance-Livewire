<?php

namespace App\Livewire;

use Livewire\Component;

class ExportPayrollFormat extends Component
{
    public function render()
    {
        return view('livewire.export-payroll-format');
    }

    public function exportpayroll(){

        $filePath = public_path('importtext/1-La Union.txt');

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, '1-LaUnion.txt');

    }
}
