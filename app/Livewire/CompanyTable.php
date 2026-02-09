<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Company;

class CompanyTable extends DataTableComponent
{
    protected $model = Company::class;

    public $showEditModal = false; // control modal visibility
    public $companyToEdit; // store company being edited

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Company Name", "company_name")
                ->sortable()->searchable()
                ->format(fn($value, $row, Column $column) =>
                    '<span class="text-white rounded-2xl '.$this->colorCoding($row->acronym).' font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5 ">'.$value .'</span>'
                )
                ->html(),
            Column::make("Acronym", "acronym")
                ->sortable()->searchable()
                ->format(fn($value, $row, Column $column) =>
                    '<span class="text-white rounded-2xl bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">'.$value .'</span>'
                )
                ->html(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),


            // (1)
            // Sample modified HTML
            Column::make('Acronym Color', 'acronym')
                ->format(
                    fn($value, $row, Column $column) => $this->colorCoding($value)
                )
                ->html(),

            // (2)
            // Sample Action Button
            Column::make("Actions")
                ->label(function ($row) {
                    return view('livewire.company.company_actions', ['company' => $row]);
                }),
        ];
    }

    // (1-2)
    // Sample Modified Function HTML
    public function colorCoding(string $color) : string
    {
        $color_coding = [
            'EF' => 'bg-red-700',
            'FMLC' => 'bg-violet-700',
            'MBI' => 'bg-blue-700',
            'WC' => 'bg-red-700',
            'MSC' => 'bg-violet-700'
        ];

        return $color_coding[$color] ?? $color;
        // return 'TEST';
    }

    // Handle edit action
    // public function edit($id)
    // {
    //     $company = Company::find($id);

    //     if ($company) {
    //         // Example: You can emit an event, open a modal, or redirect
    //         $this->dispatch('editCompany', $company->id);
    //         session()->flash('message', "Editing company: {$company->company_name}");
    //     }
    // }

    // (2-2)
    // Open modal for edit
    public function edit($id)
    {
        $this->companyToEdit = Company::find($id);

        if ($this->companyToEdit) {
            $this->showEditModal = true;
        }
    }

    // (2-3)
    // Update company
    public function update()
    {
        $this->validate([
            'companyToEdit.company_name' => 'required|string|max:255',
            'companyToEdit.acronym' => 'nullable|string|max:10',
        ]);

        $this->companyToEdit->save();

        $this->showEditModal = false;

        session()->flash('message', "Company updated successfully!");
    }

    // Make Exportation
    public function export()
    {
        return Excel::download(new UsersExport($this->getFilteredQuery()->get()), 'users.xlsx');
    }



}
