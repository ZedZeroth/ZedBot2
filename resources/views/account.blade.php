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
            <a href="/customers/{{ $account->customer_id }}">
                { $account->holder }
            </a>
        </h3>
        Network account name: {{ $account->networkAccountName }}
        <br>Label: {{ $account->label }}

        <h3>
            Balance:
            <a href="/currency/{{ $account->currency()->first()->code }}">
                {{ $account->currency()->first()->code }}</a>
                {{ $account->formatBalance() }}
        </h3>

        <h2><anchor id="model-data">Model data</h2>
        {!! $modelTable !!}

        <span style="color: green;">
            <h3><anchor id="credits">Credits to this account</h3>
            @if ($account->credits()->count())
                {!! $creditsTable !!}
            @else
                <p>None</p>
            @endif
        </span>

        <span style="color: red;">
            <h3><anchor id="debits">Debits from this account</h3>
            @if ($account->debits()->count())
                {!! $debitsTable !!}
            @else
                <p>None</p>
            @endif
        </span>

    </body>
</html>