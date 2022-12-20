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
        <h3>Recent Payments</h3>
        <livewire:recent-payments-component />
        <livewire:held-payments-component />

        <table>
            <tr>
                <td style="width: 300px;"><h3>CUSTOMER/MARKET/OTHER</h3></td>
                <td style="width: 300px;"><h3>EXCHANGE</h3></td>
                <td style="width: 600px;"><h3>NETWORK</h3></td>
            </tr>
                <td>
                    🗿 <a href="/customers">Customers</a>
                    <livewire:customer-importer-component />

                    <p>
                    💹 Rates   
                    </p>                 

                    <p>
                    💷 <a href="currencies">Currencies</a>
                    <livewire:currency-populator-component />
                    </p>
                </td>
                <td>
                    🥸 <a href="/profiles">Profiles</a>

                    <p>
                    🪧 Offers
                    </p>

                    <p>
                    💱 Trades
                    </p>

                    <p>
                    💬 Messages
                    </p>

                    <p>
                    🧾 Invoices
                    </p>
                </td>
                <td>
                    <livewire:account-synchronizer-component />

                    <p>
                    <livewire:payment-synchronizer-component />
                    </p>
                </td>
            </tr>
        </table>
        PHP {{ PHP_VERSION }}

    </body>
</html>