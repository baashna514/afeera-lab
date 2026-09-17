<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Barcode - {{ $item->barcode ?? 'SMP-'.$item->id }}</title>
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=libre-barcode-39-text:400|figtree:400,600,700&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Figtree', sans-serif;
        }
        body {
            background-color: #f1f5f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .toolbar {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .btn {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
        }
        .btn-secondary {
            background-color: #64748b;
        }
        .label-card {
            width: 75mm;
            height: 45mm;
            background: white;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 3mm 4mm;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .lab-name {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            border-bottom: 1px solid #0f172a;
            padding-bottom: 1.5mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .patient-info {
            font-size: 9px;
            color: #1e293b;
            line-height: 1.35;
        }
        .patient-name {
            font-size: 11px;
            font-weight: 800;
            color: #000;
        }
        .barcode-section {
            text-align: center;
            margin: 1mm 0;
        }
        .barcode-font {
            font-family: 'Libre Barcode 39 Text', cursive;
            font-size: 28px;
            letter-spacing: 2px;
            line-height: 1;
        }
        .meta-footer {
            display: flex;
            justify-content: space-between;
            font-size: 8px;
            color: #475569;
            font-weight: 600;
            border-top: 1px solid #e2e8f0;
            padding-top: 1mm;
        }
        .tube-tag {
            background: #0f172a;
            color: #fff;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: 700;
        }

        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0;
                display: block;
            }
            .toolbar {
                display: none;
            }
            .label-card {
                border: none;
                box-shadow: none;
                page-break-inside: avoid;
                margin: 0;
            }
            @page {
                size: 75mm 45mm;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <button onclick="window.print()" class="btn">🖨️ Print Label</button>
        <button onclick="window.close()" class="btn btn-secondary">Close Window</button>
    </div>

    <div class="label-card">
        <div class="lab-name">
            <span>{{ auth()->user()->company->name ?? 'MediLab Diagnostics' }}</span>
            <span class="tube-tag">{{ $item->sample_type ?? 'EDTA Blood' }}</span>
        </div>

        <div class="patient-info">
            <div class="patient-name">{{ $item->booking->patient->name }}</div>
            <div>
                Age/Gen: {{ $item->booking->patient->age ?? '--' }}Y / {{ strtoupper(substr($item->booking->patient->gender ?? 'U', 0, 1)) }}
                &bull; MRN: #P-{{ str_pad($item->booking->patient->id, 5, '0', STR_PAD_LEFT) }}
            </div>
            <div>Test: <strong>{{ $item->labTest->name }}</strong></div>
        </div>

        <div class="barcode-section">
            <div class="barcode-font">*{{ $item->barcode ?? 'SMP'.$item->id }}*</div>
        </div>

        <div class="meta-footer">
            <span>Inv: {{ $item->booking->invoice_number }}</span>
            <span>{{ now()->format('d/m/y H:i') }}</span>
        </div>
    </div>

</body>
</html>
