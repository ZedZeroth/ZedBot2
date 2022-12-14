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
                {!! $customer->linkTo(30) !!}
                @foreach ($customer->contacts as $contact)
                    {{ $contact->emoji() }}
                    {{ $contact->handle }}
                @endforeach
            </li>
        @endforeach
        </ul>

    </body>
</html>