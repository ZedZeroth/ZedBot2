<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Customers</h1>

        <ul>
        @foreach ($customers->sortBy('identifier') as $customer)
            <a href="/customer/{{ $customer->identifier }}">
                <li>{{ $customer->identifier }}</li>
            </a>
        @endforeach
        </ul>

    </body>
</html>