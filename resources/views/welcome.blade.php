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
        <table>
            <tr>
                <td>

                    <h3>Models</h3>

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
                                <livewire:customer-importer-component />
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
                    <h3>Rates</h3>
                    DISABLED
                    <!--livewire:rates-chart-component /-->
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <h3>Recent Payments</h3>
                    <livewire:recent-payments-component />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <livewire:held-payments-component />
                </td>
            </tr>
        </table>

    </body>
</html>