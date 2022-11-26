<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

    <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Payment Networks</h1>

        <ul>
        @foreach($payments->sortByDesc('network') as $payment)
            <li>                
                <a href='/{{ $payment->network }}/payments'>
                    {{ $payment->network }}
                </a>
            </li>
        @endforeach
        </ul>

    </body>
</html>