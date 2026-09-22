<x-dashboard-layout>
    <x-slot name="title">Pathologist Review - {{ $booking->patient->name }}</x-slot>
    <x-slot name="header">Medical Review & Pathologist Verification</x-slot>

    <!-- Header Actions -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center space-x-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors mb-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Review Queue</span>
            </a>
            <h2 class="text-xl font-bold text-slate-800">
                Patient Report Verification: {{ $booking->patient->name }}
            </h2>
            <p class="text-slate-500 text-sm">Invoice #{{ $booking->invoice_number }} &bull; Date: {{ $booking->created_at->format('d M, Y h:i A') }}</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reviews.report', $booking) }}" target="_blank" class="inline-flex items-center space-x-2 bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm">
                <i class="fa-solid fa-print"></i>
                <span>Print Diagnostic Report</span>
            </a>
        </div>
    </div>

    <!-- Patient Summary Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm mb-8">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500"><i class="fa-solid fa-id-card text-indigo-500 mr-2"></i> Patient Information</h3>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">MRN: #P-{{ str_pad($booking->patient->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-sm">
            <div>
                <p class="text-xs text-slate-400 font-medium">Patient Name</p>
                <p class="font-bold text-slate-800 mt-0.5">{{ $booking->patient->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-medium">Age / Gender</p>
                <p class="font-bold text-slate-800 mt-0.5">{{ $booking->patient->age ?? 'N/A' }} Yrs / {{ ucfirst($booking->patient->gender ?? 'Unspecified') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-medium">Phone</p>
                <p class="font-bold text-slate-800 mt-0.5">{{ $booking->patient->phone ?? 'Not Provided' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-medium">Total Billed / Paid</p>
                <p class="font-bold text-emerald-600 mt-0.5">Rs. {{ number_format($booking->paid_amount, 2) }} / {{ number_format($booking->total_amount, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Test Results Review Section -->
    <div class="space-y-8 mb-8">
        @foreach($booking->items as $item)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded border border-indigo-200">{{ $item->labTest->category }}</span>
                        <h4 class="text-lg font-extrabold text-slate-800 mt-1">{{ $item->labTest->name }}</h4>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-slate-500 font-mono">Barcode: {{ $item->barcode ?? 'N/A' }}</span>
                        @if($item->result && $item->result->status === 'verified')
                            <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800">
                                <i class="fa-solid fa-circle-check mr-1.5"></i> Verified
                            </span>
                        @else
                            <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800">
                                <i class="fa-solid fa-clock mr-1.5"></i> Pending Approval
                            </span>
                        @endif
                    </div>
                </div>

                @if($item->result && $item->result->parameters->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                                    <th class="px-6 py-3">Parameter / Sub-Test</th>
                                    <th class="px-6 py-3">Observed Result</th>
                                    <th class="px-6 py-3">Unit</th>
                                    <th class="px-6 py-3">Biological Normal Range</th>
                                    <th class="px-6 py-3">Flag</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($item->result->parameters as $param)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-6 py-3.5 font-bold text-slate-800">
                                            {{ $param->parameter_name }}
                                        </td>
                                        <td class="px-6 py-3.5 font-mono font-extrabold text-base {{ empty($param->result_value) ? 'text-slate-400 italic' : 'text-indigo-900' }}">
                                            {{ $param->result_value ?? 'No Result Entered' }}
                                        </td>
                                        <td class="px-6 py-3.5 text-slate-600 text-xs font-medium">
                                            {{ $param->unit ?? '--' }}
                                        </td>
                                        <td class="px-6 py-3.5 text-slate-600 text-xs font-medium">
                                            {{ $param->normal_range_text ?? '--' }}
                                        </td>
                                        <td class="px-6 py-3.5">
                                            @if($param->flag === 'High')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-800">
                                                    &uarr; High
                                                </span>
                                            @elseif($param->flag === 'Low')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-800">
                                                    &darr; Low
                                                </span>
                                            @elseif($param->flag === 'Normal')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800">
                                                    Normal
                                                </span>
                                            @else
                                                <span class="text-xs text-slate-400">--</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-slate-400 text-sm">
                        No parameters defined for this test.
                    </div>
                @endif

                <!-- Dynamic Report Notes if any -->
                @if($item->labTest->notes->count() > 0)
                    <div class="p-5 bg-amber-50/40 border-t border-amber-100">
                        <p class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-circle-info mr-1 text-amber-600"></i> Report Description / Standard Notes:
                        </p>
                        <ul class="space-y-1 text-xs text-amber-900 list-disc list-inside">
                            @foreach($item->labTest->notes as $note)
                                <li>{{ $note->note_text }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Pathologist Approval Form -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
        <div class="flex items-center space-x-3 mb-4 pb-3 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-stamp"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-800">Pathologist Digital Sign & Verification</h3>
                <p class="text-xs text-slate-500">Sign off this patient's test report to enable final patient report delivery</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.reviews.approve', $booking) }}" class="space-y-4">
            @csrf
            <div>
                <label for="remarks" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Doctor / Pathologist Remarks & Interpretation
                </label>
                <textarea name="remarks" id="remarks" rows="2" placeholder="e.g. Clinically correlated. Advised follow-up or correlation with clinical symptoms."
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white transition-all"></textarea>
            </div>

            <div class="flex items-center justify-between pt-2">
                <div class="text-xs text-slate-500">
                    Signing as: <strong class="text-slate-800">{{ auth()->user()->name }}</strong> ({{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }})
                </div>
                <button type="submit" class="inline-flex items-center space-x-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl transition-all shadow-md shadow-purple-600/20">
                    <i class="fa-solid fa-stamp"></i>
                    <span>Verify & Approve Report</span>
                </button>
            </div>
        </form>
    </div>
</x-dashboard-layout>
