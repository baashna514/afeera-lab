<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display a listing of the companies.
     */
    public function index(Request $request): View
    {
        $query = Company::with('superAdmin')->withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $companies = $query->latest()->paginate(10)->withQueryString();

        return view('owner.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create(): View
    {
        return view('owner.companies.create');
    }

    /**
     * Store a newly created company and its Super Admin user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Company fields
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'string', 'email', 'max:255', 'unique:companies,email'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],

            // Super Admin user fields
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
            'admin_phone' => ['nullable', 'string', 'max:50'],
        ]);

        $logoPath = null;
        if ($request->hasFile('company_logo')) {
            $logoPath = $request->file('company_logo')->store('logos', 'public');
        }

        DB::transaction(function () use ($validated, $logoPath) {
            // Create Company
            $company = Company::create([
                'name' => $validated['company_name'],
                'email' => $validated['company_email'],
                'phone' => $validated['company_phone'] ?? null,
                'address' => $validated['company_address'] ?? null,
                'logo' => $logoPath,
                'status' => $validated['status'],
            ]);

            // Create Company Super Admin User
            User::create([
                'company_id' => $company->id,
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'role' => 'super_admin',
                'phone' => $validated['admin_phone'] ?? null,
                'is_active' => true,
            ]);
        });

        return redirect()
            ->route('owner.companies.index')
            ->with('success', 'Company and Super Admin account created successfully!');
    }

    /**
     * Display the specified company.
     */
    public function show(Company $company): View
    {
        $company->load(['superAdmin', 'users']);

        return view('owner.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company): View
    {
        $company->load('superAdmin');

        return view('owner.companies.edit', compact('company'));
    }

    /**
     * Update the specified company and its super admin details in storage.
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        $superAdmin = $company->superAdmin;

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'string', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($company->id)],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],

            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($superAdmin?->id)],
            'admin_password' => ['nullable', 'string', 'min:8'],
            'admin_phone' => ['nullable', 'string', 'max:50'],
        ]);

        $logoPath = $company->logo;
        if ($request->hasFile('company_logo')) {
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('company_logo')->store('logos', 'public');
        }

        DB::transaction(function () use ($company, $superAdmin, $validated, $logoPath) {
            $company->update([
                'name' => $validated['company_name'],
                'email' => $validated['company_email'],
                'phone' => $validated['company_phone'] ?? null,
                'address' => $validated['company_address'] ?? null,
                'logo' => $logoPath,
                'status' => $validated['status'],
            ]);

            if ($superAdmin) {
                $userData = [
                    'name' => $validated['admin_name'],
                    'email' => $validated['admin_email'],
                    'phone' => $validated['admin_phone'] ?? null,
                ];

                if (! empty($validated['admin_password'])) {
                    $userData['password'] = Hash::make($validated['admin_password']);
                }

                $superAdmin->update($userData);
            }
        });

        return redirect()
            ->route('owner.companies.index')
            ->with('success', 'Company details updated successfully!');
    }

    /**
     * Remove the specified company from storage.
     */
    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()
            ->route('owner.companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}
