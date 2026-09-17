<x-dashboard-layout>
    <x-slot name="title">Pathologist Review & Approval</x-slot>
    <x-slot name="header">Pathologist Review & Verification</x-slot>

    <!-- Header & Statistics -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Medical Verification & Final Reports</h2>
            <p class="text-slate-500 text-sm">Review entered laboratory results, digitally verify, and generate patient diagnostic reports.</p>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Bookings</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $bookings->total() }}</h3>
                <p class="text-xs text-slate-400 mt-1">Patient Test Orders</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-file-medical"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pending Approval</p>
                <h3 class="text-3xl font-extrabold text-amber-600">{{ $pendingReviewsCount }}</h3>
                <p class="text-xs text-amber-600 font-medium mt-1"><i class="fa-solid fa-clock mr-1"></i> Awaiting Doctor Review</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Verified Reports</p>
                <h3 class="text-3xl font-extrabold text-emerald-600">{{ $verifiedCount }}</h3>
                <p class="text-xs text-emerald-600 font-medium mt-1"><i class="fa-solid fa-stamp mr-1"></i> Ready for Delivery</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-certificate"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-80">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient name, invoice..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Results</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Results Entered / Completed</option>
                </select>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors">
                    Filter
                </button>
            </div>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.reviews.index') }}" class="text-xs text-slate-500 hover:text-indigo-600 font-medium">
                    Clear Filters
                </a>
            @endif
        </form>
    </div>

    <!-- Review List Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4">Invoice #</th>
                        <th class="px-6 py-4">Patient Information</th>
                        <th class="px-6 py-4">Tests Included</th>
                        <th class="px-6 py-4">Review & Verification Status</th>
                        <th class="px-6 py-4">Booking Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($bookings as $booking)
                        @php
                            $allVerified = $booking->items->every(fn($i) => $i->result && $i->result->status === 'verified');
                            $hasResults = $booking->status === 'completed';
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono font-semibold text-indigo-700">
                                {{ $booking->invoice_number }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $booking->patient->name }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ $booking->patient->age ? $booking->patient->age . ' Yrs' : '' }} 
                                    {{ $booking->patient->gender ? '• ' . ucfirst($booking->patient->gender) : '' }}
                                    {{ $booking->patient->phone ? '• ' . $booking->patient->phone : '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($booking->items as $item)
                                        <span class="inline-flex items-center text-[11px] font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $item->labTest->code ?? $item->labTest->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($allVerified)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check mr-1.5 text-emerald-600"></i> Verified & Approved
                                    </span>
                                @elseif($hasResults)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                        <i class="fa-solid fa-clock mr-1.5 text-blue-600"></i> Awaiting Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-hourglass-start mr-1.5 text-amber-600"></i> Results Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $booking->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.reviews.show', $booking) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm" title="Review Diagnostic Report">
                                    <i class="fa-solid fa-microscope mr-1.5"></i> Review & Sign
                                </a>

                                <a href="{{ route('admin.reviews.report', $booking) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors" title="Print Final Report">
                                    <i class="fa-solid fa-print mr-1.5"></i> Print Report
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-user-doctor text-4xl mb-3 text-slate-300"></i>
                                <p class="text-sm font-medium">No test reviews found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
