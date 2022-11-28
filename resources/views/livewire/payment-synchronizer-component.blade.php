<span wire:poll.5s>
    ({{ $payments->count() }})
    <form>
    <button wire:click="sync('ENM0')">ENM0</button>
        <input wire:model='numberToFetch' type="text" size="3">
    </form>
</span>