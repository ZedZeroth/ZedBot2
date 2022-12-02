<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Customers</h1>

        @foreach ($customers as $customer)
            <a href="/customer/{{ $customer->identifier }}">
                {{ $customer->identifier }}
            </a>
        @endforeach

    </body>
</html>