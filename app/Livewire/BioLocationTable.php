<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\BioLocation;

class BioLocationTable extends DataTableComponent
{
    protected $model = BioLocation::class;



    public function configure(): void
    {
        $this->setPrimaryKey('id');
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
            Column::make("IP Address", "ip")
                ->sortable(),
            Column::make("status", "status")
                ->sortable()
                ->format(fn($value, $row, Column $column) =>
                    $value == 1
                    ? '<span class="text-white rounded-2xl bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">Active</span>'
                    : '<span class="text-white rounded-2xl bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">Inactive</span>'
                )
                ->html(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
