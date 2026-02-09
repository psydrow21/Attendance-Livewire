<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Builder;

class RawAttendanceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithMapping
{

    protected Builder $query;
    public $selected_id;

    public function __construct(Builder $query, $selected_id)
    {
        $this->query = $query;
        $this->selected_id = $selected_id;
    }

    public function collection()
    {
        return $this->query->whereIn('id', $this->selected_id)->get();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first Row as Bold Text
            1   => ['font' => ['bold' => true]]
        ];
    }

    public function headings(): array{
        return [
            'ID',
            'Location Name',
            'Serial Number',
            'Employee Id',
            'Employee Name',
            'Type',
            'Logs',
            'Status',
            'API Checker',
            'Created At',
            'Updated At'
        ];
    }

    /* Modified Columns */
    public function map($row): array
    {

        $display_full_name = NULL;
        if($row->oms_employee || $row->oms_users){
            if($row->oms_employee){
                $fetch_fullname = $row->oms_employee->last_name . ',' . $row->oms_employee->first_name . ' ' . $row->oms_employee->middle_name;
                $display_fullname = $fetch_fullname ?? '';

            }elseif($row->oms_users){

                $fetch_fullname = $row->oms_users->last_name . ',' . $row->oms_users->first_name . ' ' . $row->oms_users->middle_name;
                $display_fullname = $fetch_fullname ?? '';
            }
        }

        return [
            $row->id,
            strtoupper($row->location_name),
            $row->serial_number,
            $row->emp_id,
            $display_fullname != NULL ? $display_fullname : '',
            in_array($row->type, [0,3]) ? 'IN' : (in_array($row->type, [1,2]) ? 'OUT' : 'Error'),
            $row->logs,
            $row->status,
            $row->api_checker,
            $row->created_at,
            $row->updated_at
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */

}
