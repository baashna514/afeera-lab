<x-dashboard-layout>
    <x-slot name="title">Edit Test - {{ $test->name }}</x-slot>
    <x-slot name="header">Edit Laboratory Test & Sub-tests</x-slot>

    <div class="max-w-5xl mx-auto" x-data="testForm()">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('admin.tests.index') }}" class="inline-flex items-center space-x-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Tests Catalog</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.tests.update', $test) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Main Test General Information -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="fa-solid fa-vial"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">1. Main Test General Information</h3>
                        <p class="text-xs text-slate-500">Update main test category, name, code, and pricing</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Test Category -->
                    <div>
                        <label for="category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Category / Heading <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="category" id="category" value="{{ old('category', $test->category) }}" required
                               list="categories_list"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('category') border-rose-500 @enderror">
                        <datalist id="categories_list">
                            @foreach($existingCategories as $cat)
                                <option value="{{ $cat }}"></option>
                            @endforeach
                        </datalist>
                        @error('category')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Test Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Main Test Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $test->name) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('name') border-rose-500 @enderror">
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Test Code -->
                    <div>
                        <label for="code" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Short Code / Abbreviation
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code', $test->code) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('code') border-rose-500 @enderror">
                        @error('code')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Test Fee / Price (PKR) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price', $test->price) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all @error('price') border-rose-500 @enderror">
                        @error('price')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="active" {{ old('status', $test->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $test->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Sub-Tests / Parameters Repeater -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">2. Sub-Tests & Attributes (Parameters)</h3>
                            <p class="text-xs text-slate-500">Add sub-tests like WBC, HGB, RBC with optional unit, normal range, & method</p>
                        </div>
                    </div>

                    <button type="button" @click="addParameter()" class="inline-flex items-center space-x-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold text-xs px-3.5 py-2 rounded-xl transition-colors border border-blue-200">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Add Sub-Test</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(param, index) in parameters" :key="index">
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 relative group transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-slate-600 bg-white px-2.5 py-1 rounded border border-slate-200" x-text="'Sub-test #' + (index + 1)"></span>
                                <button type="button" @click="removeParameter(index)" class="text-rose-500 hover:text-rose-700 p-1 rounded hover:bg-rose-50 transition-colors" title="Remove Sub-test">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
                                <!-- Parameter Name -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Sub-Test Name <span class="text-rose-500">*</span></label>
                                    <input type="text" :name="'parameters['+index+'][name]'" x-model="param.name" required 
                                           placeholder="e.g. Hemoglobin (HGB) or WBC" 
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                </div>

                                <!-- Unit (Optional) -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Unit <span class="text-slate-400 font-normal">(Optional)</span></label>
                                    <input type="text" :name="'parameters['+index+'][unit]'" x-model="param.unit" 
                                           placeholder="e.g. g/dl, %, 10^3/µl" 
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                </div>

                                <!-- Test Method (Optional) -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Method <span class="text-slate-400 font-normal">(Optional)</span></label>
                                    <input type="text" :name="'parameters['+index+'][method]'" x-model="param.method" 
                                           placeholder="e.g. By Rapid Method, Automated" 
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                </div>
                            </div>

                            <!-- Range Row: General, Male, Female -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-slate-200/60">
                                <!-- General Normal Range -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                                        <i class="fa-solid fa-users text-slate-400 mr-1"></i> General Normal Range
                                    </label>
                                    <input type="text" :name="'parameters['+index+'][normal_range_text]'" x-model="param.normal_range_text" 
                                           placeholder="e.g. 11.9-15.9 or 4.0-10.0" 
                                           class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                </div>

                                <!-- Male Normal Range -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-blue-700 uppercase mb-1">
                                        <i class="fa-solid fa-mars text-blue-500 mr-1"></i> Male Range (Optional)
                                    </label>
                                    <input type="text" :name="'parameters['+index+'][male_range]'" x-model="param.male_range" 
                                           placeholder="e.g. 13.5-17.5" 
                                           class="w-full px-3 py-1.5 bg-blue-50/40 border border-blue-200 rounded-lg text-xs text-slate-800 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                </div>

                                <!-- Female Normal Range -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-pink-700 uppercase mb-1">
                                        <i class="fa-solid fa-venus text-pink-500 mr-1"></i> Female Range (Optional)
                                    </label>
                                    <input type="text" :name="'parameters['+index+'][female_range]'" x-model="param.female_range" 
                                           placeholder="e.g. 12.0-15.5" 
                                           class="w-full px-3 py-1.5 bg-pink-50/40 border border-pink-200 rounded-lg text-xs text-slate-800 focus:ring-2 focus:ring-pink-500 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="parameters.length === 0" class="p-6 text-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                        <i class="fa-solid fa-list-check text-2xl text-slate-300 mb-2"></i>
                        <p class="text-xs text-slate-500 font-medium">No sub-tests added yet.</p>
                        <button type="button" @click="addParameter()" class="mt-2 text-xs font-bold text-indigo-600 hover:underline">
                            + Click to Add Sub-Test Parameter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section 3: Dynamic Report Notes / Descriptions Repeater -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-lg">
                            <i class="fa-solid fa-note-sticky"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">3. Dynamic Report Notes & Descriptions</h3>
                            <p class="text-xs text-slate-500">Add custom notes or descriptions to print at the bottom of the final report (e.g. for Serology or Blood Cross Match)</p>
                        </div>
                    </div>

                    <button type="button" @click="addNote()" class="inline-flex items-center space-x-2 bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-xs px-3.5 py-2 rounded-xl transition-colors border border-amber-200">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Add Note Line</span>
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(note, index) in notes" :key="index">
                        <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="text-xs font-bold text-slate-500 pt-2.5" x-text="'Note ' + (index + 1) + ':'"></span>
                            <div class="flex-1">
                                <textarea :name="'notes['+index+'][note_text]'" x-model="note.note_text" rows="2" required 
                                          placeholder="Enter custom note line or description" 
                                          class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-2 focus:ring-amber-500 focus:outline-none"></textarea>
                            </div>
                            <button type="button" @click="removeNote(index)" class="text-rose-500 hover:text-rose-700 p-2 rounded hover:bg-rose-50 transition-colors pt-2.5" title="Remove Note">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </template>

                    <div x-show="notes.length === 0" class="p-6 text-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                        <i class="fa-solid fa-note-sticky text-2xl text-slate-300 mb-2"></i>
                        <p class="text-xs text-slate-500 font-medium">No custom notes added for this test.</p>
                        <button type="button" @click="addNote()" class="mt-2 text-xs font-bold text-amber-600 hover:underline">
                            + Click to Add Dynamic Note Line
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Button Footer -->
            <div class="flex items-center justify-end space-x-4 pt-2">
                <a href="{{ route('admin.tests.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-6 py-3 rounded-xl transition-all shadow-md shadow-indigo-600/30">
                    <i class="fa-solid fa-save text-xs"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function testForm() {
            return {
                parameters: @json($test->parameters),
                notes: @json($test->notes),
                addParameter() {
                    this.parameters.push({ name: '', unit: '', normal_range_text: '', male_range: '', female_range: '', method: '' });
                },
                removeParameter(index) {
                    this.parameters.splice(index, 1);
                },
                addNote() {
                    this.notes.push({ note_text: '' });
                },
                removeNote(index) {
                    this.notes.splice(index, 1);
                }
            }
        }
    </script>
</x-dashboard-layout>
