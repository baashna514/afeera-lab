<x-dashboard-layout>
    <x-slot name="title">SaaS Owner Dashboard</x-slot>
    <x-slot name="header">SaaS Overview & Control Center</x-slot>

    <!-- Stat Cards Header -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Companies -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Companies</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalCompanies }}</h3>
                <p class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-building text-indigo-500 mr-1"></i> Registered Labs</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-xl">
                <i class="fa-solid fa-building flex-shrink-0"></i>
            </div>
        </div>

        <!-- Active Companies -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Active Labs</p>
                <h3 class="text-3xl font-extrabold text-emerald-600">{{ $activeCompanies }}</h3>
                <p class="text-xs text-emerald-600 font-medium mt-1"><i class="fa-solid fa-circle-check mr-1"></i> Operational</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-xl">
                <i class="fa-solid fa-square-check"></i>
            </div>
        </div>

        <!-- Inactive Companies -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Inactive Labs</p>
                <h3 class="text-3xl font-extrabold text-rose-500">{{ $inactiveCompanies }}</h3>
                <p class="text-xs text-rose-500 font-medium mt-1"><i class="fa-solid fa-pause mr-1"></i> Suspended</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 text-xl">
                <i class="fa-solid fa-ban"></i>
            </div>
        </div>

        <!-- Total Super Admins -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Super Admins</p>
                <h3 class="text-3xl font-extrabold text-cyan-600">{{ $totalSuperAdmins }}</h3>
                <p class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-user-shield text-cyan-500 mr-1"></i> Lab Administrators</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-50 border border-cyan-100 flex items-center justify-center text-cyan-600 text-xl">
                <i class="fa-solid fa-user-gear"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Recent Companies</h3>
                <p class="text-xs text-slate-500 mt-0.5">Recently registered laboratory tenants in the platform</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('owner.companies.create') }}" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all shadow-md shadow-indigo-600/20">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Create New Company</span>
                </a>
                <a href="{{ route('owner.companies.index') }}" class="inline-flex items-center space-x-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all">
                    <span>View All</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4">Company Name</th>
                        <th class="px-6 py-4">Contact Details</th>
                        <th class="px-6 py-4">Super Admin</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($recentCompanies as $company)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-900">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($company->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $company->name }}</div>
                                        <div class="text-xs text-slate-400 font-normal">ID: #LAB-{{ str_pad($company->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <div class="text-xs"><i class="fa-solid fa-envelope text-slate-400 mr-1.5"></i>{{ $company->email }}</div>
                                @if($company->phone)
                                    <div class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-phone text-slate-400 mr-1.5"></i>{{ $company->phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($company->superAdmin)
                                    <div class="font-medium text-slate-800 text-xs"><i class="fa-solid fa-user-shield text-slate-400 mr-1"></i>{{ $company->superAdmin->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $company->superAdmin->email }}</div>
                                @else
                                    <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">No Super Admin</span>
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
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('owner.companies.edit', $company) }}" class="inline-flex items-center p-1.5 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Company">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="{{ route('owner.companies.show', $company) }}" class="inline-flex items-center p-1.5 text-slate-600 hover:text-cyan-600 hover:bg-cyan-50 rounded-lg transition-colors" title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-building-circle-xmark text-3xl mb-2"></i>
                                <p>No companies found yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
