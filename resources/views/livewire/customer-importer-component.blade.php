<span wire:poll.5s>
    ({{ $customers->count() }})
    <button wire:click="import">Import</button>
</span>