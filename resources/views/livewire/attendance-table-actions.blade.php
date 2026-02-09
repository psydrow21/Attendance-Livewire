<div class="flex space-x-2">
    <a href="{{ route('attendance.show', $attendance->id) }}"
       class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
       View
    </a>

    <button wire:click="$emit('exportRow', {{ $attendance->id }})"
            class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
        Export
    </button>
</div>
