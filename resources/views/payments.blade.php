<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Payments on every network</h1>

        <ul><li>
        <a href='/payment/networks'>
            View payments by network instead
        </a>
        </li></ul>

        {!! $paymentsTable !!}

    </body>
</html>