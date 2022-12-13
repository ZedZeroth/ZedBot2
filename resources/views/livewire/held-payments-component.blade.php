<span wire:poll.10s>
    <h3>Held Payments ({{ $paymentCount }})</h3>
    <table>
    @if ($payments->count())
        @foreach ($payments as $payment)
            {!! $payment->tableRow() !!}
        @endforeach
    @else
        <tr><td>No recent payments have been held.</td></tr>
    @endif
    </table>
</span>