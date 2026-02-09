<div>



    <div class="flex">
        <div class="w-14 flex-1 m-2 gap-2">
            {{-- <label for="email" class="block mb-2.5 text-sm font-medium text-heading">Employee: </label>

            <select id="selected_employees" name="selected_employees" wire:model="selected_employees" multiple>
                @foreach ($oms_employees as $employee)
                    <option value="{{ $employee['emp_id'] }}">{{ $employee['emp_id'] }} - {{ $employee['name'] }}</option>
                @endforeach
            </select> --}}

            <label class="block mb-2.5 text-sm font-medium text-heading">
                Employee:
            </label>

            <div wire:ignore>
                <select id="selected_employees" multiple class="w-full">
                     <option value="">All Employee</option>
                    @foreach ($oms_employees as $employee)
                        <option value="{{ $employee['emp_id'] }}">
                            {{ $employee['emp_id'] }} - {{ $employee['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>



        </div>

        <div class="w-32 flex-1 m-2">
            <label for="email" class="block mb-2.5 text-sm font-medium text-heading">Date Filter: </label>
            <div id="date-range-picker" class="flex items-center" >
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/></svg>
                    </div>
                    <input id="date_from" wire:model="date_from" type="date" class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="Select date">
                </div>
                <span class="mx-4 text-body">to</span>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/></svg>
                    </div>
                        <input id="date_to" wire:model="date_to" type="date" class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="Select date">
                    </div>
                </div>
            </div>

        <div class="w-14 flex-1 m-2 mt-4">
            <button wire:click="filterEmployeeDate" class="relative inline-flex items-center justify-center cursor-pointer p-0.5 overflow-hidden text-sm font-medium text-heading rounded-base group bg-gradient-to-br from-green-400 to-blue-600 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800">
                <span class=" relative px-4 py-2.5 transition-all ease-in duration-75 bg-neutral-primary-soft rounded-base group-hover:bg-transparent group-hover:dark:bg-transparent leading-5">
                Filter
                </span>

                @error('selected_employees') <span class="text-red-600 text-body text-bold text-l">{{ $message }}</span> @enderror
            </button>
        </div>

    </div>

    {{-- The whole world belongs to you. --}}
    @foreach($oms_employees as $employee)

    @if(in_array($employee['emp_id'], $selected_employees))

    @php
        $employee_id = $employee['emp_id'];
        $employee_name =  $employee['name'];
        $attendance_logs = [];
    @endphp

    <h2 class="mt-2 text-2xl text-red-600">{{ $employee['name'] }} - {{ $employee['emp_id'] }}</h2>
    <div class="overflow-x-auto">
    <table class="mb-2 w-full text-sm text-left text-gray-500 border border-slate-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-300">
            <tr>
                <th scope="col" rowspan="2" class="text-center px-6 py-3 border border-slate-500">
                    Date
                </th>
                <th scope="col" rowspan="2" class="text-center px-6 py-3 border border-slate-500">
                    Day
                </th>
                <th scope="col" colspan="4" class="text-center px-6 py-3 border border-slate-500">
                    Attendance Record
                </th>
                <th scope="col" colspan="2" class="text-center px-6 py-3 border border-slate-500">
                    Duration Status
                </th>
            </tr>

            <tr>
                <th scope="col" class="text-center px-6 py-3 border border-slate-500">
                    Morning In
                </th>
                <th scope="col" class="text-center px-6 py-3 border border-slate-500">
                    Lunch Out
                </th>
                <th scope="col" class="text-center px-6 py-3 border border-slate-500">
                    Lunch In
                </th>
                <th scope="col" class="text-center px-6 py-3 border border-slate-500">
                    Evening Out
                </th>
                <th scope="col" class="text-center px-6 py-3 border border-slate-500">
                    UnderTime
                </th>
                <th scope="col" class="text-center px-6 py-3 border border-slate-500">
                    Late
            </tr>
        </thead>
        <tbody>
            @foreach($att_per_employee as $key => $p)

            @if ($p['emp_id'] == $employee['emp_id'])
                {{-- Store in array to store it in the Excel Button --}}
                @php
                    $attendance_logs[] = array(
                        'period' => $p['period'],
                        'day' => $p['day'],
                        'morning_in' => $p['morning_in'],
                        'morning_out' => $p['morning_out'],
                        'lunch_in' => $p['lunch_in'],
                        'evening_out' => $p['evening_out'],
                        'undertime' => $p['undertime'],
                        'late' => $p['late'],
                    );
                @endphp

                {{-- Displaying of Data in the table --}}
                <tr class="bg-white">
                    <th scope="row" class="text-center px-6 py-4 border border-slate-500 font-medium text-gray-900 @if(in_array($p['day'], ['Saturday', 'Sunday'])) text-white bg-red-600 {{ $weekends_class }} @endif whitespace-nowrap">
                        {{ $p['period'] }}
                    </th>
                    <td class="text-center px-6 py-4 border border-slate-500 font-medium text-gray-900 @if(in_array($p['day'], ['Saturday', 'Sunday'])) text-white bg-red-600 {{ $weekends_class }} @endif whitespace-nowrap">
                        {{ $p['day'] }}
                    </td>
                    <th scope="row" class="text-center px-6 py-4 border border-slate-500 font-medium text-gray-900 @if(in_array($p['day'], ['Saturday', 'Sunday'])) text-white bg-red-600 {{ $weekends_class }} @endif whitespace-nowrap">
                        {{ $p['morning_in'] }}
                    </th>
                    <td class="text-center px-6 py-4 border border-slate-500 font-medium text-gray-900 @if(in_array($p['day'], ['Saturday', 'Sunday'])) text-white bg-red-600 {{ $weekends_class }} @endif whitespace-nowrap">
                        {{ $p['morning_out'] }}
                    </td>
                    <td class="text-center px-6 py-4 border border-slate-500 font-medium text-gray-900 @if(in_array($p['day'], ['Saturday', 'Sunday'])) text-white bg-red-600 {{ $weekends_class }} @endif whitespace-nowrap">
                        {{ $p['lunch_in'] }}
                    </td>
                    <td class="text-center px-6 py-4 border border-slate-500 font-medium text-gray-900 @if(in_array($p['day'], ['Saturday', 'Sunday'])) text-white bg-red-600 {{ $weekends_class }} @endif whitespace-nowrap">
                        {{ $p['evening_out'] }}
                    </td>
                    <td class="text-center px-6 py-4 border border-slate-500 font-medium text-gray-900 @if(in_array($p['day'], ['Saturday', 'Sunday'])) text-white bg-red-600 {{ $weekends_class }} @endif whitespace-nowrap">
                        {{ $p['undertime'] }}
                    </td>
                    <td class="text-center px-6 py-4 border border-slate-500 font-medium text-gray-900 @if(in_array($p['day'], ['Saturday', 'Sunday'])) text-white bg-red-600 {{ $weekends_class }} @endif whitespace-nowrap">
                        {{ $p['late'] }}
                    </td>
                </tr>

            @endif
            @endforeach

        </tbody>
    </table>
    </div>

            <button wire:click="exportSimplifiedAttendance({{ $employee_id }} ,'{{ $employee_name }}',{{ json_encode($attendance_logs) }})" class="mb-2 relative inline-flex items-center justify-center cursor-pointer p-0.5 overflow-hidden text-sm font-medium text-heading rounded-base group bg-gradient-to-br from-green-400 to-blue-600 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800">
                <span class=" relative px-4 py-2.5 transition-all ease-in duration-75 bg-neutral-primary-soft rounded-base group-hover:bg-transparent group-hover:dark:bg-transparent leading-5">
                Download Excel
                </span>
            </button>
    <div class="inline-flex items-center justify-center w-full">
        <hr class="w-64 h-1 my-8 bg-neutral-quaternary border-0 rounded-sm">

        <hr class="w-64 h-1 my-8 bg-neutral-quaternary border-0 rounded-sm">
    </div>
    <hr>
    @endif
    @endforeach

    <script>
    // document.addEventListener('DOMContentLoaded', () => {
    //     const start = document.getElementById('date_from');
    //     const end = document.getElementById('date_to');

    //     const select = $('#selected_employees').select2({ width: '100%',
    //         theme: 'classic'  });

    //     select.on('change', () => {
    //         @this.set('selected_employees', select.val());
    //     });

    // });

    document.addEventListener('livewire:navigated', () => {
        // reinitialize JS, refetch data, reset state
        const start = document.getElementById('date_from');
        const end = document.getElementById('date_to');

        const select = $('#selected_employees').select2({ width: '100%',
            theme: 'classic'  });

        select.on('change', () => {
            @this.set('selected_employees', select.val());
        });
    })
    </script>

    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('request', ({ succeed, fail }) => {
            const start = document.getElementById('date_from');
            const end = document.getElementById('date_to');

            loader();

            // ✅ CLOSE on success
            succeed(() => {
                Swal.close();

                start.addEventListener('change', () => {
                    @this.set('date_from', start.value);
                });

                end.addEventListener('change', () => {
                    @this.set('date_to', end.value);
                });

            });

            // ❌ CLOSE on error
            fail(() => {
                Swal.close();
            });
        });
    });
    </script>


</div>
