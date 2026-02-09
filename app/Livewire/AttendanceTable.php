<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\BioLocation;

use App\Exports\RawAttendanceExport;

use Illuminate\Database\Eloquent\Builder;

use Maatwebsite\Excel\Facades\Excel;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;



use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;


use Carbon\Carbon;



class AttendanceTable extends DataTableComponent
{



    protected $model = Attendance::class;

    protected bool $allowBulkActions = false;
    public bool $bulkActionsEnabled = false;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setSingleSortingDisabled();

        $this->setBulkActions([
            'exportExcel' => 'Export To Excel'
        ]);

    }

    public function builder(): Builder
    {
        return Attendance::query()
                ->with('oms_employee')
                ->with('oms_users');
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Logs From')
                ->filter(function ($builder, string $value){
                    $builder->whereDate('logs', '>=', $value);
                }),

            DateFilter::make('Logs To')
                ->filter(function (Builder $builder, string $value){
                    $builder->whereDate('logs', '<=', $value);
                }),

            SelectFilter::make('Type')
            ->options([
                '' => 'All',
                'in' => 'IN',
                'out' => 'OUT'
            ])
            ->filter(function ($builder, string $value){
                if($value === 'in'){
                    $builder->whereIn('type', [0,3]);
                }

                if($value === 'out'){
                    $builder->whereIn('type', [1,2]);
                }
            }),

            SelectFilter::make('Location')
            ->options(
                ['' => 'All Location'] +
                Biolocation::pluck('location', 'location')->toArray()
            )
            ->filter(function ($builder, string $value){
                $builder->where('location_name', $value);
            })
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Location Name" , 'location_name')
                ->sortable()
                ->searchable(),
            Column::make("Serial Number", 'serial_number')
                ->sortable(),
            Column::make("Employee Id", 'emp_id')
                ->sortable()
                ->searchable(),
            Column::make("Employee Name", 'emp_id')
                ->format(function ($value,$row, Column $column){

                    if($row->oms_employee || $row->oms_users){

                        if($row->oms_employee){

                            $fetch_fullname = $row->oms_employee->last_name . ',' . $row->oms_employee->first_name . ' ' . $row->oms_employee->middle_name;
                            $display_fullname = $fetch_fullname ?? '';

                            return $display_fullname;
                        }elseif($row->oms_users){

                            $fetch_fullname = $row->oms_users->last_name . ',' . $row->oms_users->first_name . ' ' . $row->oms_users->middle_name;
                            $display_fullname = $fetch_fullname ?? '';

                            return $display_fullname;
                        }
                    }

                    return '';



                })
                ->sortable()
                ->searchable(),
            Column::make('Logs', 'logs')
                ->sortable()
                ->format(function ($value,$row, Column $column){
                    return '<span class="text-white rounded-2xl bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">'. Carbon::parse($value)->format('Y-m-d h:i a') .'</span>';
                })
                ->html(),

            Column::make("Type", 'type')
                ->sortable()
                ->format(function ($value,$row, Column $column) {

                    if(in_array($value, [0,3])){
                        return '<span class="text-white rounded-2xl bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">IN</span>';
                    }else if(in_array($value, [1,2])){
                        return '<span class="text-white rounded-2xl bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">OUT</span>';
                    }else{
                        return 'Error';
                    }
                })
                ->html()
                ->searchable(),

            // For Biolocation
            // Column::make("IP Address", "biolocations.ip")
            //     ->sortable(),
            // Column::make("TTL", "biolocations.ttl_option")
            //     ->sortable(),
            // Column::make("Biometrics Model", "biolocations.biometrics_model")
            //     ->sortable(),

            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }


    protected $listeners = ['export' => 'export'];

    /**
     * EXPORT USING SAME FILTERS
     */
    public function exportExcel()
    {


    return Excel::download(
        new RawAttendanceExport($this->getFilteredQuery(), $this->getSelected()),
        'Raw-Attendance.xlsx'
    );
    }

    /**
     * Get query WITH filters & search applied
     */
    protected function getFilteredQuery(): Builder
    {
        return $this->applyFilters(
            $this->applySearch(
                $this->builder()
            )
        );
    }
}
