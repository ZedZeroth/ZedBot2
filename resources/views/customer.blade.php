<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/customers">↩️</a>

        <h1>Customer: {{ $customer->identifier }}</h1>

        <ul>
            <li><a href="#details">Details</a></li>
            <li><a href="#model-data">Model data</a></li>
            <li><a href="#accounts">Accounts held</a></li>
            <li><a href="#credits">Credits to this customer</a></li>
            <li><a href="#debits">Debits from this customer</a></li>
        </ul>

        <h2><anchor id="details">Details</h2>

        Name: {{ $customer->familyName }}

        <h2><anchor id="model-data">Model data</h2>
        {!! $modelTable !!}

        <span>
            <h3><anchor id="accounts">Accounts held</h3>
            @if ($customer->accounts()->count())
                @foreach ($customer->accounts()->get() as $account)
                    <br>{{ $account->identifier }}
                @endforeach
            @else
                <p>None</p>
            @endif
        </span>

    </body>
</html>