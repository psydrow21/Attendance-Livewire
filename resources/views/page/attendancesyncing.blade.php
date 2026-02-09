@extends('template')

@section('section')

    <h4 class="mb-1 text-2xl font-bold text-heading md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">Syncing.</span> </h4>

    <div class="flex mb-3">
        <div class="w-32 flex-auto">
            @livewire('synclocation')



        </div>

        <div class="w-32 flex-auto">
            @livewire('syncattendance')

            @livewire('export-payroll-format')

        </div>
    </div>


    @livewire('BioLocationTable')


@endsection
