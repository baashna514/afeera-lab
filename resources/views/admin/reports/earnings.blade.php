<x-dashboard-layout>
    <x-slot name="title">Earnings Report</x-slot>
    <x-slot name="header">Earnings & Payments Report</x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-xl mr-4 flex-shrink-0">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Today's Earnings</p>
                <p class="text-2xl font-bold text-slate-800">Rs. {{ number_format($todayTotal, 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl mr-4 flex-shrink-0">
                <i class="fa-solid fa-calendar-week"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">This Week</p>
                <p class="text-2xl font-bold text-slate-800">Rs. {{ number_format($weeklyTotal, 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xl mr-4 flex-shrink-0">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">This Month</p>
                <p class="text-2xl font-bold text-slate-800">Rs. {{ number_format($monthlyTotal, 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-xl mr-4 flex-shrink-0">
                <i class="fa-solid fa-vault"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Overall Earnings</p>
                <p class="text-2xl font-bold text-slate-800">Rs. {{ number_format($overallTotal, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.earnings') }}" class="flex flex-col sm:flex-row items-end space-y-4 sm:space-y-0 sm:space-x-4">
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-slate-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-slate-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="w-full sm:w-auto pb-0.5">
                <button type="submit" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-900 text-white font-medium py-2 px-6 rounded-lg transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
            </div>
            @if($startDate || $endDate)
                <div class="w-full sm:w-auto pb-0.5">
                    <a href="{{ route('admin.reports.earnings') }}" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-6 rounded-lg transition-colors flex items-center justify-center">
                        Clear
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-200 bg-slate-50">
            <h3 class="font-semibold text-slate-800"><i class="fa-solid fa-table-list mr-2 text-indigo-500"></i>Daily Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-semibold">
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Total Bookings</th>
                        <th class="px-6 py-4">Total Invoiced (Billed)</th>
                        <th class="px-6 py-4">Total Collected (Paid)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($earningsByDate as $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $row->total_bookings }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                Rs. {{ number_format($row->total_invoiced, 2) }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-emerald-600">
                                Rs. {{ number_format($row->total_paid, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-chart-pie text-3xl mb-3 text-slate-300"></i>
                                <p>No earnings data found for the selected period.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($earningsByDate->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $earningsByDate->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
