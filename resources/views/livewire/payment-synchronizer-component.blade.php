<span wire:poll.5s>
    ({{ $payments->count() }})
    <form>
    <button wire:click="sync('ENM')">ENM</button>
    <button wire:click="sync('ENMF')">ENMF</button>
        <input wire:model='numberToFetch' type="text" size="3">
    </form>
</span>