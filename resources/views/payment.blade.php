<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/payments">↩️</a>

        <h1 style="word-wrap: break-word;">Payment: {{ $payment->identifier }}</h1>

        <ul>
            <li><a href="#details">Details</a></li>
            <li><a href="#model-data">Model data</a></li>
        </ul>

        <h2><anchor id="details">Details</h2>
        {!! $paymentTable !!}

        <h2><anchor id="model-data">Model data</h2>
        {!! $modelTable !!}

    </body>
</html>