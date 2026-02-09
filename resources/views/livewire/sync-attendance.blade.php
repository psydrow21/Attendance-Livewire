<div>
    {{-- Do your work, then step back. --}}

     <button wire:click="syncAttendance" class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none" type="button">
        Sync Attendance
    </button>

    <!-- Loader -->
    <span wire:loading wire:target="syncAttendance" class="text-blue-900 ml-3">
        <svg class="animate-spin h-5 w-5 inline-block" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <span class="ml-1">Connecting...</span>
    </span>


    <script>
        window.addEventListener('swal:success', () => {
            success();
        });

        window.addEventListener('swal:error', (data) => {
            error(data.detail.message);
        });

    </script>

</div>
