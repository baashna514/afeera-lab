<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic Report - {{ $booking->patient->name }} - {{ $booking->invoice_number }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 24px 0;
        }
        .report-page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            padding: 12mm 15mm;
            border-radius: 4px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .no-print-bar {
            width: 210mm;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: #4f46e5;
            color: #fff;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-back {
            background: #64748b;
            color: #fff;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
        }

        /* Hospital Header */
        .lab-header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .lab-branding h1 {
            font-size: 22px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            color: #0f172a;
        }
        .lab-branding p {
            font-size: 11px;
            color: #475569;
            margin-top: 2px;
        }
        .lab-badge {
            text-align: right;
            font-size: 11px;
            font-weight: 700;
            color: #4f46e5;
            background: #eef2ff;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #c7d2fe;
        }

        /* Patient Information Box */
        .patient-box {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 20px;
            display: grid;
            grid-template-cols: repeat(4, 1fr);
            gap: 10px;
            background: #f8fafc;
        }
        .info-item {
            font-size: 11px;
        }
        .info-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
        }
        .info-val {
            font-weight: 800;
            color: #0f172a;
            margin-top: 1px;
        }

        /* Test Section */
        .test-heading {
            background: #0f172a;
            color: #fff;
            padding: 6px 10px;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 4px 4px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
        }
        .test-heading .category-tag {
            font-size: 10px;
            background: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 3px;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 12px;
            border: 1px solid #e2e8f0;
            border-top: none;
        }
        .results-table th {
            background: #f1f5f9;
            padding: 6px 10px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 10px;
            color: #334155;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }
        .results-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #0f172a;
        }
        .results-table tr:last-child td {
            border-bottom: none;
        }
        .param-name {
            font-weight: 700;
        }
        .result-val {
            font-weight: 800;
            font-size: 12px;
            color: #000;
        }
        .unit, .range {
            color: #475569;
            font-size: 10.5px;
        }

        /* Custom Notes / Remarks */
        .notes-card {
            background: #fffbeb;
            border: 1px dashed #fcd34d;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 8px;
            font-size: 10px;
            color: #78350f;
            line-height: 1.4;
        }
        .notes-card strong {
            font-weight: 800;
            text-transform: uppercase;
            font-size: 9.5px;
            display: block;
            margin-bottom: 4px;
        }

        /* Signatures Footer */
        .report-footer {
            margin-top: 30px;
            padding-top: 14px;
            border-top: 1px solid #cbd5e1;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .sign-col {
            text-align: center;
            width: 50mm;
        }
        .sign-line {
            border-bottom: 1px solid #0f172a;
            height: 25px;
            margin-bottom: 6px;
        }
        .sign-title {
            font-size: 10.5px;
            font-weight: 800;
            color: #0f172a;
        }
        .sign-subtitle {
            font-size: 9px;
            color: #64748b;
        }
        .stamp-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            color: #059669;
            font-weight: 800;
            background: #ecfdf5;
            padding: 3px 8px;
            border-radius: 4px;
            border: 1px solid #a7f3d0;
            margin-bottom: 6px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .no-print-bar {
                display: none;
            }
            .report-page {
                box-shadow: none;
                border: none;
                padding: 10mm;
                width: 100%;
                min-height: auto;
            }
            @page {
                size: A4 portrait;
                margin: 8mm;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <a href="{{ route('admin.reviews.show', $booking) }}" class="btn-back">&larr; Return to Review</a>
        <button onclick="window.print()" class="btn-print"><i class="fa-solid fa-print"></i> Print Clinical Report</button>
    </div>

    <div class="report-page">
        <div>
            <!-- Hospital Header -->
            <div class="lab-header">
                <div class="lab-branding">
                    <h1>{{ $company->name ?? 'Clinical Pathology Laboratory' }}</h1>
                    <p>{{ $company->address ?? 'Main Road, Hospital Campus' }} &bull; Ph: {{ $company->phone ?? '0300-0000000' }}</p>
                </div>
                <div class="lab-badge">
                    <i class="fa-solid fa-certificate mr-1"></i> ISO Verified LIMS Report
                </div>
            </div>

            <!-- Patient Information Box -->
            <div class="patient-box">
                <div class="info-item">
                    <div class="info-label">Patient Name:</div>
                    <div class="info-val">{{ $booking->patient->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Age / Gender:</div>
                    <div class="info-val">{{ $booking->patient->age ? $booking->patient->age.' Yrs' : '--' }} / {{ ucfirst($booking->patient->gender ?? 'N/A') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Reg / Invoice #:</div>
                    <div class="info-val">{{ $booking->invoice_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Booking Date:</div>
                    <div class="info-val">{{ $booking->created_at->format('d/m/Y h:i A') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Patient ID / MRN:</div>
                    <div class="info-val">#P-{{ str_pad($booking->patient->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Contact / Phone:</div>
                    <div class="info-val">{{ $booking->patient->phone ?? '--' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Referred By:</div>
                    <div class="info-val">Consultant Physician</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Report Printed:</div>
                    <div class="info-val">{{ now()->format('d/m/Y h:i A') }}</div>
                </div>
            </div>

            <!-- Test Results Table -->
            @foreach($booking->items as $item)
                <div class="test-heading">
                    <span>{{ $item->labTest->name }}</span>
                    <span class="category-tag">{{ $item->labTest->category }}</span>
                </div>

                @if($item->result && $item->result->parameters->count() > 0)
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Investigation / Parameter</th>
                                <th style="width: 25%;">Observed Result</th>
                                <th style="width: 15%;">Unit</th>
                                <th style="width: 25%;">Biological Reference Interval</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->result->parameters as $param)
                                <tr>
                                    <td class="param-name">{{ $param->parameter_name }}</td>
                                    <td class="result-val">{{ $param->result_value ?? '--' }}</td>
                                    <td class="unit">{{ $param->unit ?? '--' }}</td>
                                    <td class="range">{{ $param->normal_range_text ?? '--' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <!-- Dynamic Notes for this test if any -->
                @if($item->labTest->notes->count() > 0)
                    <div class="notes-card">
                        <strong>Test Notes / Clinical Method:</strong>
                        <ul style="padding-left: 14px;">
                            @foreach($item->labTest->notes as $note)
                                <li>{{ $note->note_text }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Signatures & Verification Footer -->
        <div>
            <div class="report-footer">
                <div class="sign-col">
                    <div class="sign-line"></div>
                    <div class="sign-title">Medical Lab Technologist</div>
                    <div class="sign-subtitle">B.Sc. MLT, Registered</div>
                </div>

                <div style="text-align: center;">
                    <div class="stamp-badge">
                        <i class="fa-solid fa-shield-check"></i> DIGITALLY VERIFIED
                    </div>
                    <p style="font-size: 8.5px; color: #64748b;">Electronically authenticated without physical signature</p>
                </div>

                <div class="sign-col">
                    <div class="sign-line"></div>
                    <div class="sign-title">Dr. Consultant Pathologist</div>
                    <div class="sign-subtitle">M.B.B.S., M.Phil. / F.C.P.S. (Hematology)</div>
                </div>
            </div>
            <p style="text-align: center; font-size: 8px; color: #94a3b8; margin-top: 8px;">
                * This report is subject to clinical correlation. Not valid for medico-legal purposes. &bull; Generated by MediLab LIMS Pro
            </p>
        </div>
    </div>

</body>
</html>
