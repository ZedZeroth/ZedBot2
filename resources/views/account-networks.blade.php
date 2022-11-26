<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

    <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Account Networks</h1>

        <ul>
        @foreach($accounts->sortBy('network') as $account)
            <li>                
                <a href='/{{ $account->network }}/accounts'>
                    {{ $account->network }}
                </a>
            </li>
        @endforeach
        </ul>

    </body>
</html>