<div>
    {{-- The best athlete wants his opponent at his best. --}}

    <form class="max-w-sm mx-auto">
    <label for="countries" class="block mb-2.5 text-sm font-medium text-heading">Select an option</label>
        <select id="countries" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
            <option selected>Choose a country</option>
            <option value="US">United States</option>
            <option value="CA">Canada</option>
            <option value="FR">France</option>
            <option value="DE">Germany</option>
        </select>
    </form>


    <div class="max-w-xl mx-auto p-6 bg-white rounded-xl shadow">

        <form wire:submit="importzkteco" class="space-y-4">

            <input
                type="file"
                wire:model="zkteco_file"
                name="zkteco_file"
                accept=".xls,.xlsx"
                class="block w-full text-sm text-gray-700
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100"
            >

            @error('zkteco_file')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg
                    hover:bg-blue-700 disabled:opacity-50">
                Upload From Zkteco Attendance Management
            </button>
        </form>

    </div>
</div>
