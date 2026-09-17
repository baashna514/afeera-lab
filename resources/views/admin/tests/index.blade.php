<x-dashboard-layout>
    <x-slot name="title">Test Management</x-slot>
    <x-slot name="header">Laboratory Test Catalog Management</x-slot>

    <!-- Top Action Bar & Filters -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('admin.tests.index') }}" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
            <div class="relative w-full sm:w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search test name, code or category..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
            </div>

            <select name="category" onchange="this.form.submit()" class="w-full sm:w-48 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="w-full sm:w-36 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            @if(request('search') || request('category') || request('status'))
                <a href="{{ route('admin.tests.index') }}" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 underline">
                    Clear Filters
                </a>
            @endif
        </form>

        <!-- Create Test Button -->
        <a href="{{ route('admin.tests.create') }}" class="w-full md:w-auto inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-md shadow-indigo-600/20">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Create New Test</span>
        </a>
    </div>

    <!-- Tests Cards / Table Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tests as $test)
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <!-- Header Badges -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                            <i class="fa-solid fa-tag text-slate-400 mr-1.5"></i> {{ $test->category }}
                        </span>
                        @if($test->status === 'active')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                Inactive
                            </span>
                        @endif
                    </div>

                    <!-- Test Name & Code -->
                    <h3 class="text-lg font-bold text-slate-900 mb-1">
                        <a href="{{ route('admin.tests.show', $test) }}" class="hover:text-indigo-600 transition-colors">
                            {{ $test->name }}
                        </a>
                    </h3>
                    @if($test->code)
                        <p class="text-xs text-slate-400 mb-4">Code: <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 font-semibold">{{ $test->code }}</span></p>
                    @endif

                    <!-- Attributes Counters -->
                    <div class="flex items-center space-x-4 text-xs text-slate-500 mb-4 pt-3 border-t border-slate-100">
                        <div class="flex items-center space-x-1.5" title="Sub-tests / Parameters Count">
                            <i class="fa-solid fa-list-check text-indigo-500"></i>
                            <span class="font-semibold text-slate-700">{{ $test->parameters_count }}</span> Sub-tests
                        </div>
                        <div class="flex items-center space-x-1.5" title="Report Notes Count">
                            <i class="fa-solid fa-note-sticky text-amber-500"></i>
                            <span class="font-semibold text-slate-700">{{ $test->notes_count }}</span> Report Notes
                        </div>
                    </div>
                </div>

                <!-- Footer Price & Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-slate-400 block">Test Fee</span>
                        <span class="text-base font-extrabold text-indigo-600">PKR {{ number_format($test->price, 2) }}</span>
                    </div>

                    <div class="flex items-center space-x-1">
                        <a href="{{ route('admin.tests.show', $test) }}" class="p-2 text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 rounded-lg transition-colors" title="View Details">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.tests.edit', $test) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Test">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.tests.destroy', $test) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this test?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Test">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 bg-white rounded-2xl p-12 text-center border border-slate-200/80 shadow-sm">
                <i class="fa-solid fa-flask-vial text-5xl text-slate-300 mb-3"></i>
                <h3 class="text-base font-bold text-slate-700">No Tests Found</h3>
                <p class="text-xs text-slate-400 mt-1 mb-4">Start by creating your first laboratory test with sub-test parameters and custom notes.</p>
                <a href="{{ route('admin.tests.create') }}" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-all shadow-md shadow-indigo-600/20">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Create Test</span>
                </a>
            </div>
        @endforelse
    </div>

    @if($tests->hasPages())
        <div class="mt-8">
            {{ $tests->links() }}
        </div>
    @endif
</x-dashboard-layout>
