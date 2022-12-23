<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/customers">↩️</a>

        <h1>Customer: {{ $customer->identifier }}</h1>

        <ul>
            <li><a href="#details">Details</a></li>
            <li><a href="#risk-assessments">Risk assessments</a></li>
            <li><a href="#model-data">Model data</a></li>
            <li><a href="#accounts">Accounts held</a></li>
            <li><a href="#credits">Credits to this customer</a></li>
            <li><a href="#debits">Debits from this customer</a></li>
        </ul>

        <h2><anchor id="details">Details</h2>
        <ul>
        <li>Name: {{ $customer->fullName() }}</li>
        <li>Age: {{ $customer->age() }} ({{ $customer->dateOfBirth }})</li>
        <li>{!! $customer->location() !!}</li>
        <li>Bank Volume: {!! $customer->volumeEmojis() !!}</li>
        <li>Payment Count: {{ $customer->payments()->count() }}</li>
        <li>Identity Documents:
            @foreach ($customer->identityDocuments as $identityDocument)
                {{ $identityDocument->emoji() }}
                {{ $identityDocument->dateOfExpiry }}
            @endforeach
        </li>
        <li>Contacts:
            @foreach ($customer->contacts as $contact)
                {{ $contact->emoji() }}
                {{ $contact->handle }}
            @endforeach
        </li>
        </ul>

        <h2><anchor id="risk-assessments">Risk assessments</h2>
        {!! $customer->riskAssessmentEmojis() !!}
        <h3>{!! $customer->riskAssessments->where('type', 'Volume')->firstOrFail()->emoji() !!} Volume</h3>
        <ul><li>GBP Payment Volumes:
            Week:{{ $customer->volume('GBP', 7, true) }}
            Month:{{ $customer->volume('GBP', 30, true) }}
            Quarter:{{ $customer->volume('GBP', 90, true) }}
            Year:{{ $customer->volume('GBP', 365, true) }}
        </li><li>Action: {{ $customer->riskAssessments->where('type', 'Volume')->firstOrFail()->action }}
        </li><li>Notes: {{ $customer->riskAssessments->where('type', 'Volume')->firstOrFail()->notes }}
        </li></ul>
        <h3>{!! $customer->riskAssessments->where('type', 'Velocity')->firstOrFail()->emoji() !!} Velocity</h3>
        <ul><li>GBP Payment Velocities:
            Week:{{ $customer->velocity('GBP', 7) }}
            Month:{{ $customer->velocity('GBP', 30) }}
            Quarter:{{ $customer->velocity('GBP', 90) }}
            Year:{{ $customer->velocity('GBP', 365) }}
        </li><li>Action: {{ $customer->riskAssessments->where('type', 'Velocity')->firstOrFail()->action }}
        </li><li>Notes: {{ $customer->riskAssessments->where('type', 'Velocity')->firstOrFail()->notes }}
        </li></ul>

        <h2><anchor id="model-data">Model data</h2>
        {!! $modelTable !!}

        <span>
            <h3><anchor id="accounts">Accounts held ({{ $customer->accounts()->count() }})</h3>
            <table>
                @if ($customer->accounts->count())
                    @foreach ($customer->accounts as $account)
                        {!! $account->tableRow() !!}
                    @endforeach
                @else
                    No accounts held.
                @endif
            </table>
        </span>

        <span style="color: green;">
            <h3><anchor id="credits">Credits to this customer's accounts</h3>
            <table>
                @if ($customer->credits())
                    @foreach ($customer->credits()->sortByDesc('timestamp') as $credit)
                        {!! $credit->tableRow() !!}
                    @endforeach
                @else
                    No credits exist for this customer.
                @endif
            </table>
        </span>

        <span style="color: red;">
            <h3><anchor id="debits">Debits from this customer's accounts</h3>
            <table>
                @if ($customer->debits())
                    @foreach ($customer->debits()->sortByDesc('timestamp') as $debit)
                        {!! $debit->tableRow() !!}
                    @endforeach
                @else
                    No debits exist for this customer.
                @endif
            </table>
        </span>

    </body>
</html>