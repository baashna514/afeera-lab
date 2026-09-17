<x-dashboard-layout>
    <x-slot name="title">Result Entry - {{ $booking->invoice_number }}</x-slot>
    <x-slot name="header">Test Result Entry</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ $booking->patient->name }}</h2>
            <p class="text-slate-500 text-sm">Invoice: {{ $booking->invoice_number }} • Date: {{ $booking->created_at->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm transition-colors">
            &larr; Back to Bookings
        </a>
    </div>

    <form action="{{ route('admin.results.update', $booking) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        @foreach($booking->items as $item)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-bold text-lg text-slate-800 text-center uppercase tracking-wide">{{ $item->labTest->name }}</h3>
                    @if($item->labTest->category)
                        <p class="text-center text-slate-500 text-sm font-medium mt-1">{{ $item->labTest->category }}</p>
                    @endif
                </div>
                
                @if($item->result && $item->result->parameters->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-200 text-xs uppercase text-slate-500 font-semibold">
                                    <th class="px-6 py-3 w-1/3">Test Parameter</th>
                                    <th class="px-6 py-3 w-1/3">Result Value</th>
                                    <th class="px-6 py-3">Unit</th>
                                    <th class="px-6 py-3">Normal Range</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($item->result->parameters as $param)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-slate-800">
                                            {{ $param->parameter_name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="results[{{ $param->id }}]" value="{{ $param->result_value }}" 
                                                   class="w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-semibold text-indigo-700 bg-indigo-50/30"
                                                   placeholder="Enter result...">
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ $param->unit ?? '--' }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ $param->normal_range_text ?? '--' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-slate-500">
                        <i class="fa-solid fa-flask-vial text-3xl mb-3 text-slate-300"></i>
                        <p>No parameters defined for this test.</p>
                    </div>
                @endif
            </div>
        @endforeach

        <div class="flex justify-end pt-4 border-t border-slate-200">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-sm transition-colors flex items-center">
                <i class="fa-solid fa-save mr-2"></i> Save Results & Complete
            </button>
        </div>
    </form>
</x-dashboard-layout>
