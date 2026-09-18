<x-dashboard-layout>
    <x-slot name="title">Companies List</x-slot>
    <x-slot name="header">Companies Management</x-slot>

    <!-- Top Action Bar & Filters -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('owner.companies.index') }}" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
            <div class="relative w-full sm:w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search company or admin..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
            </div>

            <select name="status" onchange="this.form.submit()" class="w-full sm:w-44 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            @if(request('search') || request('status'))
                <a href="{{ route('owner.companies.index') }}" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 underline">
                    Clear Filters
                </a>
            @endif
        </form>

        <!-- Create Button -->
        <a href="{{ route('owner.companies.create') }}" class="w-full md:w-auto inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-md shadow-indigo-600/20">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Create New Company</span>
        </a>
    </div>

    <!-- Companies Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4">Company</th>
                        <th class="px-6 py-4">Contact Information</th>
                        <th class="px-6 py-4">Super Admin User</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created At</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($companies as $company)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($company->logo)
                                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm overflow-hidden p-1 border border-slate-200">
                                            <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="w-full h-full object-contain">
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-base shadow-sm">
                                            {{ strtoupper(substr($company->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('owner.companies.show', $company) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition-colors">
                                            {{ $company->name }}
                                        </a>
                                        <div class="text-xs text-slate-400 font-normal">ID: #LAB-{{ str_pad($company->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <div class="text-xs"><i class="fa-solid fa-envelope text-slate-400 mr-1.5"></i>{{ $company->email }}</div>
                                @if($company->phone)
                                    <div class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-phone text-slate-400 mr-1.5"></i>{{ $company->phone }}</div>
                                @endif
                                @if($company->address)
                                    <div class="text-xs text-slate-400 mt-0.5 truncate max-w-xs"><i class="fa-solid fa-location-dot text-slate-400 mr-1.5"></i>{{ $company->address }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($company->superAdmin)
                                    <div class="font-semibold text-slate-800 text-xs flex items-center">
                                        <i class="fa-solid fa-user-shield text-indigo-500 mr-1.5"></i>
                                        {{ $company->superAdmin->name }}
                                    </div>
                                    <div class="text-xs text-slate-500 pl-5">{{ $company->superAdmin->email }}</div>
                                @else
                                    <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">No Super Admin assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($company->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $company->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <a href="{{ route('owner.companies.show', $company) }}" class="inline-flex items-center p-2 text-slate-600 hover:text-cyan-600 hover:bg-cyan-50 rounded-lg transition-colors" title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('owner.companies.edit', $company) }}" class="inline-flex items-center p-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Company">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST" action="{{ route('owner.companies.destroy', $company) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this company?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Company">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-building-circle-xmark text-4xl mb-3 text-slate-300"></i>
                                <p class="text-base font-semibold text-slate-600">No Companies Found</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your search filters or create a new company.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($companies->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $companies->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
