<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Accounts on every network</h1>

        <ul><li>
        <a href='/account/networks'>
            View accounts by network instead
        </a>
        </li></ul>
        <table>
            @if ($accounts->count())
                @foreach ($accounts as $account)
                    {!! $account->tableRow() !!}
                @endforeach
            @else
                No accounts exist.
            @endif
        </table>

    </body>
</html>