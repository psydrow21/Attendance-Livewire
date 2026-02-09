<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;

use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

use App\Models\BioLocation;

class LocationSyncMonitoring extends DataTableComponent
{
    protected $model = BioLocation::class;

    public $date_log_filter = '2025-12-15';

    public function builder(): Builder{
        return BioLocation::query()->with('attendance');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Logs From')
                ->filter(function ($builder, string $value){
                    $this->date_log_filter = $value;
                }),

            SelectFilter::make('Location')
                ->options(
                    ['' => 'All Location'] +
                    BioLocation::pluck('location', 'location')->toArray()
                )
                ->filter(function (Builder $builder, string $value){
                    $builder->where('location', $value);
                }),

            SelectFilter::make('Serial Number')
                ->options(
                    ['' => 'All Serial Number'] +
                    BioLocation::pluck('serial_number', 'serial_number')->toArray()
                )
                ->filter(function (Builder $builder, string $value){
                    $builder->where('serial_number', $value);
                }),

            SelectFilter::make('Logs Sync')
                ->options([
                    '' => 'All Status',
                    'sync' => 'Sync',
                    'not_sync' => 'Not Sync'
                ])
                ->filter(function(Builder $builder, string $value){

                    if($value === 'sync'){
                        $builder->whereHas('attendance_specific_day', function($q){
                            $q->whereDate('logs', $this->date_log_filter);
                        });
                    }

                    if($value === 'not_sync'){
                        $builder->whereDoesntHave('attendance_specific_day', function($q){
                            $q->whereDate('logs', $this->date_log_filter);
                        });
                    }
                })

        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Location", "location")
                ->sortable(),
            Column::make("Serial Number", "serial_number")
                ->sortable(),

            Column::make("Status", "status")
                ->format(fn ($value,$row, Column $column) =>
                    $value == 1
                    ? '<span class="text-white rounded-2xl bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">Active</span>'
                    : '<span class="text-white rounded-2xl bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">Inactive</span>'
                )
                ->html()
                ->sortable(),

            Column::make('Logs', 'location')
                ->format(function ($value, $row) {
                    $attendance = $row
                    ->attendance_specific_day($this->date_log_filter)
                    ->whereDate('logs', $this->date_log_filter)
                    ->latest('logs')
                    ->count();

                    $display_attendance = NULL;

                    if($attendance > 0){
                        $display_attendance = '<span class="text-white rounded-2xl bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">Updated Sync</span>';
                    }else{
                        $display_attendance = '<span class="text-white rounded-2xl bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">Not Sync</span>';
                    }

                    return $display_attendance;
                })
                ->html(),

            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
