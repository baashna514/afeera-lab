<x-dashboard-layout>
    <x-slot name="title">Patient Bookings</x-slot>
    <x-slot name="header">Patient Bookings</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <p class="text-slate-600 text-sm">Manage patient registrations, billing, and test bookings.</p>
        <a href="{{ route('admin.bookings.create') }}" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
            <i class="fa-solid fa-plus"></i>
            <span>New Booking</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-semibold">
                        <th class="px-6 py-4">Invoice #</th>
                        <th class="px-6 py-4">Patient Name</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-indigo-600">{{ $booking->invoice_number }}</td>
                            <td class="px-6 py-4 text-slate-800">
                                {{ $booking->patient->name }}
                                <div class="text-xs text-slate-500">{{ $booking->patient->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $booking->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-slate-800 font-medium">
                                Rs. {{ number_format($booking->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.results.edit', $booking) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-indigo-600 hover:bg-indigo-50 transition-colors" title="Enter Results">
                                    <i class="fa-solid fa-file-waveform"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-folder-open text-3xl mb-3 text-slate-300"></i>
                                <p>No bookings found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
