<x-dashboard-layout>
    <x-slot name="title">System Settings</x-slot>
    <x-slot name="header">System Settings</x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between pb-5 border-b mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800">Company & Report Settings</h2>
                            <p class="text-sm text-slate-500 mt-1">Configure your laboratory profile, doctor credentials, and customize printed report layouts.</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-lg relative mb-6 flex items-center gap-2" role="alert">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-lg relative mb-6" role="alert">
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Company Information -->
                        <div class="mb-10 border-b pb-8">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-building text-indigo-600"></i>
                                Company Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Company / Laboratory Name</label>
                                    <input type="text" name="company_name" value="{{ old('company_name', $setting->company_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Email Address</label>
                                    <input type="email" name="company_email" value="{{ old('company_email', $setting->company_email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Phone Number</label>
                                    <input type="text" name="company_phone" value="{{ old('company_phone', $setting->company_phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Company Logo</label>
                                    
                                    @php
                                        $currentLogo = $setting->company_logo ?? null;
                                    @endphp

                                    @if($currentLogo)
                                        <!-- Logo Already Uploaded - Show Preview + Remove Button -->
                                        <div id="current_logo_card" class="border rounded-lg p-3 bg-slate-50 mb-3 border-slate-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ asset('storage/' . $currentLogo) }}" 
                                                         alt="Company Logo" 
                                                         class="h-20 w-20 object-contain border rounded bg-white p-1 border-slate-200 shadow-sm">
                                                    <div>
                                                        <p class="text-xs font-semibold text-slate-700">Current Logo</p>
                                                        <p class="text-xs text-slate-500 mt-0.5" id="current_filename">{{ basename($currentLogo) }}</p>
                                                    </div>
                                                </div>
                                                
                                                <button type="button" 
                                                        onclick="confirmRemoveLogo()"
                                                        class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold py-2 px-3.5 rounded-lg flex items-center gap-1.5 transition-colors shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Remove Logo
                                                </button>
                                            </div>
                                            <input type="hidden" name="remove_logo" id="remove_logo_flag" value="0">
                                        </div>
                                    @else
                                        <!-- No Logo Uploaded Yet -->
                                        <div id="no_logo_placeholder" class="border-2 border-dashed border-slate-300 rounded-lg p-4 mb-3 bg-slate-50 text-center">
                                            <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="mt-2 text-xs font-semibold text-slate-600">No logo uploaded yet</p>
                                            <p class="text-[11px] text-slate-400">Select an image to preview & manage it</p>
                                        </div>
                                    @endif

                                    <!-- Remove notice container -->
                                    <div id="remove_logo_notice_container"></div>

                                    <!-- Live Preview for newly selected file (Hidden by Default) -->
                                    <div id="new_logo_preview" class="hidden mb-3 border border-indigo-200 rounded-lg p-3 bg-indigo-50/70">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <img id="preview_image" src="" alt="New Logo Preview" class="h-16 w-16 object-contain border rounded bg-white p-1 border-indigo-200 shadow-sm">
                                                <div>
                                                    <p class="text-xs font-bold text-indigo-900 flex items-center gap-1">
                                                        <i class="fa-solid fa-circle-check text-indigo-600"></i> New logo selected
                                                    </p>
                                                    <p class="text-xs text-indigo-700 mt-0.5" id="new_file_name"></p>
                                                    <p class="text-[11px] text-indigo-500 mt-0.5">Click "Save Settings" below to apply this logo.</p>
                                                </div>
                                            </div>
                                            <button type="button" onclick="cancelNewLogo()" class="text-xs text-slate-600 hover:text-slate-800 bg-white border border-slate-200 px-2.5 py-1 rounded shadow-sm">
                                                Clear
                                            </button>
                                        </div>
                                    </div>

                                    <!-- File Input -->
                                    <input type="file" 
                                           name="company_logo" 
                                           id="logo_file_input"
                                           accept="image/*"
                                           onchange="previewLogo(event)"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">

                                    @error('company_logo')
                                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700">Laboratory Address</label>
                                    <textarea name="company_address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('company_address', $setting->company_address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Report Display Settings (3 Portions) -->
                        <div x-data="{
                            showHeader: {{ old('show_header', $setting->show_header ?? true) ? '1' : '0' }},
                            showPatient: {{ old('show_patient_info', $setting->show_patient_info ?? true) ? '1' : '0' }},
                            showFooter: {{ old('show_footer', $setting->show_footer ?? true) ? '1' : '0' }}
                        }" class="mb-10 border-b pb-8">
                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                    <i class="fa-solid fa-sliders text-indigo-600"></i>
                                    Report Display Settings
                                </h3>
                                <p class="text-sm text-slate-500 mt-1">Configure which sections and individual information fields should appear on the printed diagnostic report, and set default values.</p>
                            </div>

                            <div class="space-y-6">
                                <!-- Portion 1: Header Display Settings -->
                                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm transition-all">
                                    <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <h4 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                                                <i class="fa-solid fa-heading text-indigo-500"></i>
                                                1. Header Display Settings
                                            </h4>
                                            <p class="text-xs text-slate-500 mt-0.5">Top section containing laboratory branding, logo, contact, and report metadata.</p>
                                        </div>
                                        <div class="flex items-center gap-4 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
                                            <span class="text-xs font-semibold text-slate-600">Show Header:</span>
                                            <label class="inline-flex items-center text-sm cursor-pointer">
                                                <input type="radio" name="show_header" value="1" x-model="showHeader" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                                <span class="ml-1.5 font-medium text-slate-800">Yes</span>
                                            </label>
                                            <label class="inline-flex items-center text-sm cursor-pointer">
                                                <input type="radio" name="show_header" value="0" x-model="showHeader" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                                <span class="ml-1.5 font-medium text-slate-800">No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Granular Header Options -->
                                    <div x-show="showHeader == '1'" x-transition class="p-5 bg-white space-y-4">
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Header Elements to Display:</label>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_header_logo" value="1" {{ old('show_header_logo', $setting->show_header_logo ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Company Logo</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_header_company_name" value="1" {{ old('show_header_company_name', $setting->show_header_company_name ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Company / Lab Name</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_header_address" value="1" {{ old('show_header_address', $setting->show_header_address ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Laboratory Address</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_header_phone" value="1" {{ old('show_header_phone', $setting->show_header_phone ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Phone Number</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_header_email" value="1" {{ old('show_header_email', $setting->show_header_email ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Email Address</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_header_lab_no" value="1" {{ old('show_header_lab_no', $setting->show_header_lab_no ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Lab / Invoice Number</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_header_report_date" value="1" {{ old('show_header_report_date', $setting->show_header_report_date ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Report Date</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_header_sample_date" value="1" {{ old('show_header_sample_date', $setting->show_header_sample_date ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Sample Collection Date</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_header_report_status" value="1" {{ old('show_header_report_status', $setting->show_header_report_status ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Report Status (e.g. Final)</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Header Lab Number Configuration -->
                                        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center gap-3">
                                            <div class="flex-1">
                                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                                    <i class="fa-solid fa-hashtag text-indigo-500 mr-1"></i> Lab / Invoice Number Prefix
                                                </label>
                                                <input type="text" name="default_lab_prefix" value="{{ old('default_lab_prefix', $setting->default_lab_prefix ?? 'INV-') }}" placeholder="e.g. LAB-2026- or INV-" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm w-full max-w-sm">
                                                <p class="text-xs text-slate-500 mt-1">Default prefix applied to invoices & lab numbers on reports.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="showHeader == '0'" class="p-4 bg-amber-50/50 border-t border-amber-100 text-xs text-amber-700 flex items-center gap-2">
                                        <i class="fa-solid fa-circle-info text-amber-500"></i>
                                        The entire top header section will be completely hidden from diagnostic reports.
                                    </div>
                                </div>

                                <!-- Portion 2: Patient Information Display Settings -->
                                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm transition-all">
                                    <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <h4 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                                                <i class="fa-solid fa-hospital-user text-emerald-500"></i>
                                                2. Patient Information Display Settings
                                            </h4>
                                            <p class="text-xs text-slate-500 mt-0.5">Middle box showing patient identity, demographics, sample collection type, and clinical details.</p>
                                        </div>
                                        <div class="flex items-center gap-4 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
                                            <span class="text-xs font-semibold text-slate-600">Show Patient Info:</span>
                                            <label class="inline-flex items-center text-sm cursor-pointer">
                                                <input type="radio" name="show_patient_info" value="1" x-model="showPatient" class="text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                                                <span class="ml-1.5 font-medium text-slate-800">Yes</span>
                                            </label>
                                            <label class="inline-flex items-center text-sm cursor-pointer">
                                                <input type="radio" name="show_patient_info" value="0" x-model="showPatient" class="text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                                                <span class="ml-1.5 font-medium text-slate-800">No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Granular Patient Info Options -->
                                    <div x-show="showPatient == '1'" x-transition class="p-5 bg-white space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Patient Information Fields to Display:</label>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_patient_name" value="1" {{ old('show_patient_name', $setting->show_patient_name ?? true) ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Patient Name</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_patient_age_gender" value="1" {{ old('show_patient_age_gender', $setting->show_patient_age_gender ?? true) ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Age & Gender</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_patient_id" value="1" {{ old('show_patient_id', $setting->show_patient_id ?? true) ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Patient ID (PID / MR)</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_patient_referred_by" value="1" {{ old('show_patient_referred_by', $setting->show_patient_referred_by ?? true) ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Referred By (Doctor)</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_patient_contact" value="1" {{ old('show_patient_contact', $setting->show_patient_contact ?? true) ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Contact / Phone</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_patient_collection_type" value="1" {{ old('show_patient_collection_type', $setting->show_patient_collection_type ?? true) ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Collection Type (Specimen)</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_patient_fasting" value="1" {{ old('show_patient_fasting', $setting->show_patient_fasting ?? true) ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Fasting Status</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_patient_clinical_info" value="1" {{ old('show_patient_clinical_info', $setting->show_patient_clinical_info ?? true) ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Clinical Information</span>
                                                </label>
                                                <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                    <input type="checkbox" name="show_patient_barcode" value="1" {{ old('show_patient_barcode', $setting->show_patient_barcode ?? true) ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-slate-700 font-medium">Barcode Number</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Fill Default Values for Dynamic Clinical Fields -->
                                        <div class="pt-4 border-t border-slate-200">
                                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2 flex items-center gap-1.5">
                                                <i class="fa-solid fa-pen-to-square text-emerald-600"></i>
                                                Fill Values / Default Content for Report Fields:
                                            </label>
                                            <p class="text-xs text-slate-500 mb-3">Set the values for these fields. If not customized when registering a patient or booking, these values will be automatically printed on the report.</p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Collection Type</label>
                                                    <input type="text" name="default_collection_type" value="{{ old('default_collection_type', $setting->default_collection_type ?? 'Venous Blood') }}" placeholder="e.g. Venous Blood" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Fasting Status</label>
                                                    <input type="text" name="default_fasting" value="{{ old('default_fasting', $setting->default_fasting ?? 'No') }}" placeholder="e.g. No, Yes, 12 Hours" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Clinical Information</label>
                                                    <input type="text" name="default_clinical_info" value="{{ old('default_clinical_info', $setting->default_clinical_info ?? 'Routine Check-up') }}" placeholder="e.g. Routine Check-up" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Referred By (Doctor)</label>
                                                    <input type="text" name="default_referred_by" value="{{ old('default_referred_by', $setting->default_referred_by ?? 'Dr. Consultant Physician') }}" placeholder="e.g. Dr. Consultant Physician" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="showPatient == '0'" class="p-4 bg-amber-50/50 border-t border-amber-100 text-xs text-amber-700 flex items-center gap-2">
                                        <i class="fa-solid fa-circle-info text-amber-500"></i>
                                        The entire patient information box will be hidden from diagnostic reports.
                                    </div>
                                </div>

                                <!-- Portion 3: Footer Display Settings -->
                                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm transition-all">
                                    <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <h4 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                                                <i class="fa-solid fa-file-signature text-purple-500"></i>
                                                3. Footer Display Settings
                                            </h4>
                                            <p class="text-xs text-slate-500 mt-0.5">Bottom section containing QR verification, signatures, pathologist credentials, and disclaimer.</p>
                                        </div>
                                        <div class="flex items-center gap-4 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
                                            <span class="text-xs font-semibold text-slate-600">Show Footer:</span>
                                            <label class="inline-flex items-center text-sm cursor-pointer">
                                                <input type="radio" name="show_footer" value="1" x-model="showFooter" class="text-purple-600 focus:ring-purple-500 h-4 w-4">
                                                <span class="ml-1.5 font-medium text-slate-800">Yes</span>
                                            </label>
                                            <label class="inline-flex items-center text-sm cursor-pointer">
                                                <input type="radio" name="show_footer" value="0" x-model="showFooter" class="text-purple-600 focus:ring-purple-500 h-4 w-4">
                                                <span class="ml-1.5 font-medium text-slate-800">No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Granular Footer Options -->
                                    <div x-show="showFooter == '1'" x-transition class="p-5 bg-white">
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Footer Elements to Display:</label>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                            <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                <input type="checkbox" name="show_footer_qr" value="1" {{ old('show_footer_qr', $setting->show_footer_qr ?? true) ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-slate-700 font-medium">QR Verification Code</span>
                                            </label>
                                            <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                <input type="checkbox" name="show_footer_signature" value="1" {{ old('show_footer_signature', $setting->show_footer_signature ?? true) ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-slate-700 font-medium">Doctor Signature Line / Stamp</span>
                                            </label>
                                            <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                <input type="checkbox" name="show_footer_doctor_name" value="1" {{ old('show_footer_doctor_name', $setting->show_footer_doctor_name ?? true) ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-slate-700 font-medium">Doctor Name</span>
                                            </label>
                                            <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                <input type="checkbox" name="show_footer_doctor_degree" value="1" {{ old('show_footer_doctor_degree', $setting->show_footer_doctor_degree ?? true) ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-slate-700 font-medium">Degrees & Qualifications</span>
                                            </label>
                                            <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                <input type="checkbox" name="show_footer_doctor_reg" value="1" {{ old('show_footer_doctor_reg', $setting->show_footer_doctor_reg ?? true) ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-slate-700 font-medium">Registration Number</span>
                                            </label>
                                            <label class="flex items-center p-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                                <input type="checkbox" name="show_footer_disclaimer" value="1" {{ old('show_footer_disclaimer', $setting->show_footer_disclaimer ?? true) ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                                <span class="ml-2 text-sm text-slate-700 font-medium">Computer Generated Disclaimer</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div x-show="showFooter == '0'" class="p-4 bg-amber-50/50 border-t border-amber-100 text-xs text-amber-700 flex items-center gap-2">
                                        <i class="fa-solid fa-circle-info text-amber-500"></i>
                                        The entire report footer and doctor signature block will be hidden from diagnostic reports.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Information (Footer Credentials) -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-user-doctor text-indigo-600"></i>
                                Pathologist / Doctor Credentials (Report Footer)
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Doctor Name</label>
                                    <input type="text" name="doctor_name" value="{{ old('doctor_name', $setting->doctor_name) }}" placeholder="e.g. Dr. Ritu Malhotra" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Degrees / Qualifications</label>
                                    <input type="text" name="doctor_degree" value="{{ old('doctor_degree', $setting->doctor_degree) }}" placeholder="e.g. MBBS, MD (Pathology)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Registration Number</label>
                                    <input type="text" name="doctor_reg_no" value="{{ old('doctor_reg_no', $setting->doctor_reg_no) }}" placeholder="e.g. Reg No.: DMC/2009/12345" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save Settings
                            </button>
                        </div>
                    </form>

                    <!-- Hidden Form for Remove Logo -->
                    @if($setting->company_logo)
                        <form id="remove-logo-form" action="{{ route('admin.settings.remove-logo') }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview_image').src = e.target.result;
                    document.getElementById('new_file_name').textContent = file.name;
                    document.getElementById('new_logo_preview').classList.remove('hidden');
                    
                    const noLogoBox = document.getElementById('no_logo_placeholder');
                    if (noLogoBox) noLogoBox.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function cancelNewLogo() {
            const input = document.getElementById('logo_file_input');
            if (input) input.value = '';
            document.getElementById('new_logo_preview').classList.add('hidden');
            document.getElementById('preview_image').src = '';
            const noLogoBox = document.getElementById('no_logo_placeholder');
            if (noLogoBox) noLogoBox.classList.remove('hidden');
        }

        function confirmRemoveLogo() {
            if (confirm('Are you sure you want to remove the company logo? This will take effect when you save settings.')) {
                let flag = document.getElementById('remove_logo_flag');
                if (!flag) {
                    flag = document.createElement('input');
                    flag.type = 'hidden';
                    flag.name = 'remove_logo';
                    flag.id = 'remove_logo_flag';
                    document.querySelector('form').appendChild(flag);
                }
                flag.value = '1';
                
                const card = document.getElementById('current_logo_card');
                if (card) card.style.display = 'none';
                
                const container = document.getElementById('remove_logo_notice_container');
                if (container) {
                    container.innerHTML = `
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-3 flex items-center justify-between text-xs text-amber-800">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                                Current logo will be removed when you click <strong>Save Settings</strong>.
                            </span>
                            <button type="button" onclick="cancelRemoveLogo()" class="text-xs underline text-amber-900 font-semibold hover:text-black">
                                Undo
                            </button>
                        </div>
                    `;
                }
            }
        }

        function cancelRemoveLogo() {
            const flag = document.getElementById('remove_logo_flag');
            if (flag) flag.value = '0';
            const card = document.getElementById('current_logo_card');
            if (card) card.style.display = 'block';
            const container = document.getElementById('remove_logo_notice_container');
            if (container) container.innerHTML = '';
        }
    </script>
</x-dashboard-layout>
