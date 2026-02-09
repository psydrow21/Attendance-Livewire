<?php

namespace App\Livewire;

use Livewire\Component;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

// Filter Initiantiate
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;


class UsersTable extends DataTableComponent
{

    // For Fetching Data
    protected $model = User::class;

    // Rendering of displayed column
    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Name', 'name')->sortable()->searchable(),
            Column::make('Email', 'email')->sortable()->searchable(),
            Column::make('Token', 'remember_token')->sortable(),
            Column::make('Created At', 'created_at')->sortable(),
            Column::make('Update At', 'updated_at')->sortable(),
        ];
    }

    // Set The Display Data
    public array $perPageAccepted = [10, 20, 50];

    // This is the configuration of the table
    public function configure(): void
    {
        $this->setPrimaryKey('id');

        // Enable top toolbar
        $this->setConfigurableAreas([
            'toolbar-left-start' => 'livewire.users-table-export',
        ]);

        //   $this->setConfigurableAreas([
        //     'before-tools' => 'path.to.my.view',
        //     'toolbar-left-start' => 'path.to.my.view',
        //     'toolbar-left-end' => 'path.to.my.view',
        //     'toolbar-right-start' => 'path.to.my.view',
        //     'toolbar-right-end' => 'path.to.my.view',
        //     'before-toolbar' => 'path.to.my.view',
        //     'after-toolbar' => 'path.to.my.view',
        //     'before-pagination' => 'path.to.my.view',
        //     'after-pagination' => 'path.to.my.view',
        // ]);

        // $this->setRefreshTime(2000); // Component refreshes every 2 seconds

       // Search & Filters
        $this->setSearchDebounce(500);
        $this->setSearchVisibilityEnabled();
        $this->setFilterLayout('popover');
        $this->setFiltersVisibilityEnabled();
        $this->setPerPageVisibilityEnabled();
        $this->setBulkActionsEnabled();

        // Set Table Wrapper
        // $this->setTableWrapperAttributes([
        //     'id' => 'my-id',
        //     'class' => 'relative overflow-x-auto shadow-md sm:rounded-lg',
        // ]);

        // Set the Table Classes
        $this->setTableAttributes([
            'default' => true,
            'class' => 'w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400',
        ]);

        // Set the Header Classes
        $this->setTheadAttributes([
            'id' => 'my-id',
            'class' => 'w-full text-sm text-left rtl:text-right text-blue-900 dark:text-blue-100',
        ]);

        // Set The Table Body Classes
        $this->setTbodyAttributes([
            'default' => true,
            'class' => 'fw-bold text-blue-900',
        ]);
    }

    // Make a Filter
    public function filters(): array
    {
        return [
            SelectFilter::make('Name')
            ->options([
                '' => 'All',
                'Open' => [
                    1 => 'Type A',
                    2 => 'Type B',
                    3 => 'Type C',
                ],
                'Closed' => [
                    24 => 'Type X',
                    25 => 'Type Y',
                    26 => 'Type Z',
                ],
            ])
            ->setFirstOption('All Tags'),
        ];
    }


    // Make Exportation
    public function export()
    {
        return Excel::download(new UsersExport($this->getFilteredQuery()->get()), 'users.xlsx');
    }
}


