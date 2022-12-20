<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Customers</h1>

        <table>
        @foreach ($customers as $customer)
            <tr>
                <td>{!! $customer->linkTo(30) !!}</td>
                <td>{!! $customer->ageEmojis() !!}</td>
                <td>{!! $customer->location() !!}</td>
                <td>{!! $customer->volumeEmojis() !!}</td>
                <td>{!! $customer->riskAssessmentEmojis() !!}</td>
                <td>
                @foreach ($customer->identityDocuments as $identityDocument)
                    {{ $identityDocument->emoji() }}
                    {{ $identityDocument->dateOfExpiry }}
                @endforeach
                </td>
                <td>
                @foreach ($customer->contacts as $contact)
                    {{ $contact->emoji() }}
                    {{ $contact->handle }}
                @endforeach
                </td>
            </tr>
        @endforeach
</table>

    </body>
</html>