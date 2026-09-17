<x-dashboard-layout>
    <x-slot name="title">Sample Collection & Barcodes</x-slot>
    <x-slot name="header">Sample Collection & Barcode Tagging</x-slot>

    <!-- Header & Statistics -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Phlebotomy & Sample Collection</h2>
            <p class="text-slate-500 text-sm">Track patient specimens, print vial barcodes, and mark samples collected.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.bookings.create') }}" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-sm">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Register Patient</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Specimens</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $samples->total() }}</h3>
                <p class="text-xs text-slate-400 mt-1">All Recorded Samples</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-vials"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pending Collection</p>
                <h3 class="text-3xl font-extrabold text-amber-600">{{ $pendingCount }}</h3>
                <p class="text-xs text-amber-600 font-medium mt-1"><i class="fa-solid fa-clock mr-1"></i> Awaiting Phlebotomy</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-vial"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Collected Samples</p>
                <h3 class="text-3xl font-extrabold text-emerald-600">{{ $collectedCount }}</h3>
                <p class="text-xs text-emerald-600 font-medium mt-1"><i class="fa-solid fa-check-double mr-1"></i> In Processing</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.samples.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-80">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search barcode, patient, invoice..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Only</option>
                    <option value="collected" {{ request('status') === 'collected' ? 'selected' : '' }}>Collected Only</option>
                </select>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors">
                    Filter
                </button>
            </div>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.samples.index') }}" class="text-xs text-slate-500 hover:text-indigo-600 font-medium">
                    Clear Filters
                </a>
            @endif
        </form>
    </div>

    <!-- Samples List Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4">Sample Barcode</th>
                        <th class="px-6 py-4">Patient Information</th>
                        <th class="px-6 py-4">Test & Specimen Tube</th>
                        <th class="px-6 py-4">Collection Status</th>
                        <th class="px-6 py-4">Booking Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($samples as $sample)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-200 inline-block text-xs">
                                    <i class="fa-solid fa-barcode mr-1"></i> {{ $sample->barcode ?? 'SMP-'.str_pad($sample->id, 5, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-1">Inv: {{ $sample->booking->invoice_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $sample->booking->patient->name }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ $sample->booking->patient->age ? $sample->booking->patient->age . ' Yrs' : '' }} 
                                    {{ $sample->booking->patient->gender ? '• ' . ucfirst($sample->booking->patient->gender) : '' }}
                                    {{ $sample->booking->patient->phone ? '• ' . $sample->booking->patient->phone : '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $sample->labTest->name }}</div>
                                <span class="inline-flex items-center text-[11px] px-2 py-0.5 rounded font-medium bg-slate-100 text-slate-600 mt-1">
                                    <i class="fa-solid fa-vial mr-1 text-slate-400"></i> {{ $sample->sample_type ?? 'Blood / Serum' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($sample->sample_status === 'collected')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check mr-1.5 text-emerald-600"></i> Collected
                                    </span>
                                    <div class="text-[11px] text-slate-400 mt-1">
                                        {{ $sample->collected_at ? \Carbon\Carbon::parse($sample->collected_at)->format('d M, h:i A') : 'Recorded' }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-clock mr-1.5 text-amber-600"></i> Pending Collection
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $sample->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.samples.barcode', $sample) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors" title="Print Barcode Label">
                                    <i class="fa-solid fa-barcode mr-1"></i> Barcode
                                </a>

                                @if($sample->sample_status !== 'collected')
                                    <form method="POST" action="{{ route('admin.samples.collect', $sample) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                                            <i class="fa-solid fa-check mr-1"></i> Collect
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.results.edit', $sample->booking) }}" class="inline-flex items-center px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition-colors" title="Enter Results">
                                    <i class="fa-solid fa-file-waveform mr-1"></i> Results
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-vial-circle-check text-4xl mb-3 text-slate-300"></i>
                                <p class="text-sm font-medium">No samples found.</p>
                                <p class="text-xs text-slate-400 mt-1">Book tests through Patient Registration to generate sample collection entries.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($samples->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $samples->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
