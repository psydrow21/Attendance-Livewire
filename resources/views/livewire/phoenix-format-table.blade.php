<div>
    {{-- Because she competes with no one, no one can compete with her. --}}

     <div class="flex">
        <div class="w-64 flex-auto">
            <label class="block mb-2.5 text-sm font-medium text-heading">
                Location:
            </label>

            <div wire:ignore>
                <select class="js-example-responsive" id="selected_location">
                     <option value="" selected disabled>Select Branches</option>
                    @foreach ($active_location as $location)
                        <option value="{{ $location->location }}">
                            {{ $location->location }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="w-64 flex-auto m-2">
            <label for="email" class="block mb-2.5 text-sm font-medium text-heading">Date Filter: </label>
            <div id="date-range-picker" class="flex items-center" >
                <button wire:click="subtractMonth" class="m-2 relative inline-flex  items-center justify-center p-0.5 overflow-hidden text-sm font-medium text-heading rounded-base group bg-gradient-to-br cursor-pointer from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                    <span class=" relative px-4 py-2.5 transition-all ease-in duration-75 bg-neutral-primary-soft rounded-base group-hover:bg-transparent group-hover:dark:bg-transparent leading-5">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m17 16-4-4 4-4m-6 8-4-4 4-4"/>
                        </svg>
                    </span>
                </button>

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

                <button wire:click="addMonth" class="m-2 relative inline-flex items-center justify-center p-0.5 overflow-hidden text-sm font-medium text-heading rounded-base group bg-gradient-to-br cursor-pointer from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                    <span class=" relative px-4 py-2.5 transition-all ease-in duration-75 bg-neutral-primary-soft rounded-base group-hover:bg-transparent group-hover:dark:bg-transparent leading-5">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 16 4-4-4-4m6 8 4-4-4-4"/>
                        </svg>
                    </span>
                </button>
                </div>
            </div>

        <div class="w-14 flex-1 m-2 mt-4">
            <button type="button" wire:click.prevent="filterPayrollFormat" class="relative inline-flex items-center justify-center cursor-pointer p-0.5 overflow-hidden text-sm font-medium text-heading rounded-base group bg-gradient-to-br from-green-400 to-blue-600 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800">
                <span class=" relative px-4 py-2.5 transition-all ease-in duration-75 bg-neutral-primary-soft rounded-base group-hover:bg-transparent group-hover:dark:bg-transparent leading-5">
                Filter
                </span>
            </button>

            <button type="button" wire:click.prevent="exportTextFile" class="relative inline-flex items-center justify-center cursor-pointer p-0.5 overflow-hidden text-sm font-medium text-heading rounded-base group bg-gradient-to-br from-green-400 to-blue-600 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800">
                <span class=" relative px-4 py-2.5 transition-all ease-in duration-75 bg-neutral-primary-soft rounded-base group-hover:bg-transparent group-hover:dark:bg-transparent leading-5">
                Export
                </span>

                @error('selected_location') <span class="text-red-600 text-body text-bold text-l">{{ $message }}</span> @enderror
            </button>
        </div>


    </div>

    {{-- Table --}}
    <div class="relative overflow-x-auto bg-neutral-primary shadow-xs rounded-base border border-default" >
    <table class="w-full text-sm text-left rtl:text-right text-body">
        <thead class="text-sm text-body border-b border-default">
            <tr>
                <th scope="col" class="px-6 py-3 bg-neutral-secondary-soft font-medium">
                    EMPLOYEE ID
                </th>
                <th scope="col" class="px-6 py-3 font-medium">
                    TYPE
                </th>
                <th scope="col" class="px-6 py-3 bg-neutral-secondary-soft font-medium">
                    DATE
                </th>
                <th scope="col" class="px-6 py-3 font-medium">
                    TIME(MILITARY)
                </th>
            </tr>
        </thead>
        <tbody>
            @if (count($attendance_formatted_logs) > 0)

                @foreach ($attendance_formatted_logs as $att)

                    <tr class="border-b border-default">
                        <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap bg-neutral-secondary-soft">
                            {{ $att['emp_id'] }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $att['type'] }}
                        </td>
                        <td class="px-6 py-4 bg-neutral-secondary-soft">
                            {{ $att['datelogs'] }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $att['timelogs'] }}
                        </td>
                    </tr>

                @endforeach

            @else
                <tr><td colspan="4" class="text-center py-4">No records found</td></tr>
            @endif

        </tbody>
    </table>
    </div>

    <script>
    // document.addEventListener('DOMContentLoaded', () => {
    //     const select = $('#selected_location').select2({ width: '100%' });

    //     select.on('change', () => {
    //         @this.set('selected_location', select.val());
    //     });

    // });

    document.addEventListener('livewire:navigated', () => {
        // reinitialize JS, refetch data, reset state
        const select = $('#selected_location').select2({ width: '100%' });

        select.on('change', () => {
            @this.set('selected_location', select.val());
        });
    })
    </script>

    <script>
        window.addEventListener('download_payroll_format', (data) => {
            loader();

            // Success
            if (data.detail.success === true || data.detail.success === 'true') {

                // Create temp anchor
                const a = document.createElement('a');
                a.href = data.detail.file;
                a.download = ''; // force download
                a.style.display = 'none';

                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                success();
            } else {
                console.error(data.detail.message);
                error(data.detail.message);
            }

        });


        // Filter Payroll
        window.addEventListener('filter_payroll_format', (data) => {
            loader();

            if(data.detail.success == 'true'){
                swal.close();
            }else{
                error(data.detail.message);
            }
        });
    </script>

</div>
