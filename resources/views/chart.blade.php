<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/payments">↩️</a>

        <h1>Chart</h1>

        {!! $chart->container() !!}
        {!! $chart->script() !!}

    </body>
</html>