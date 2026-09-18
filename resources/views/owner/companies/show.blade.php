<x-dashboard-layout>
    <x-slot name="title">{{ $company->name }} - Details</x-slot>
    <x-slot name="header">Company Details & Overview</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('owner.companies.index') }}" class="inline-flex items-center space-x-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Companies List</span>
            </a>

            <div class="flex items-center space-x-3">
                <a href="{{ route('owner.companies.edit', $company) }}" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-all shadow-md shadow-indigo-600/20">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                    <span>Edit Company</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Main Info Card -->
            <div class="md:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-start justify-between mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-4">
                        @if($company->logo)
                            <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center shadow-sm overflow-hidden p-1 border border-slate-200">
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="w-full h-full object-contain">
                            </div>
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-extrabold text-2xl shadow-sm">
                                {{ strtoupper(substr($company->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ $company->name }}</h2>
                            <p class="text-xs text-slate-400">ID: #LAB-{{ str_pad($company->id, 4, '0', STR_PAD_LEFT) }} • Registered {{ $company->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @if($company->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                            <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span> Inactive
                        </span>
                    @endif
                </div>

                <div class="space-y-4 text-sm text-slate-700">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-envelope w-5 text-indigo-500 text-center"></i>
                        <span class="font-medium">Business Email:</span>
                        <a href="mailto:{{ $company->email }}" class="text-indigo-600 hover:underline">{{ $company->email }}</a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-phone w-5 text-indigo-500 text-center"></i>
                        <span class="font-medium">Phone Number:</span>
                        <span>{{ $company->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fa-solid fa-location-dot w-5 text-indigo-500 text-center mt-1"></i>
                        <span class="font-medium">Address:</span>
                        <span>{{ $company->address ?? 'Not provided' }}</span>
                    </div>
                </div>
            </div>

            <!-- Super Admin Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-2 text-cyan-600 mb-3 font-bold text-xs uppercase tracking-wider">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>Super Admin</span>
                    </div>
                    @if($company->superAdmin)
                        <div class="font-bold text-slate-900 text-base mb-1">{{ $company->superAdmin->name }}</div>
                        <div class="text-xs text-slate-500 mb-2">{{ $company->superAdmin->email }}</div>
                        @if($company->superAdmin->phone)
                            <div class="text-xs text-slate-500"><i class="fa-solid fa-phone mr-1"></i>{{ $company->superAdmin->phone }}</div>
                        @endif
                    @else
                        <p class="text-xs text-slate-400">No Super Admin assigned to this company.</p>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4">
                    <span class="text-xs text-slate-400">Total Company Users: <strong class="text-slate-800">{{ $company->users->count() }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Registered Users Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200/80">
                <h3 class="text-base font-bold text-slate-800">Company Users & Personnel</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($company->users as $user)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-6 py-3 font-semibold text-slate-900">{{ $user->name }}</td>
                                <td class="px-6 py-3 text-slate-600 text-xs">{{ $user->email }}</td>
                                <td class="px-6 py-3 text-xs capitalize">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800">
                                        {{ str_replace('_', ' ', $user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-xs">
                                    @if($user->is_active)
                                        <span class="text-emerald-600 font-semibold"><i class="fa-solid fa-circle text-[8px] mr-1"></i> Active</span>
                                    @else
                                        <span class="text-rose-500 font-semibold"><i class="fa-solid fa-circle text-[8px] mr-1"></i> Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-slate-400 text-xs">No users registered under this company yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard-layout>
