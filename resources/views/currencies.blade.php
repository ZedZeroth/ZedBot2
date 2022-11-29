<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

    <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Currencies</h1>

        <ul>
        @if ($currencies->count())
            @foreach($currencies->sortBy('code') as $currency)
                <li>
                    <a href='currency/{{ $currency->code }}'>
                        {{ $currency->code }}
                        ({{ $currency->symbol }})
                    </a>
                </li>
            @endforeach
        @else
            No currencies exist.
        @endif
</ul>

    </body>
</html>