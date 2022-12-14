<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/accounts">↩️</a>

        <h1>Account: {{ $account->identifier }}</h1>

        <ul>
            <li><a href="#details">Details</a></li>
            <li><a href="#model-data">Model data</a></li>
            <li><a href="#credits">Credits to this account</a></li>
            <li><a href="#debits">Debits from this account</a></li>
        </ul>

        <h2><anchor id="details">Details</h2>

        <h3>Network:
            <a href="/{{ $account->network }}/accounts">
                {{ $account->network }}
            </a>
        </h3>

        <h3>Holder:
            @if ($account->customer)
                <a href="/customer/{{ $account->customer->identifier}}">
                    {{ $account->customer->fullName() }}
                </a>
            @else
                <span style="font-style: italic;">NONE</span>
            @endif
        </h3>
        Network account name: {{ $account->networkAccountName }}
        <br>Label: {{ $account->label }}

        <h3>
            Balance:
            @if (is_null($account->balance))
                <span style="font-style: italic;">UNKNOWN</span>
            @else
                <a href="/currency/{{ $account->currency()->first()->code }}">
                    {{ $account->currency()->first()->code }}</a>
                    {{ $account->formatBalance() }}
            @endif
        </h3>

        <h2><anchor id="model-data">Model data</h2>
        <table>
        {!! $account->tableRow() !!}
        </table>

        <span style="color: green;">
            <h3><anchor id="credits">Credits to this account</h3>
            <table>
            @if ($account->credits)
                @foreach ($account->credits->sortByDesc('timestamp') as $credit)
                    {!! $credit->tableRow() !!}
                @endforeach
            @else
                No credits exist for this account.
            @endif
            </table>
        </span>

        <span style="color: red;">
            <h3><anchor id="debits">Debits from this account</h3>
            <table>
            @if ($account->debits)
                @foreach ($account->debits->sortByDesc('timestamp') as $debit)
                    {!! $debit->tableRow() !!}
                @endforeach
            @else
                No debits exist for this account.
            @endif
            </table>
        </span>

    </body>
</html>