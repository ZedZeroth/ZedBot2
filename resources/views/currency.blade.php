<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/currencies">↩️</a>

        <h1>Currency: {{ $currency->code }}</h1>
        <ul>
            <li><a href="#denominations">Denominations</a></li>
            <li><a href="#model-data">Model data</a></li>
            <li><a href="#payments">Payments in this currency</a></li>
            <li><a href="#accounts">Accounts in this currency</a></li>
        </ul>

        <h2><anchor id="denominations">Denominations</h2>
        <p>
            {{ $currency->symbol }} 1 =
            {{ $moneyConverter->convert(1, $currency) }}
            {{ $currency->baseUnitNamePlural }}
        </p>

        <h2><anchor id="model-data">Model data</h2>
        {!! $modelTable !!}

        <h2><anchor id="payments">Payments in this currency</h2>
        @if ($currency->payments()->count())
            {!! $paymentsTable !!}
        @else
            <p>None</p>
        @endif

        <h2><anchor id="accounts">Accounts in this currency</h2>
        @if ($currency->accounts()->count())
            {!! $paymentsTable !!}
        @else
            <p>None</p>
        @endif

    </body>
</html>