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

        <table>
        @if ($accounts->count())
            @foreach ($accounts as $account)
                {!! $account->tableRow() !!}
            @endforeach
        @else
            No accounts on this network.
        @endif
        </table>
    </body>
</html>