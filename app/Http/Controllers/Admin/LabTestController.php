<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LabTestController extends Controller
{
    /**
     * Display a listing of the lab tests.
     */
    public function index(Request $request): View
    {
        $query = LabTest::withCount(['parameters', 'notes']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tests = $query->latest()->paginate(12)->withQueryString();

        $categories = LabTest::select('category')->distinct()->pluck('category');

        return view('admin.tests.index', compact('tests', 'categories'));
    }

    /**
     * Show the form for creating a new lab test.
     */
    public function create(): View
    {
        $existingCategories = LabTest::select('category')->distinct()->pluck('category');

        return view('admin.tests.create', compact('existingCategories'));
    }

    /**
     * Store a newly created lab test in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],

            // Sub-test parameters validation
            'parameters' => ['nullable', 'array'],
            'parameters.*.name' => ['required', 'string', 'max:255'],
            'parameters.*.unit' => ['nullable', 'string', 'max:100'],
            'parameters.*.min_range' => ['nullable', 'string', 'max:100'],
            'parameters.*.max_range' => ['nullable', 'string', 'max:100'],
            'parameters.*.normal_range_text' => ['nullable', 'string', 'max:255'],
            'parameters.*.method' => ['nullable', 'string', 'max:255'],
            'parameters.*.default_value' => ['nullable', 'string', 'max:255'],

            // Dynamic Report Notes validation
            'notes' => ['nullable', 'array'],
            'notes.*.note_text' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $companyId = $request->user()->company_id;

            $test = LabTest::create([
                'company_id' => $companyId,
                'category' => $validated['category'],
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
                'price' => $validated['price'],
                'status' => $validated['status'],
            ]);

            // Create Sub-test Parameters
            if (! empty($validated['parameters'])) {
                foreach ($validated['parameters'] as $index => $param) {
                    $test->parameters()->create([
                        'company_id' => $companyId,
                        'name' => $param['name'],
                        'unit' => $param['unit'] ?? null,
                        'min_range' => $param['min_range'] ?? null,
                        'max_range' => $param['max_range'] ?? null,
                        'normal_range_text' => $param['normal_range_text'] ?? null,
                        'method' => $param['method'] ?? null,
                        'default_value' => $param['default_value'] ?? null,
                        'sort_order' => $index + 1,
                    ]);
                }
            }

            // Create Dynamic Report Notes
            if (! empty($validated['notes'])) {
                foreach ($validated['notes'] as $index => $note) {
                    if (! empty(trim($note['note_text']))) {
                        $test->notes()->create([
                            'company_id' => $companyId,
                            'note_text' => $note['note_text'],
                            'sort_order' => $index + 1,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.tests.index')
            ->with('success', 'Lab Test created successfully with parameters and report notes!');
    }

    /**
     * Display the specified lab test.
     */
    public function show(LabTest $test): View
    {
        $test->load(['parameters', 'notes']);

        return view('admin.tests.show', compact('test'));
    }

    /**
     * Show the form for editing the specified lab test.
     */
    public function edit(LabTest $test): View
    {
        $test->load(['parameters', 'notes']);
        $existingCategories = LabTest::select('category')->distinct()->pluck('category');

        return view('admin.tests.edit', compact('test', 'existingCategories'));
    }

    /**
     * Update the specified lab test in storage.
     */
    public function update(Request $request, LabTest $test): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],

            'parameters' => ['nullable', 'array'],
            'parameters.*.name' => ['required', 'string', 'max:255'],
            'parameters.*.unit' => ['nullable', 'string', 'max:100'],
            'parameters.*.min_range' => ['nullable', 'string', 'max:100'],
            'parameters.*.max_range' => ['nullable', 'string', 'max:100'],
            'parameters.*.normal_range_text' => ['nullable', 'string', 'max:255'],
            'parameters.*.method' => ['nullable', 'string', 'max:255'],
            'parameters.*.default_value' => ['nullable', 'string', 'max:255'],

            'notes' => ['nullable', 'array'],
            'notes.*.note_text' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($test, $validated, $request) {
            $companyId = $request->user()->company_id;

            $test->update([
                'category' => $validated['category'],
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
                'price' => $validated['price'],
                'status' => $validated['status'],
            ]);

            // Sync Parameters
            $test->parameters()->delete();
            if (! empty($validated['parameters'])) {
                foreach ($validated['parameters'] as $index => $param) {
                    $test->parameters()->create([
                        'company_id' => $companyId,
                        'name' => $param['name'],
                        'unit' => $param['unit'] ?? null,
                        'min_range' => $param['min_range'] ?? null,
                        'max_range' => $param['max_range'] ?? null,
                        'normal_range_text' => $param['normal_range_text'] ?? null,
                        'method' => $param['method'] ?? null,
                        'default_value' => $param['default_value'] ?? null,
                        'sort_order' => $index + 1,
                    ]);
                }
            }

            // Sync Dynamic Notes
            $test->notes()->delete();
            if (! empty($validated['notes'])) {
                foreach ($validated['notes'] as $index => $note) {
                    if (! empty(trim($note['note_text']))) {
                        $test->notes()->create([
                            'company_id' => $companyId,
                            'note_text' => $note['note_text'],
                            'sort_order' => $index + 1,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.tests.index')
            ->with('success', 'Lab Test updated successfully!');
    }

    /**
     * Remove the specified lab test from storage.
     */
    public function destroy(LabTest $test): RedirectResponse
    {
        $test->delete();

        return redirect()
            ->route('admin.tests.index')
            ->with('success', 'Lab Test deleted successfully.');
    }
}
