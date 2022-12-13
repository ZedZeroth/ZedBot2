<span wire:poll.5s>
    <form>
    💸 <a href="payments">Payments</a>
    ({{ $payments->count() }})
        <button wire:click="sync('ENM0')">ENM0</button>
        <button wire:click="sync('MMP0')">MMP0</button>
        <input wire:model='numberToFetch' type="text" size="3">
    </form>
</span>