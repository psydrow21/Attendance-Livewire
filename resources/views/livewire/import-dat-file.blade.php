<div>
    <div class="max-w-xl mx-auto p-6 bg-white rounded-xl shadow">

        <form wire:submit="getTotalData" class="space-y-4">

            <input
                type="file"
                wire:model="dat_file"
                name="dat_file"
                accept=".dat,.txt"
                class="block w-full text-sm text-gray-700
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100"
            >

            @error('dat_file')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg
                    hover:bg-blue-700 disabled:opacity-50">
                Upload Dat File Attendance
            </button>
        </form>

    </div>
</div>
