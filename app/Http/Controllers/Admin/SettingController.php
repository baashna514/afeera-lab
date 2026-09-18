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
            'show_header' => 'boolean',
            'show_patient_info' => 'boolean',
            'show_footer' => 'boolean',
            'doctor_name' => 'nullable|string|max:255',
            'doctor_degree' => 'nullable|string|max:255',
            'doctor_reg_no' => 'nullable|string|max:255',
        ]);

        $setting = CompanySetting::firstOrNew(['company_id' => auth()->user()->company_id]);

        $setting->fill($request->except(['company_logo', 'show_header', 'show_patient_info', 'show_footer']));

        $setting->show_header = $request->has('show_header');
        $setting->show_patient_info = $request->has('show_patient_info');
        $setting->show_footer = $request->has('show_footer');

        if ($request->hasFile('company_logo')) {
            // Delete old logo if it exists
            if ($setting->company_logo) {
                Storage::disk('public')->delete($setting->company_logo);
            }
            $path = $request->file('company_logo')->store('logos', 'public');
            $setting->company_logo = $path;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
