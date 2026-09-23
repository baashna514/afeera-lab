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
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        /* Header */
        .lab-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .lab-branding {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .lab-branding img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .lab-branding h1 {
            font-size: 22px;
            font-weight: 900;
            color: #1e3a8a;
            margin-bottom: 2px;
        }
        .lab-branding p {
            font-size: 11px;
            color: #475569;
            line-height: 1.4;
        }
        .lab-report-info {
            text-align: right;
        }
        .lab-report-info table {
            width: auto;
            margin-left: auto;
        }
        .lab-report-info td {
            font-size: 11px;
            padding: 1px 4px;
            color: #334155;
        }
        .lab-report-info .lbl {
            font-weight: 700;
            color: #1e3a8a;
            text-align: right;
            padding-right: 6px;
        }
        /* Patient Box */
        .patient-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
        }
        .patient-box table {
            width: auto;
        }
        .patient-box td {
            font-size: 11px;
            padding: 2px 6px;
            color: #334155;
        }
        .patient-box .lbl {
            font-weight: 700;
            color: #1e3a8a;
        }
        /* Test Section */
        .test-heading {
            background: #eef2ff;
            border-left: 4px solid #1e3a8a;
            padding: 8px 12px;
            margin: 14px 0 8px 0;
            font-size: 12px;
            font-weight: 700;
            color: #3730a3;
            text-transform: uppercase;
        }
        /* Results Table */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .results-table th {
            background: #1e3a8a;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 7px 10px;
            text-align: left;
            letter-spacing: 0.5px;
        }
        .results-table td {
            padding: 6px 10px;
            font-size: 11px;
            border-bottom: 1px solid #e2e8f0;
        }
        .results-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .param-name {
            font-weight: 600;
            color: #1e293b;
        }
        .result-val {
            font-weight: 800;
            color: #0f172a;
            font-size: 12px;
        }
        .unit { color: #64748b; }
        .range { color: #475569; font-size: 10px; }
        .flag-normal {
            color: #16a34a;
            font-weight: 700;
            font-size: 10px;
        }
        .flag-high {
            color: #dc2626;
            font-weight: 800;
            font-size: 10px;
        }
        .flag-low {
            color: #ea580c;
            font-weight: 800;
            font-size: 10px;
        }
        /* Notes */
        .notes-card {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 10px;
            color: #78350f;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        /* Footer */
        .report-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 24px;
            padding-top: 12px;
            border-top: 2px solid #1e3a8a;
        }
        .sign-col {
            font-size: 11px;
        }
        .sign-col.right {
            text-align: right;
        }
        .sign-title {
            font-weight: 800;
            color: #1e3a8a;
            font-size: 13px;
        }
        .sign-subtitle {
            color: #475569;
            font-size: 10px;
            line-height: 1.5;
        }
        @media print {
            body { padding: 0; background: #fff; }
            .no-print-bar { display: none !important; }
            .report-page {
                box-shadow: none;
                margin: 0;
                padding: 8mm 12mm;
                width: 100%;
                min-height: 100vh;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    @php
        $setting = \App\Models\CompanySetting::where('company_id', $company->id)->first();
    @endphp

    <div class="no-print-bar">
        <a href="{{ route('admin.reviews.show', $booking) }}" class="btn-back">&larr; Return to Review</a>
        <button onclick="window.print()" class="btn-print"><i class="fa-solid fa-print"></i> Print Clinical Report</button>
    </div>

    <div class="report-page">
        <div>
            <!-- Hospital Header -->
            @if(!$setting || $setting->show_header)
            <div class="lab-header">
                <div class="lab-branding">
                    @if((!$setting || $setting->show_header_logo) && $setting && $setting->company_logo)
                        <img src="{{ asset('storage/' . $setting->company_logo) }}" alt="Logo">
                    @endif
                    <div>
                        @if(!$setting || $setting->show_header_company_name)
                            <h1>{{ $setting->company_name ?? $company->name ?? 'Clinical Pathology Laboratory' }}</h1>
                        @endif
                        @if(!$setting || $setting->show_header_address)
                            <p>{{ $setting->company_address ?? $company->address ?? 'Main Road, Hospital Campus' }}</p>
                        @endif
                        @if((!$setting || $setting->show_header_phone) || ((!$setting || $setting->show_header_email) && $setting && $setting->company_email))
                        <p>
                            @if(!$setting || $setting->show_header_phone)
                                <span>Phone: {{ $setting->company_phone ?? $company->phone ?? '0300-0000000' }}</span>
                            @endif
                            @if((!$setting || $setting->show_header_email) && $setting && $setting->company_email)
                                <span> | Email: {{ $setting->company_email }}</span>
                            @endif
                        </p>
                        @endif
                    </div>
                </div>
                <div class="lab-report-info">
                    <div style="font-size: 16px; font-weight: 900; margin-bottom: 6px; text-transform: uppercase; color:#0f172a;">Laboratory Report</div>
                    <table>
                        @if(!$setting || $setting->show_header_lab_no)
                            <tr><td class="lbl">Lab No.</td><td>: {{ $booking->lab_number ?: $booking->invoice_number }}</td></tr>
                        @endif
                        @if(!$setting || $setting->show_header_report_date)
                            <tr><td class="lbl">Report Date</td><td>: {{ now()->format('d-M-Y h:i A') }}</td></tr>
                        @endif
                        @if(!$setting || $setting->show_header_sample_date)
                            <tr><td class="lbl">Sample Collected</td><td>: {{ \Carbon\Carbon::parse($booking->items->first()?->collected_at ?? $booking->created_at)->format('d-M-Y h:i A') }}</td></tr>
                        @endif
                        @if(!$setting || $setting->show_header_report_status)
                            <tr><td class="lbl">Report Status</td><td>: {{ ucfirst($booking->status === 'completed' ? 'Final' : $booking->status) }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>
            @endif

            <!-- Patient Information Box -->
            @if(!$setting || $setting->show_patient_info)
            <div class="patient-box">
                <div>
                    <table>
                        @if(!$setting || $setting->show_patient_name)
                            <tr><td class="lbl">Patient Name</td><td>: <strong>{{ $booking->patient->name }}</strong></td></tr>
                        @endif
                        @if(!$setting || $setting->show_patient_age_gender)
                            <tr><td class="lbl">Age / Gender</td><td>: {{ $booking->patient->age ? $booking->patient->age.' Years' : '--' }} / {{ ucfirst($booking->patient->gender ?? 'N/A') }}</td></tr>
                        @endif
                        @if(!$setting || $setting->show_patient_id)
                            <tr><td class="lbl">Patient ID</td><td>: PID-{{ str_pad($booking->patient->id, 6, '0', STR_PAD_LEFT) }}</td></tr>
                        @endif
                        @if(!$setting || $setting->show_patient_referred_by)
                            <tr><td class="lbl">Referred By</td><td>: {{ $booking->referred_by ?: ($setting->default_referred_by ?? 'Dr. Consultant Physician') }}</td></tr>
                        @endif
                        @if(!$setting || $setting->show_patient_contact)
                            <tr><td class="lbl">Contact</td><td>: {{ $booking->patient->phone ?? '--' }}</td></tr>
                        @endif
                    </table>
                </div>
                <div>
                    <table>
                        @if(!$setting || $setting->show_patient_collection_type)
                            <tr><td class="lbl">Collection Type</td><td>: {{ $booking->collection_type ?: ($setting->default_collection_type ?? ($booking->items->first()?->sample_type ?? 'Venous Blood')) }}</td></tr>
                        @endif
                        @if(!$setting || $setting->show_patient_fasting)
                            <tr><td class="lbl">Fasting</td><td>: {{ $booking->fasting ?: ($setting->default_fasting ?? 'No') }}</td></tr>
                        @endif
                        @if(!$setting || $setting->show_patient_clinical_info)
                            <tr><td class="lbl">Clinical Info</td><td>: {{ $booking->clinical_info ?: ($setting->default_clinical_info ?? 'Routine Check-up') }}</td></tr>
                        @endif
                        @if(!$setting || $setting->show_patient_barcode)
                            <tr><td class="lbl">Barcode No</td><td>: {{ $booking->items->first()->barcode ?? '--' }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>
            @endif

            <!-- Test Results Table -->
            @foreach($booking->items as $item)
                <div class="test-heading">
                    {{ $item->labTest->category }}
                    <div style="font-size: 16px; margin-top: 4px; font-weight: 900; color: #1e3a8a;">{{ $item->labTest->name }}</div>
                </div>

                @if($item->result && $item->result->parameters->count() > 0)
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Test Name</th>
                                <th style="width: 20%;">Result</th>
                                <th style="width: 15%;">Unit</th>
                                <th style="width: 20%;">Reference Range</th>
                                <th style="width: 10%;">Flag</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->result->parameters as $param)
                                <tr>
                                    <td class="param-name">{{ $param->parameter_name }}</td>
                                    <td class="result-val">{{ $param->result_value ?? '--' }}</td>
                                    <td class="unit">{{ $param->unit ?? '--' }}</td>
                                    <td class="range">{{ $param->normal_range_text ?? '--' }}</td>
                                    <td class="{{ $param->flag_class }}">
                                        @if($param->flag === 'Low')
                                            &darr; Low
                                        @elseif($param->flag === 'High')
                                            &uarr; High
                                        @elseif($param->flag === 'Normal')
                                            Normal
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <!-- Dynamic Notes for this test if any -->
                @if($item->labTest->notes->count() > 0)
                    <div class="notes-card">
                        <strong>Note:</strong> Results are to be correlated clinically.<br>
                        @foreach($item->labTest->notes as $note)
                            &bull; {{ $note->note_text }}<br>
                        @endforeach
                    </div>
                @endif
            @endforeach
            
            <div style="text-align: center; margin-top: 20px; font-size: 11px; font-style: italic; color: #475569;">* End of Report *</div>
        </div>

        <!-- Signatures & Verification Footer -->
        @if(!$setting || $setting->show_footer)
        <div>
            <div class="report-footer">
                <div class="sign-col" style="text-align: left; width: 120px;">
                    @if(!$setting || $setting->show_footer_qr)
                        <!-- QR Code Placeholder -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('admin.reviews.show', $booking)) }}" alt="QR Code" style="width: 60px; height: 60px;">
                    @endif
                </div>

                <div class="sign-col right">
                    @if($setting && $setting->doctor_name)
                        @if(!$setting || $setting->show_footer_signature)
                            <!-- Signature placeholder image could go here -->
                            <div style="height: 40px; margin-bottom: 5px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/f/f6/Signature_placeholder.svg" style="height: 40px; opacity: 0.6;" alt="Signature">
                            </div>
                        @endif
                        @if(!$setting || $setting->show_footer_doctor_name)
                            <div class="sign-title">{{ $setting->doctor_name }}</div>
                        @endif
                        <div class="sign-subtitle">
                            @if((!$setting || $setting->show_footer_doctor_degree) && $setting->doctor_degree) {{ $setting->doctor_degree }} <br> @endif
                            @if((!$setting || $setting->show_footer_doctor_reg) && $setting->doctor_reg_no) {{ $setting->doctor_reg_no }} @endif
                        </div>
                    @else
                        @if(!$setting || $setting->show_footer_signature)
                            <div style="height: 40px; border-bottom: 1px solid #cbd5e1; margin-bottom: 5px; width: 150px; display: inline-block;"></div>
                        @endif
                        @if(!$setting || $setting->show_footer_doctor_name)
                            <div class="sign-title">Authorized Signatory</div>
                        @endif
                        <div class="sign-subtitle">Consultant Pathologist</div>
                    @endif
                </div>
            </div>
            @if(!$setting || $setting->show_footer_disclaimer)
                <p style="text-align: center; font-size: 9px; color: #64748b; margin-top: 15px; border-top: 1px solid #cbd5e1; padding-top: 5px;">
                    This is a computer generated report. No signature is required.
                </p>
            @endif
        </div>
        @endif
    </div>

</body>
</html>
