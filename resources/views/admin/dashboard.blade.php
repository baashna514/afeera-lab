<x-dashboard-layout>
    <x-slot name="title">{{ $company->name ?? 'Company' }} - Dashboard</x-slot>
    <x-slot name="header">Laboratory Super Admin Dashboard</x-slot>

    <!-- Welcome Hero Section -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 sm:p-8 text-white shadow-xl mb-8 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 mb-3">
                    <i class="fa-solid fa-hospital mr-1.5"></i> {{ $company->name ?? 'Laboratory Organization' }}
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Welcome back, {{ $user->name }}!</h2>
                <p class="text-sm text-slate-300 mt-1 max-w-xl">
                    Manage patient bookings, sample collection barcodes, dynamic test results entry, and pathologist report verifications.
                </p>
            </div>
            <div class="flex items-center space-x-3 flex-shrink-0">
                <span class="inline-flex items-center px-4 py-2 bg-emerald-500/20 text-emerald-300 rounded-xl text-xs font-semibold border border-emerald-500/30">
                    <i class="fa-solid fa-circle text-[8px] mr-2 text-emerald-400"></i> System Operational
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Patients Registered Today -->
        <a href="{{ route('admin.bookings.index') }}" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Today's Patients</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $todayPatients }}</h3>
                <p class="text-xs text-slate-400 mt-1"><i class="fa-solid fa-user-plus text-indigo-500 mr-1"></i> New Bookings</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users"></i>
            </div>
        </a>

        <!-- Samples Pending Collection -->
        <a href="{{ route('admin.samples.index') }}" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pending Samples</p>
                <h3 class="text-3xl font-extrabold text-amber-600">{{ $pendingSamples }}</h3>
                <p class="text-xs text-amber-600 font-medium mt-1"><i class="fa-solid fa-vial mr-1"></i> Awaiting Barcode</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-barcode"></i>
            </div>
        </a>

        <!-- Pending Result Entry -->
        <a href="{{ route('admin.bookings.index') }}" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Result Entry</p>
                <h3 class="text-3xl font-extrabold text-blue-600">{{ $pendingResults }}</h3>
                <p class="text-xs text-blue-600 font-medium mt-1"><i class="fa-solid fa-file-waveform mr-1"></i> In Technician Queue</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-flask"></i>
            </div>
        </a>

        <!-- Reports Pending Verification -->
        <a href="{{ route('admin.reviews.index') }}" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pathologist Review</p>
                <h3 class="text-3xl font-extrabold text-purple-600">{{ $pendingReview }}</h3>
                <p class="text-xs text-purple-600 font-medium mt-1"><i class="fa-solid fa-user-doctor mr-1"></i> Pending Approval</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-signature"></i>
            </div>
        </a>
    </div>

    <!-- Laboratory Key Modules Workflows Overview -->
    <div class="mb-8">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Laboratory Operational Modules</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Module 1: Patient Booking & Registration -->
            <a href="{{ route('admin.bookings.create') }}" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all relative group block">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-base mb-1 group-hover:text-indigo-600 transition-colors">1. Patient Registration</h4>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Customer MRN creation, details entry, doctor referral, dynamic test selection (CBC, LFT, RFT) & automatic invoice.
                </p>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <span class="text-[11px] font-semibold text-indigo-600 uppercase tracking-wider">Module 1</span>
                    <span class="text-xs text-indigo-600 font-semibold flex items-center">Open &rarr;</span>
                </div>
            </a>

            <!-- Module 2: Sample Collection & Barcode Tagging -->
            <a href="{{ route('admin.samples.index') }}" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-amber-300 transition-all relative group block">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-barcode"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-base mb-1 group-hover:text-amber-600 transition-colors">2. Sample Barcode Tagging</h4>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Reception booking sample list, unique vial/tube barcode label generation & sticker printing.
                </p>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <span class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider">Module 2</span>
                    <span class="text-xs text-amber-600 font-semibold flex items-center">Open &rarr;</span>
                </div>
            </a>

            <!-- Module 3: Dynamic Test Result Entry -->
            <a href="{{ route('admin.bookings.index') }}" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-blue-300 transition-all relative group block">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-vial-circle-check"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-base mb-1 group-hover:text-blue-600 transition-colors">3. Dynamic Test Result Entry</h4>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Preloaded sub-parameters (Hemoglobin, TLC, Normal Ranges). Only enter test values rapidly into template.
                </p>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <span class="text-[11px] font-semibold text-blue-600 uppercase tracking-wider">Module 3</span>
                    <span class="text-xs text-blue-600 font-semibold flex items-center">Open &rarr;</span>
                </div>
            </a>

            <!-- Module 4: Pathologist Verification & PDF Report -->
            <a href="{{ route('admin.reviews.index') }}" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all relative group block">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-pdf"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-base mb-1 group-hover:text-emerald-600 transition-colors">4. Pathologist Review & PDF</h4>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Doctor review, digital signature, clinical notes, and professional printable laboratory report.
                </p>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider">Module 4</span>
                    <span class="text-xs text-emerald-600 font-semibold flex items-center">Open &rarr;</span>
                </div>
            </a>

        </div>
    </div>
</x-dashboard-layout>
