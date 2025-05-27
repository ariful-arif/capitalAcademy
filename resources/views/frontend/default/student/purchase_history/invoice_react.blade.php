<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ get_phrase('Invoice') }} #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            margin: 40px;
            color: #000;
        }

        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-info {
            font-size: 14px;
        }

        .invoice-info h2 {
            margin: 0;
            font-size: 16px;
        }

        .invoice-info p {
            margin: 5px 0 0 0;
        }

        .logo {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
        }

        .logo-icon {
            font-size: 28px;
            color: #7f3fd6;
            vertical-align: middle;
        }

        .divider {
            border-top: 1px solid #ccc;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            font-weight: bold;
        }

        tr.item-row td {
            border-bottom: 1px dotted #aaa;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            padding-top: 20px;
            font-weight: bold;
        }

        .footer-table td:last-child {
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="top-section">
        <div class="invoice-info">
            <h2>{{ get_phrase('Invoice') }} #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</h2>
            <p>Date: {{ date('d-m-Y', strtotime($invoice->created_at)) }}</p>
        </div>
        <div class="logo">
            <span class="logo-icon">Capital Academy</span>
        </div>
    </div>

    <!-- Divider -->
    <div class="divider"></div>

    <!-- Invoice Table -->
    <table>
        <thead>
            <tr>
                <th>{{ get_phrase('Item') }}</th>
                <th>{{ get_phrase('Date of issue') }}</th>
                <th>{{ get_phrase('Payment Method') }}</th>
                <th>{{ get_phrase('Price') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr class="item-row">
                <td>{{ $invoice->title }}</td>
                <td>{{ date('d-m-Y', strtotime($invoice->created_at)) }}</td>
                <td>{{ ucfirst($invoice->payment_type) }}</td>
                <td>{{ currency($invoice->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <table class="footer-table">
        <tr>
            <td>{{ get_phrase('Billed to :') }} {{ $user->name }}</td>
            <td>{{ get_phrase('Total') }} {{ currency($invoice->amount, 2) }}</td>
        </tr>
    </table>

</body>
</html>
