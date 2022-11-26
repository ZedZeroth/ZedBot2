<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

    <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Currencies</h1>

        <ul>
        @foreach($currencies->sortBy('code') as $currency)
            <li>
                <a href='currency/{{ $currency->code }}'>
                    {{ $currency->code }}
                    ({{ $currency->symbol }})
                </a>
            </li>
        @endforeach
</ul>

    </body>
</html>