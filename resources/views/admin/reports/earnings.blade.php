<x-dashboard-layout>
    <x-slot name="title">Earnings Report</x-slot>
    <x-slot name="header">Earnings & Payments Report</x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex items-center">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-xl mr-4 flex-shrink-0">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Today's Earnings</p>
                <p class="text-2xl font-extrabold text-slate-800">Rs. {{ number_format($todayTotal, 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex items-center">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 text-xl mr-4 flex-shrink-0">
                <i class="fa-solid fa-calendar-week"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">This Week</p>
                <p class="text-2xl font-extrabold text-slate-800">Rs. {{ number_format($weeklyTotal, 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex items-center">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 text-xl mr-4 flex-shrink-0">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">This Month</p>
                <p class="text-2xl font-extrabold text-slate-800">Rs. {{ number_format($monthlyTotal, 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex items-center">
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 text-xl mr-4 flex-shrink-0">
                <i class="fa-solid fa-vault"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Total Collected</p>
                <p class="text-2xl font-extrabold text-slate-800">Rs. {{ number_format($overallTotal, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 mb-8">
        <form method="GET" action="{{ route('admin.reports.earnings') }}" class="flex flex-col lg:flex-row items-end gap-4">
            <div class="w-full sm:w-auto">
                <label class="block text-xs font-semibold uppercase text-slate-600 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2">
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-xs font-semibold uppercase text-slate-600 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2">
            </div>
            <div class="w-full sm:w-64">
                <label class="block text-xs font-semibold uppercase text-slate-600 mb-1">Filter by Specific Test</label>
                <select name="test_id" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2">
                    <option value="">All Tests (General Revenue)</option>
                    @foreach($availableTests as $test)
                        <option value="{{ $test->id }}" {{ (string)$selectedTestId === (string)$test->id ? 'selected' : '' }}>
                            {{ $test->name }} (Rs. {{ number_format($test->price, 0) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto pb-0.5 flex gap-2">
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-5 rounded-xl text-xs transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-filter mr-1.5"></i> Apply Filter
                </button>
                @if($startDate || $endDate || $selectedTestId)
                    <a href="{{ route('admin.reports.earnings') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2 px-4 rounded-xl text-xs transition-colors flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Specific Test Performance Breakdown Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden mb-8">
        <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">
                <i class="fa-solid fa-flask-vial mr-2 text-indigo-500"></i> Specific Test Revenue & Performance Breakdown
            </h3>
            <span class="text-xs text-slate-500 font-medium">{{ $testBreakdown->count() }} Test Types Conducted</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-3.5">Test Name</th>
                        <th class="px-6 py-3.5">Department / Category</th>
                        <th class="px-6 py-3.5">Standard Price</th>
                        <th class="px-6 py-3.5">Tests Performed</th>
                        <th class="px-6 py-3.5 text-right">Total Revenue Generated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($testBreakdown as $row)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $row->labTest->name ?? 'Deleted Test' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-xs font-semibold">
                                <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-700">{{ $row->labTest->category ?? 'General' }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-xs font-mono">
                                Rs. {{ number_format($row->labTest->price ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 font-extrabold text-indigo-600">
                                {{ $row->test_count }} Times
                            </td>
                            <td class="px-6 py-4 text-right font-extrabold text-emerald-600">
                                Rs. {{ number_format($row->total_revenue, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                <p class="text-sm">No test booking transactions recorded yet for this criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Breakdown Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800 text-sm">
                <i class="fa-solid fa-calendar-day mr-2 text-indigo-500"></i> Daily Revenue Log
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-3.5">Date</th>
                        <th class="px-6 py-3.5">Number of Bookings</th>
                        <th class="px-6 py-3.5">Total Invoiced (Billed)</th>
                        <th class="px-6 py-3.5 text-right">Total Collected (Cash Paid)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($earningsByDate as $row)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">
                                {{ $row->total_bookings }} Bookings
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-mono">
                                Rs. {{ number_format($row->total_invoiced, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-extrabold text-emerald-600">
                                Rs. {{ number_format($row->total_paid, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-chart-pie text-3xl mb-3 text-slate-300"></i>
                                <p class="text-sm">No daily earnings data found for the selected period.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($earningsByDate->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $earningsByDate->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
