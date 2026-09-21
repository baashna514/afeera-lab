<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Get the current company's setting, or return a new empty instance
        $setting = CompanySetting::firstOrNew(['company_id' => auth()->user()->company_id]);

        return view('admin.settings.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'show_header' => 'nullable|in:0,1',
            'show_patient_info' => 'nullable|in:0,1',
            'show_footer' => 'nullable|in:0,1',
            'doctor_name' => 'nullable|string|max:255',
            'doctor_degree' => 'nullable|string|max:255',
            'doctor_reg_no' => 'nullable|string|max:255',
            'default_lab_prefix' => 'nullable|string|max:255',
            'default_referred_by' => 'nullable|string|max:255',
            'default_collection_type' => 'nullable|string|max:255',
            'default_fasting' => 'nullable|string|max:255',
            'default_clinical_info' => 'nullable|string|max:1000',
        ]);

        $setting = CompanySetting::firstOrNew(['company_id' => auth()->user()->company_id]);

        $setting->fill($request->only([
            'company_name',
            'company_email',
            'company_phone',
            'company_address',
            'doctor_name',
            'doctor_degree',
            'doctor_reg_no',
            'default_lab_prefix',
            'default_referred_by',
            'default_collection_type',
            'default_fasting',
            'default_clinical_info',
        ]));

        // Master toggles
        $setting->show_header = $request->input('show_header') == '1';
        $setting->show_patient_info = $request->input('show_patient_info') == '1';
        $setting->show_footer = $request->input('show_footer') == '1';

        // Sub-options for Header
        if ($setting->show_header) {
            $setting->show_header_logo = $request->has('show_header_logo');
            $setting->show_header_company_name = $request->has('show_header_company_name');
            $setting->show_header_address = $request->has('show_header_address');
            $setting->show_header_phone = $request->has('show_header_phone');
            $setting->show_header_email = $request->has('show_header_email');
            $setting->show_header_lab_no = $request->has('show_header_lab_no');
            $setting->show_header_report_date = $request->has('show_header_report_date');
            $setting->show_header_sample_date = $request->has('show_header_sample_date');
            $setting->show_header_report_status = $request->has('show_header_report_status');
        }

        // Sub-options for Patient Info
        if ($setting->show_patient_info) {
            $setting->show_patient_name = $request->has('show_patient_name');
            $setting->show_patient_age_gender = $request->has('show_patient_age_gender');
            $setting->show_patient_id = $request->has('show_patient_id');
            $setting->show_patient_referred_by = $request->has('show_patient_referred_by');
            $setting->show_patient_contact = $request->has('show_patient_contact');
            $setting->show_patient_collection_type = $request->has('show_patient_collection_type');
            $setting->show_patient_fasting = $request->has('show_patient_fasting');
            $setting->show_patient_clinical_info = $request->has('show_patient_clinical_info');
            $setting->show_patient_barcode = $request->has('show_patient_barcode');
        }

        // Sub-options for Footer
        if ($setting->show_footer) {
            $setting->show_footer_qr = $request->has('show_footer_qr');
            $setting->show_footer_signature = $request->has('show_footer_signature');
            $setting->show_footer_doctor_name = $request->has('show_footer_doctor_name');
            $setting->show_footer_doctor_degree = $request->has('show_footer_doctor_degree');
            $setting->show_footer_doctor_reg = $request->has('show_footer_doctor_reg');
            $setting->show_footer_disclaimer = $request->has('show_footer_disclaimer');
        }

        // Handle logo removal via checkbox/button in store
        if ($request->boolean('remove_logo')) {
            if ($setting->company_logo) {
                Storage::disk('public')->delete($setting->company_logo);
                $setting->company_logo = null;
            }
        }

        // Handle new logo upload
        if ($request->hasFile('company_logo')) {
            if ($setting->company_logo) {
                Storage::disk('public')->delete($setting->company_logo);
            }
            $path = $request->file('company_logo')->store('logos', 'public');
            $setting->company_logo = $path;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function removeLogo()
    {
        $setting = CompanySetting::firstOrNew(['company_id' => auth()->user()->company_id]);

        if ($setting->company_logo) {
            Storage::disk('public')->delete($setting->company_logo);
            $setting->company_logo = null;
            $setting->save();
        }

        return redirect()->back()->with('success', 'Company logo removed successfully.');
    }
}
