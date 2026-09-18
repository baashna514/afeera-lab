<x-dashboard-layout>
    <x-slot name="title">Create New Company</x-slot>
    <x-slot name="header">Add New Laboratory Tenant & Super Admin</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('owner.companies.index') }}" class="inline-flex items-center space-x-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Companies List</span>
            </a>
        </div>

        <form method="POST" action="{{ route('owner.companies.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Section 1: Company Information -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">1. Company / Laboratory Information</h3>
                        <p class="text-xs text-slate-500">General details about the healthcare laboratory organization</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Company Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required 
                               placeholder="e.g. City Pathology Laboratories" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('company_name') border-rose-500 @enderror">
                        @error('company_name')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Email -->
                    <div>
                        <label for="company_email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Company Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="company_email" id="company_email" value="{{ old('company_email') }}" required 
                               placeholder="e.g. info@citypathology.com" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('company_email') border-rose-500 @enderror">
                        @error('company_email')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Phone -->
                    <div>
                        <label for="company_phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Phone Number
                        </label>
                        <input type="text" name="company_phone" id="company_phone" value="{{ old('company_phone') }}" 
                               placeholder="e.g. +92 42 37891000" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('company_phone') border-rose-500 @enderror">
                        @error('company_phone')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Account Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active (Operational)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Suspended)</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                                        <!-- Company Logo -->
                    <div>
                        <label for="company_logo" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Company Logo
                        </label>
                        <input type="file" name="company_logo" id="company_logo" accept="image/*"
                               class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('company_logo')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Address -->
                    <div class="md:col-span-2">
                        <label for="company_address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Physical Address
                        </label>
                        <textarea name="company_address" id="company_address" rows="3" 
                                  placeholder="e.g. 123 Medical Center Drive, Main Boulevard, Lahore" 
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('company_address') border-rose-500 @enderror">{{ old('company_address') }}</textarea>
                        @error('company_address')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Company Super Admin User Account -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center font-bold text-lg">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">2. Company Super Admin Credentials</h3>
                        <p class="text-xs text-slate-500">The primary administrator account created for this company to manage their lab operations</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Admin Full Name -->
                    <div>
                        <label for="admin_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Super Admin Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" required 
                               placeholder="e.g. Dr. Sarah Chen" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('admin_name') border-rose-500 @enderror">
                        @error('admin_name')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Email -->
                    <div>
                        <label for="admin_email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Super Admin Login Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" required 
                               placeholder="e.g. admin@citypathology.com" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('admin_email') border-rose-500 @enderror">
                        @error('admin_email')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Password -->
                    <div>
                        <label for="admin_password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Initial Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="admin_password" id="admin_password" required 
                               placeholder="Minimum 8 characters" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('admin_password') border-rose-500 @enderror">
                        @error('admin_password')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Phone -->
                    <div>
                        <label for="admin_phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Admin Direct Phone
                        </label>
                        <input type="text" name="admin_phone" id="admin_phone" value="{{ old('admin_phone') }}" 
                               placeholder="e.g. +92 300 9876543" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('admin_phone') border-rose-500 @enderror">
                        @error('admin_phone')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button Footer -->
            <div class="flex items-center justify-end space-x-4 pt-2">
                <a href="{{ route('owner.companies.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-6 py-3 rounded-xl transition-all shadow-md shadow-indigo-600/30">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Create Company & Super Admin</span>
                </button>
            </div>
        </form>
    </div>
</x-dashboard-layout>
