<x-dashboard-layout>
    <x-slot name="title">{{ $test->name }} - Details</x-slot>
    <x-slot name="header">Laboratory Test Details</x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('admin.tests.index') }}" class="inline-flex items-center space-x-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Tests Catalog</span>
            </a>

            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.tests.edit', $test) }}" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-all shadow-md shadow-indigo-600/20">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                    <span>Edit Test</span>
                </a>
            </div>
        </div>

        <!-- Main Test Overview Header Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6 mb-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            <i class="fa-solid fa-tag mr-1.5"></i> {{ $test->category }}
                        </span>
                        @if($test->code)
                            <span class="font-mono bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-xs font-bold">
                                {{ $test->code }}
                            </span>
                        @endif
                        @if($test->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                Inactive
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-900">{{ $test->name }}</h1>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-right">
                    <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Test Charge / Fee</span>
                    <span class="text-2xl font-black text-indigo-600">PKR {{ number_format($test->price, 2) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs text-slate-600">
                <div>
                    <span class="text-slate-400 block mb-0.5">Total Sub-tests</span>
                    <strong class="text-slate-800 text-sm">{{ $test->parameters->count() }} Parameters</strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Report Notes</span>
                    <strong class="text-slate-800 text-sm">{{ $test->notes->count() }} Custom Lines</strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Created Date</span>
                    <strong class="text-slate-800 text-sm">{{ $test->created_at->format('M d, Y') }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Last Modified</span>
                    <strong class="text-slate-800 text-sm">{{ $test->updated_at->format('M d, Y') }}</strong>
                </div>
            </div>
        </div>

        <!-- Sub-tests / Parameters Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
            <div class="p-5 border-b border-slate-200/80 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Sub-Tests & Attribute Parameters</h3>
                <span class="text-xs font-semibold text-slate-400">{{ $test->parameters->count() }} Parameters Defined</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                            <th class="px-6 py-3.5">#</th>
                            <th class="px-6 py-3.5">Sub-Test Parameter Name</th>
                            <th class="px-6 py-3.5">Unit</th>
                            <th class="px-6 py-3.5">Normal Reference Range</th>
                            <th class="px-6 py-3.5">Method</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($test->parameters as $index => $param)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-6 py-3.5 text-xs text-slate-400 font-mono">{{ $index + 1 }}</td>
                                <td class="px-6 py-3.5 font-bold text-slate-900">{{ $param->name }}</td>
                                <td class="px-6 py-3.5 text-slate-600 font-mono text-xs">{{ $param->unit ?? '-' }}</td>
                                <td class="px-6 py-3.5 text-slate-600 font-medium text-xs">{{ $param->normal_range_text ?? '-' }}</td>
                                <td class="px-6 py-3.5 text-slate-500 text-xs italic">{{ $param->method ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs">
                                    No sub-test parameters defined for this test.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Custom Report Notes & Descriptions -->
        @if($test->notes->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center">
                    <i class="fa-solid fa-note-sticky text-amber-500 mr-2"></i>
                    Report Bottom Notes & Descriptions
                </h3>
                <div class="bg-amber-50/50 rounded-xl border border-amber-200/80 p-5 space-y-2">
                    @foreach($test->notes as $note)
                        <div class="flex items-start space-x-2 text-sm text-slate-800">
                            <span class="text-amber-600 font-bold">•</span>
                            <p class="leading-relaxed font-medium">{{ $note->note_text }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-dashboard-layout>
