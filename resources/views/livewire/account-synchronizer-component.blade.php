<span wire:poll.5s>
    ({{ $accounts->count() }})
    <form>
        <button wire:click="sync('ENM')">ENM</button>
        <button wire:click="sync('LCS')">LCS</button>
        <input wire:model='numberToFetch' type="text" size="3">
    </form>
</span>