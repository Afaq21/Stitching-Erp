<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $booking->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #413781;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .company-tagline {
            font-size: 14px;
            color: #666;
        }
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .invoice-left, .invoice-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .invoice-right {
            text-align: right;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .info-block {
            margin-bottom: 20px;
        }
        .info-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .info-content {
            color: #666;
            line-height: 1.4;
        }
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .services-table th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .services-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .services-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .totals {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .total-row.final {
            border-bottom: 2px solid #667eea;
            font-weight: bold;
            font-size: 18px;
            color: #667eea;
        }
        .payment-info {
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d4edda; color: #155724; }
        .status-in_progress { background: #cce7ff; color: #004085; }
        .status-ready { background: #d1ecf1; color: #0c5460; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">Stiching Erp</div>
        <div class="company-tagline">Professional Tailoring Services</div>
    </div>

    <!-- Invoice Info -->
    <div class="invoice-info">
        <div class="invoice-left">
            <div class="invoice-title">INVOICE</div>
            <div class="info-block">
                <div class="info-title">Invoice Number:</div>
                <div class="info-content">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="info-block">
                <div class="info-title">Invoice Date:</div>
                <div class="info-content">{{ $booking->created_at->format('d M Y') }}</div>
            </div>
            <div class="info-block">
                <div class="info-title">Status:</div>
                <div class="info-content">
                    <span class="status-badge status-{{ $booking->status }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="invoice-right">
            <div class="info-block">
                <div class="info-title">Bill To:</div>
                <div class="info-content">
                    <strong>{{ $booking->customer->name }}</strong><br>
                    {{ $booking->customer->phone }}<br>
                    @if($booking->customer->address)
                        {{ $booking->customer->address }}
                    @endif
                </div>
            </div>
            <div class="info-block">
                <div class="info-title">Booking Date:</div>
                <div class="info-content">{{ $booking->booking_date->format('d M Y') }}</div>
            </div>
            <div class="info-block">
                <div class="info-title">Delivery Date:</div>
                <div class="info-content">{{ $booking->delivery_date->format('d M Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Services Table -->
    <table class="services-table">
        <thead>
            <tr>
                <th>Service</th>
                <th>Design</th>
                <th>Category</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->bookingItems as $item)
            <tr>
                <td>
                    <strong>{{ $item->service->name }}</strong>
                    @if($item->service->description)
                        <br><small style="color: #666;">{{ $item->service->description }}</small>
                    @endif
                </td>
                <td>
                    @if($item->designCatalog)
                        {{ $item->designCatalog->title }}
                        @if($item->designCatalog->price_adjustment > 0)
                            <br><small style="color: #28a745;">+Rs{{ number_format($item->designCatalog->price_adjustment, 0) }}</small>
                        @endif
                    @else
                        <span style="color: #666;">No Design Selected</span>
                    @endif
                </td>
                <td>{{ ucfirst($item->service->service_category) }}</td>
                <td style="text-align: right;">Rs{{ number_format($item->total_price, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>Rs{{ number_format($booking->total_amount, 0) }}</span>
        </div>
        @if($booking->advance_amount > 0)
        <div class="total-row">
            <span>Advance Paid:</span>
            <span>Rs{{ number_format($booking->advance_amount, 0) }}</span>
        </div>
        @endif
        <div class="total-row final">
            <span>
                @if($booking->remaining_amount > 0)
                    Amount Due:
                @else
                    Total Paid:
                @endif
            </span>
            <span>Rs{{ number_format($booking->remaining_amount > 0 ? $booking->remaining_amount : $booking->total_amount, 0) }}</span>
        </div>
    </div>

    <div style="clear: both;"></div>

    <!-- Payment Info -->
    @if($booking->remaining_amount > 0)
    <div class="payment-info">
        <div class="info-title">Payment Information</div>
        <div class="info-content">
            <strong>Remaining Amount:</strong> Rs{{ number_format($booking->remaining_amount, 0) }}<br>
            <strong>Payment Due:</strong> On Delivery<br>
            @if($booking->notes)
                <strong>Notes:</strong> {{ $booking->notes }}
            @endif
        </div>
    </div>
    @endif

    @if($booking->notes && $booking->remaining_amount <= 0)
    <div class="payment-info">
        <div class="info-title">Notes</div>
        <div class="info-content">{{ $booking->notes }}</div>
    </div>
    @endif

</body>
</html>
