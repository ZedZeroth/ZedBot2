<span wire:poll.5s>
    ({{ $accounts->count() }})
    <form>
        <button wire:click="sync('ENM0')">ENM0</button>
        <button wire:click="sync('LCS0')">LCS0</button>
        <input wire:model='numberToFetch' type="text" size="3">
    </form>
</span>