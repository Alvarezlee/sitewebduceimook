<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1f2937; }
        h1 { color: #1d4ed8; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        .totals { margin-top: 20px; text-align: right; }
    </style>
</head>
<body>
    <h1>{{ config('app.name') }} — Facture {{ $order->order_number }}</h1>
    <p>
        Client : {{ $order->user->full_name }} ({{ $order->user->email }})<br>
        Date : {{ $order->created_at->translatedFormat('d/m/Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format((float) $item->unit_price, 0, ',', ' ') }} XAF</td>
                    <td>{{ number_format((float) $item->total_price, 0, ',', ' ') }} XAF</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Sous-total : {{ number_format((float) $order->subtotal, 0, ',', ' ') }} XAF</p>
        <p>Remise : {{ number_format((float) $order->discount, 0, ',', ' ') }} XAF</p>
        <p><strong>Total : {{ number_format((float) $order->total, 0, ',', ' ') }} XAF</strong></p>
    </div>
</body>
</html>
