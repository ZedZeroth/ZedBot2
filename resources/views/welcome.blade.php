<?php

?>
<!DOCTYPE html>
@livewireScripts
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    </head>

    <body>

        <h1>ZedBot v1.0.1</h1>

        <table>
            <tr>
                <td>

                    <h2>Models</h2>

                    <table>
                        <tr>
                            <td>
                                &bull; <a href="accounts">Accounts</a>
                            </td>
                            <td>
                                <livewire:account-synchronizer-component />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &bull; <a href="customers">Customers</a>
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td>
                                &bull; <a href="currencies">Currencies</a>
                            </td>
                            <td>
                                <livewire:currency-populator-component />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &bull; <a href="payments">Payments</a>
                            </td>
                            <td>
                                <livewire:payment-synchronizer-component />
                            </td>
                        </tr>
                    </table>
                </td>

                <td>
                    <h2>Rates</h2>
                    DISABLED
                    <!--
                        <livewire:rates-chart-component />
                    -->
                </td>
            </tr>
        </table>

    </body>
</html>