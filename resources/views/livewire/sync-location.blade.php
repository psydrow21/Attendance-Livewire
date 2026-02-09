<div>
    {{-- The Master doesn't talk, he acts. --}}

    <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none" type="button">
        Sync Location
    </button>

    <div id="popup-modal" wire:ignore.self tabindex="-1" data-modal-backdrop="static" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="w-full max-w-md bg-sky-500 rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="relative shadow-2xl rounded-lg  bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6">
                    <button type="button" class="cursor-not-allowed absolute top-3 end-2.5 text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center hover:text-white" data-modal-hide="popup-modal">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-fg-disabled w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>

                    <h3 class="mb-1 text-body text-bold text-2xl">IP Address</h3>
                    @error('ip') <span class="text-red-600 text-body text-bold text-l">{{ $message }}</span> @enderror
                    <input type="text" wire:model.live="ip" placeholder="___ . ___ . ___ . ___" class="rounded-lg shadow bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body mb-1">
                    <button wire:click="ipAdressChecking" class="cursor-pointer text-white rounded lg shadow-2xl px-4 py-2 bg-blue-700 bg-danger box-border border border-transparent hover:bg-blue-900 transition box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Check IP Address  </button>
                    <span class="text-red-600 text-body text-bold text-l">{{ $ip }} <br> {{ $status }}</span>

                    <!-- Loader -->
                    <span wire:loading wire:target="ipAdressChecking" class="text-blue-900 ml-3">
                        <svg class="animate-spin h-5 w-5 inline-block" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span class="ml-1">Connecting...</span>
                    </span>

                    <h3 class="mb-1 text-body text-bold text-2xl">Location</h3>
                    @error('location') <span class="text-red-600 text-body text-bold text-l">{{ $message }}</span> @enderror
                    <input wire:model="location" type="text" class="rounded-lg shadow bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body mb-6" name="location" id="location">

                    <h3 class="text-body text-bold text-xl">TTL display <span class="text-red-700 text-bold text-l">(When pinging biometrics what is the display TTL)</span></h3>
                    @error('ttl') <span class="text-red-600 text-body text-bold text-l">{{ $message }}</span> @enderror
                    <select wire:model="ttl" type="text" class="rounded-lg shadow bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body mb-6" name="ttl" id="ttl">
                        <option value="" selected disabled>Please Select TTL output</option>
                        {{-- Foreach the TTL display from the livewire --}}
                        @foreach($ttl_option as $ttl_opt)
                            <option value="{{ $ttl_opt->ttl_respond }}">{{ $ttl_opt->ttl_respond }}</option>
                        @endforeach

                    </select>

                    <h3 class="mb-1 text-body text-bold text-xl">Biometrics Model</h3>
                    @error('biomodel') <span class="text-red-600 text-body text-bold text-l">{{ $message }}</span> @enderror
                    <select wire:model="biomodel" type="text" class="rounded-lg shadow bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body mb-6" name="biomodel" id="biomodel">
                        <option value="" selected disabled>Please Select Biometrics Model</option>
                        {{-- Foreach the Biometrics Model from the livewire --}}
                        @foreach($biomodel_options as $biomodel_opt)
                                <option value="{{ $biomodel_opt->biometrics_model }}">{{ $biomodel_opt->biometrics_model }}</option>
                        @endforeach

                    </select>

                    <div class="flex items-center space-x-4 justify-center">
                        <button wire:click="bioLocationSave" type="button" class="cursor-pointer bg-green-700 rounded-lg shadow text-white bg-danger box-border border border-transparent hover:bg-green-900 transition box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                        Yes, I'm sure
                        </button>
                        <button data-modal-hide="popup-modal" type="button" class="cursor-not-allowed bg-red-600 text-white rounded-lg shadow text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-red-900 transition box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">No, cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
