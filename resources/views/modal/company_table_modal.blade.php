<div>
    @if($showEditModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg w-96">
                <h2 class="text-lg font-bold mb-4">Edit Company</h2>

                <div class="mb-4">
                    <label class="block mb-1">Company Name</label>
                    <input type="text" wire:model.defer="companyToEdit.company_name"
                           class="w-full border rounded px-2 py-1">
                    @error('companyToEdit.company_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Acronym</label>
                    <input type="text" wire:model.defer="companyToEdit.acronym"
                           class="w-full border rounded px-2 py-1">
                    @error('companyToEdit.acronym') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('showEditModal', false)"
                            class="bg-gray-500 text-white px-4 py-1 rounded">
                        Cancel
                    </button>
                    <button wire:click="update"
                            class="bg-green-500 text-white px-4 py-1 rounded">
                        Save
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
