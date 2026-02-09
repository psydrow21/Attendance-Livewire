<div>
    {{-- Because she competes with no one, no one can compete with her. --}}

    <h4 class="mb-1 text-2xl font-bold text-heading md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">Device Action.</span> </h4>

    <p class="p-0 m-0 text-lg font-normal text-body lg:text-xl">IP: <span class="text-red-700">{{ $active_ip }}</span></p>

    <p class="p-0 m-0 text-lg font-normal text-body lg:text-xl">TTL: <span class="text-red-700">{{ $active_ttl }}</span></p>

    <p class="p-0 m-0 text-lg font-normal text-body lg:text-xl">Location: <span class="text-red-700">{{ $location }}</span></p>

        <button type="button"
        wire:load.attr="disabled"
        wire:click="deviceTestVoice"
        class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg shadow
        hover:bg-green-700 transition box-border border border-transparent hover:bg-brand-strong
        focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        id="devicetestvoice" name="devicetestvoice"><i class="ti-export"></i> Test Voice</button>

        <button type="button"
        wire:click="devicePowerOff"
        class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg shadow
        hover:bg-green-700 transition box-border border border-transparent hover:bg-brand-strong
        focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        id="devicepoweroff" name="devicepoweroff"><i class="ti-export"></i> Power Off</button>

        <button type="button"
        wire:click="deviceRestart"
        class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg shadow
        hover:bg-green-700 transition box-border border border-transparent hover:bg-brand-strong
        focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        id="devicerestart" name="devicerestart"><i class="ti-export"></i> Restart</button>

        <button type="button"
        wire:click="deviceClearAttendance"
        wire:confirm.prompt="Are you sure?\n\nType DELETE to confirm|DELETE"
        class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg shadow
        hover:bg-green-700 transition box-border border border-transparent hover:bg-brand-strong
        focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        id="export" name="export"><i class="ti-export"></i> Clear Attendance Logs on Biometrics</button>

        <button type="button"
        wire:click="deviceUser"
        class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg shadow
        hover:bg-green-700 transition box-border border border-transparent hover:bg-brand-strong
        focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        id="deviceuser" name="deviceuser"><i class="ti-export"></i> Users In Biometrics</button>

        <button type="button"
        wire:click="deviceSerialNumber"
        class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg shadow
        hover:bg-green-700 transition box-border border border-transparent hover:bg-brand-strong
        focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        id="deviceserialnumber" name="deviceserialnumber"><i class="ti-export"></i> Biometrics Serial Number</button>

        <button type="button"
        wire:click="deviceOsVersion"
        class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg shadow
        hover:bg-green-700 transition box-border border border-transparent hover:bg-brand-strong
        focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        id="deviceosversion" name="deviceosversion"><i class="ti-export"></i> OS Version</button>

        <button type="button"
        wire:click="deviceName"
        class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg shadow
        hover:bg-green-700 transition box-border border border-transparent hover:bg-brand-strong
        focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        id="devicename" name="devicename"><i class="ti-export"></i> Device Name</button>



    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('request', ({ succeed, fail }) => {
            loader();
        });
    });
    </script>


    <script>
        window.addEventListener('swal:success', (data) => {
            success(data.detail.message);
        });

        window.addEventListener('swal:error', (data) => {
            error(data.detail.message);
        });
    </script>



</div>
