<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

    <a href="/">🏠</a> &bull; <a href="/account/networks">↩️</a>

        <h1>Accounts on the {{ $network }} Network</h1>

        <ul><li>
        <a href='/accounts'>
            View accounts on every network instead
        </a>
        </li></ul>

        {!! $accountsTable !!}

    </body>
</html>