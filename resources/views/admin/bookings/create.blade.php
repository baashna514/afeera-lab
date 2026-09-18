<x-dashboard-layout>
    <x-slot name="title">New Booking</x-slot>
    <x-slot name="header">New Patient Registration & Booking</x-slot>

    <div x-data="bookingForm()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Patient Details Form -->
        <div class="lg:col-span-2 space-y-6">
            <form id="booking-form" action="{{ route('admin.bookings.store') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Alpine hidden inputs for tests -->
                <template x-for="test in cart" :key="test.id">
                    <input type="hidden" name="test_ids[]" :value="test.id">
                </template>
                <input type="hidden" name="discount" x-model="discount">
                <input type="hidden" name="paid_amount" x-model="paidAmount">

                <!-- Patient Information -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                        <h3 class="font-semibold text-slate-800"><i class="fa-solid fa-user mr-2 text-indigo-500"></i>Patient Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Patient Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="patient_name" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('patient_name') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                            <input type="text" name="patient_phone" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Age (Years)</label>
                            <input type="number" name="patient_age" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Gender</label>
                            <select name="patient_gender" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Test Selection -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800"><i class="fa-solid fa-flask-vial mr-2 text-indigo-500"></i>Available Tests</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <input type="text" x-model="searchQuery" placeholder="Search tests by name or code..." class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                        <template x-for="test in filteredTests" :key="test.id">
                            <div class="border border-slate-200 rounded-lg p-3 hover:border-indigo-300 hover:bg-indigo-50/30 transition-colors flex justify-between items-center cursor-pointer"
                                 @click="addToCart(test)">
                                <div>
                                    <h4 class="font-medium text-slate-800 text-sm" x-text="test.name"></h4>
                                    <p class="text-xs text-slate-500 mt-0.5"><span x-text="test.category"></span> <span x-show="test.code" x-text="'• ' + test.code"></span></p>
                                </div>
                                <div class="text-right">
                                    <span class="font-semibold text-slate-800 text-sm" x-text="'Rs. ' + test.price"></span>
                                    <div class="mt-1">
                                        <i class="fa-solid fa-plus-circle text-indigo-500"></i>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Cart Sidebar -->
        <div class="bg-slate-800 rounded-xl shadow-lg border border-slate-700 text-white overflow-hidden flex flex-col h-max sticky top-6">
            <div class="p-5 border-b border-slate-700 bg-slate-900/50">
                <h3 class="font-semibold text-white"><i class="fa-solid fa-cart-shopping mr-2 text-indigo-400"></i>Billing Cart</h3>
            </div>
            
            <div class="p-5 flex-1 min-h-[200px]">
                <template x-if="cart.length === 0">
                    <div class="text-center py-10 text-slate-400">
                        <i class="fa-solid fa-cart-arrow-down text-3xl mb-3 opacity-50"></i>
                        <p class="text-sm">No tests selected yet.</p>
                    </div>
                </template>

                <ul class="space-y-3">
                    <template x-for="item in cart" :key="item.id">
                        <li class="flex justify-between items-start pb-3 border-b border-slate-700 last:border-0 last:pb-0">
                            <div>
                                <p class="text-sm font-medium" x-text="item.name"></p>
                                <button type="button" @click="removeFromCart(item.id)" class="text-xs text-rose-400 hover:text-rose-300 mt-1"><i class="fa-solid fa-trash mr-1"></i>Remove</button>
                            </div>
                            <span class="text-sm font-medium" x-text="'Rs. ' + item.price"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="p-5 bg-slate-900/80 border-t border-slate-700 space-y-4">
                <div class="flex justify-between items-center text-sm text-slate-300">
                    <span>Subtotal</span>
                    <span x-text="'Rs. ' + subtotal"></span>
                </div>
                
                <div class="flex justify-between items-center">
                    <label class="text-sm text-slate-300">Discount (Rs.)</label>
                    <input type="number" x-model="discount" class="w-24 text-right rounded-lg bg-slate-800 border-slate-600 text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-1">
                </div>
                
                <div class="flex justify-between items-center border-t border-slate-700 pt-3">
                    <span class="font-semibold text-lg">Total Amount</span>
                    <span class="font-bold text-lg text-emerald-400" x-text="'Rs. ' + total"></span>
                </div>

                <div class="flex justify-between items-center">
                    <label class="text-sm text-slate-300">Paid Amount (Rs.)</label>
                    <input type="number" x-model="paidAmount" class="w-24 text-right rounded-lg bg-slate-800 border-slate-600 text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-1">
                </div>

                <button type="button" @click="document.getElementById('booking-form').submit()" :disabled="cart.length === 0" 
                        class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-check-circle mr-2"></i> Confirm Booking
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bookingForm', () => ({
                availableTests: @json($labTests),
                searchQuery: '',
                cart: [],
                discount: 0,
                paidAmount: 0,
                
                get filteredTests() {
                    if (this.searchQuery === '') return this.availableTests;
                    return this.availableTests.filter(test => {
                        return test.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                               (test.code && test.code.toLowerCase().includes(this.searchQuery.toLowerCase()));
                    });
                },
                
                addToCart(test) {
                    if (!this.cart.find(item => item.id === test.id)) {
                        this.cart.push(test);
                        this.updateTotals();
                    }
                },
                
                removeFromCart(id) {
                    this.cart = this.cart.filter(item => item.id !== id);
                    this.updateTotals();
                },

                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + parseFloat(item.price || 0), 0).toFixed(2);
                },

                get total() {
                    let calc = parseFloat(this.subtotal) - parseFloat(this.discount || 0);
                    return calc > 0 ? calc.toFixed(2) : '0.00';
                },
                
                updateTotals() {
                    this.paidAmount = this.total;
                },

                init() {
                    this.$watch('discount', (value) => {
                        this.updateTotals();
                    });
                }
            }));
        });
    </script>
</x-dashboard-layout>
