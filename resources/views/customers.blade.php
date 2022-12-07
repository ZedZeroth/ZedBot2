<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Customers</h1>

        <ul>
        @foreach ($customers as $customer)
            <li>
                <a href="/customer/{{ $customer->identifier }}">
                    {{ $customer->fullName() }}
                </a>
            </li>
        @endforeach
        </ul>

    </body>
</html>