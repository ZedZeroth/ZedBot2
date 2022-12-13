<span wire:poll.10s>
    <table>
    @if ($payments->count())
        @foreach ($payments as $payment)
            {!! $payment->tableRow() !!}
        @endforeach
    @else
        <tr><td>No recent payments exist.</td></tr>
    @endif
    </table>
</span>