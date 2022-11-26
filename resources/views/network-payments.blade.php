<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/payment/networks">↩️</a>

        <h1>Payments on the {{ $network }} Network</h1>

        <ul><li>
        <a href='/payments'>
            View payments on every network instead
        </a>
        </li></ul>

        {!! $paymentsTable !!}

    </body>
</html>