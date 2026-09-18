<x-dashboard-layout>
    <x-slot name="title">System Settings</x-slot>
    <x-slot name="header">System Settings</x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Company Settings</h2>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Company Information -->
                        <div class="mb-8 border-b pb-6">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Company Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                                    <input type="text" name="company_name" value="{{ old('company_name', $setting->company_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" name="company_email" value="{{ old('company_email', $setting->company_email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="text" name="company_phone" value="{{ old('company_phone', $setting->company_phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Logo</label>
                                    @if($setting->company_logo)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $setting->company_logo) }}" alt="Logo" class="h-16 object-contain">
                                        </div>
                                    @endif
                                    <input type="file" name="company_logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Address</label>
                                    <textarea name="company_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('company_address', $setting->company_address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Report Settings (Hide/Show) -->
                        <div class="mb-8 border-b pb-6">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Report Display Settings</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="show_header" id="show_header" value="1" {{ old('show_header', $setting->show_header ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="show_header" class="ml-2 block text-sm text-gray-900">Show Company Header on Report</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="show_patient_info" id="show_patient_info" value="1" {{ old('show_patient_info', $setting->show_patient_info ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="show_patient_info" class="ml-2 block text-sm text-gray-900">Show Patient Information on Report</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="show_footer" id="show_footer" value="1" {{ old('show_footer', $setting->show_footer ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="show_footer" class="ml-2 block text-sm text-gray-900">Show Footer (Doctor Info) on Report</label>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Doctor Information (Footer)</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Doctor Name</label>
                                    <input type="text" name="doctor_name" value="{{ old('doctor_name', $setting->doctor_name) }}" placeholder="e.g. Dr. Ritu Malhotra" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Degrees/Qualifications</label>
                                    <input type="text" name="doctor_degree" value="{{ old('doctor_degree', $setting->doctor_degree) }}" placeholder="e.g. MD (Pathology)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Registration Number</label>
                                    <input type="text" name="doctor_reg_no" value="{{ old('doctor_reg_no', $setting->doctor_reg_no) }}" placeholder="e.g. Reg No.: DMC/2009/12345" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
