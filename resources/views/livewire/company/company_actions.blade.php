<div class="flex space-x-2">
    <button
        wire:click="edit({{ $company->id }})"
        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
    >
        Edit
    </button>

    <button
        wire:click="$dispatch('deleteCompany', {{ $company->id }})"
        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
    >
        Delete
    </button>
</div>
