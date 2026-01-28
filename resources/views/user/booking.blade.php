@extends('layouts.app', [
    'title' => 'Book Your Experience - Alamanda Houseboat',
    'header' => [
        'links' => [
            'Home' => '/home',
            'Book Now' => '/booking',
        ],
    ],
])

@push('styles')
<style>
    .package-card { transition: all 0.3s ease; }
    .package-card:hover { transform: translateY(-4px); }
    .package-card.selected { border-color: #4f46e5 !important; background: #eef2ff !important; }

    .tab-btn { transition: all 0.3s ease; }
    .tab-btn.active { background: #4f46e5; color: white; border-color: #4f46e5; }

    .guest-form-item { transition: all 0.3s ease; }
    .guest-form-item:hover { border-color: #d4d4d8; }

    input:focus, select:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .btn-primary {
        background: #4f46e5;
        transition: all 0.3s ease;
    }
    .btn-primary:hover:not(:disabled) {
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
    }
    .btn-primary:disabled {
        background: #d4d4d8;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-secondary {
        background: white;
        border: 1px solid #e4e4e7;
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        background: #fafafa;
        border-color: #d4d4d8;
    }
</style>
@endpush

@section('content')
    <!-- Page Content -->
    <div class="pt-32 pb-24 px-6">
        <div class="max-w-4xl mx-auto">

            <!-- Page Title -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-semibold tracking-tight text-zinc-900 mb-4">Book Your Experience</h1>
                <p class="text-lg text-zinc-500">Choose your package and select your travel dates</p>
            </div>

            @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center gap-3">
                <iconify-icon icon="lucide:check-circle" width="20"></iconify-icon>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                <div class="flex items-center gap-3 mb-2">
                    <iconify-icon icon="lucide:alert-circle" width="20"></iconify-icon>
                    <span class="font-semibold">Please fix the following errors:</span>
                </div>
                <ul class="ml-8 list-disc space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('booking.store') }}">
                @csrf

                <!-- PACKAGE SECTION -->
                <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-zinc-200 mb-8">
                    <h2 class="text-xl font-semibold text-zinc-900 mb-6 flex items-center gap-2">
                        <iconify-icon icon="lucide:package" width="20" class="text-indigo-500"></iconify-icon>
                        Select Your Package
                    </h2>

                    <!-- Package Tabs -->
                    <div class="flex gap-3 mb-8">
                        <button type="button" class="tab-btn active px-6 py-2 rounded-full border border-zinc-200 text-sm font-medium" onclick="showTab('standard',this)">
                            Standard Package
                        </button>
                        <button type="button" class="tab-btn px-6 py-2 rounded-full border border-zinc-200 text-sm font-medium" onclick="showTab('fullboard',this)">
                            Full Board Package
                        </button>
                    </div>

                    <!-- Standard Packages -->
                    <div id="standard" class="tab-content">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @php $standardPackages = \App\Models\Package::where('name', 'like', 'Standard%')->get(); @endphp
                            @foreach($standardPackages as $package)
                            <div class="package-card relative border-2 border-zinc-200 rounded-2xl p-6 cursor-pointer text-center" onclick="selectCard(this, 'standard')">
                                <input type="radio" name="package_id" value="{{ $package->id }}" class="hidden" data-duration="{{ $package->duration }}" required>
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg mx-auto mb-4 flex items-center justify-center">
                                    <iconify-icon icon="lucide:anchor" width="18" class="text-indigo-600"></iconify-icon>
                                </div>
                                <h3 class="font-semibold text-zinc-900">{{ $package->name }}</h3>
                                <p class="package-duration text-sm text-zinc-500 mb-3">{{ $package->duration }}</p>
                                <p class="text-xl font-bold text-indigo-600">RM {{ number_format($package->price_standard, 0, ',', ',') }}</p>
                                <p class="text-xs text-zinc-400 mt-1">per charter</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fullboard Packages -->
                    <div id="fullboard" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @php $fullboardPackages = \App\Models\Package::where('name', 'like', 'Full Board%')->get(); @endphp
                            @foreach($fullboardPackages as $package)
                            <div class="package-card relative border-2 border-zinc-200 rounded-2xl p-6 cursor-pointer text-center" onclick="selectCard(this, 'fullboard')">
                                <input type="radio" name="package_id" value="{{ $package->id }}" class="hidden" data-type="fullboard" data-duration="{{ $package->duration }}">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg mx-auto mb-4 flex items-center justify-center">
                                    <iconify-icon icon="lucide:utensils" width="18" class="text-emerald-600"></iconify-icon>
                                </div>
                                <h3 class="font-semibold text-zinc-900">{{ $package->name }}</h3>
                                <p class="package-duration text-sm text-zinc-500 mb-3">{{ $package->duration }}</p>
                                <div class="mt-3 space-y-1">
                                    <p class="text-sm text-zinc-600">Adult: <span class="font-semibold text-zinc-900">RM {{ number_format($package->price_fullboard_adult, 0) }}</span></p>
                                    <p class="text-sm text-zinc-600">Child: <span class="font-semibold text-zinc-900">RM {{ number_format($package->price_fullboard_child, 0) }}</span></p>
                                </div>
                                <p class="text-xs text-zinc-400 mt-2">per person</p>
                            </div>
                            @endforeach
                        </div>

                        <!-- Guest Count for Fullboard -->
                        <div id="fullboardGuestCount" class="hidden mt-6 p-4 bg-zinc-50 rounded-xl">
                            <p class="text-sm font-medium text-zinc-700 mb-4">Number of Guests (Auto-calculated from IC)</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-600 mb-2">Adults *</label>
                                    <input type="number" name="total_adults" min="1" value="0" readonly class="w-full px-4 py-2.5 border border-zinc-300 rounded-lg text-sm bg-zinc-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-600 mb-2">Children *</label>
                                    <input type="number" name="total_children" min="0" value="0" readonly class="w-full px-4 py-2.5 border border-zinc-300 rounded-lg text-sm bg-zinc-100">
                                </div>
                            </div>
                            <p class="text-xs text-zinc-500 mt-2">Adults: 13+ years | Children: 12 years and below</p>
                        </div>
                    </div>
                </div>

                <!-- DATE SECTION -->
                <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-zinc-200 mb-8">
                    <h2 class="text-xl font-semibold text-zinc-900 mb-6 flex items-center gap-2">
                        <iconify-icon icon="lucide:calendar" width="20" class="text-indigo-500"></iconify-icon>
                        Select Travel Dates
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-2">Check-In Date *</label>
                            <input type="date" name="check_in_date" id="checkInDate" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-2">Check-Out Date *</label>
                            <input type="date" name="check_out_date" id="checkOutDate" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm">
                        </div>
                    </div>

                    <!-- Availability Status -->
                    <div id="availabilityStatus" class="mt-4 hidden rounded-xl p-4 text-sm"></div>
                </div>

                <!-- GUEST SECTION -->
                <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-zinc-200">
                    <h2 class="text-xl font-semibold text-zinc-900 mb-6 flex items-center gap-2">
                        <iconify-icon icon="lucide:users" width="20" class="text-indigo-500"></iconify-icon>
                        Guest Information
                    </h2>

                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl mb-6">
                        <div class="flex items-start gap-3">
                            <iconify-icon icon="lucide:info" width="18" class="text-blue-600 mt-0.5"></iconify-icon>
                            <div>
                                <p class="font-medium text-blue-800">Maximum 30 guests allowed</p>
                                <p class="text-sm text-blue-700">Please provide details for all guests (minimum 1 guest)</p>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="total_guests" id="totalGuests" value="0">

                    <!-- Guest Container -->
                    <div id="guestContainer" class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2"></div>

                    <div class="flex items-center justify-between gap-4 mb-6">
                        <button type="button" onclick="addGuest()" class="btn-secondary flex-1 py-3 rounded-xl text-sm font-medium flex items-center justify-center gap-2">
                            <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                            Add Guest
                        </button>
                        <button type="button" onclick="removeGuest()" class="btn-secondary px-4 py-3 rounded-xl text-sm font-medium">
                            <iconify-icon icon="lucide:minus" width="16"></iconify-icon>
                        </button>
                    </div>

                    <!-- Guest Count Status -->
                    <div id="guestCountStatus" class="text-center mb-4">
                        <span class="inline-flex items-center gap-2 text-zinc-500 text-sm">
                            <iconify-icon icon="lucide:users" width="16"></iconify-icon>
                            <span id="guestCountText">0 / 30 guests</span>
                        </span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" disabled class="btn-primary w-full py-4 rounded-xl text-white font-semibold text-base flex items-center justify-center gap-2">
                        <span>Proceed to Payment</span>
                        <iconify-icon icon="lucide:arrow-right" width="18"></iconify-icon>
                    </button>
                </div>

                <!-- COUPON SECTION -->
                <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-zinc-200 mb-8">
                    <h2 class="text-xl font-semibold text-zinc-900 mb-6 flex items-center gap-2">
                        <iconify-icon icon="lucide:ticket" width="20" class="text-emerald-500"></iconify-icon>
                        Discount Coupon
                    </h2>

                    <div class="flex gap-3">
                        <div class="flex-1">
                            <input type="text" name="coupon_code" id="couponCode" class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm uppercase" placeholder="Enter coupon code (if any)">
                        </div>
                        <button type="button" onclick="validateCoupon()" class="px-6 py-3 bg-zinc-100 text-zinc-700 rounded-xl text-sm font-medium hover:bg-zinc-200 transition-all">
                            Apply
                        </button>
                    </div>
                    <div id="couponStatus" class="mt-3 text-sm"></div>
                    <input type="hidden" name="coupon_id" id="couponId">
                    <input type="hidden" name="discount_amount" id="discountAmount" value="0">
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    let guestCount = 0;
    const guestContainer = document.getElementById("guestContainer");
    const submitBtn = document.getElementById("submitBtn");
    const guestCountText = document.getElementById("guestCountText");
    const totalGuestsInput = document.getElementById("totalGuests");
    const checkInDate = document.getElementById("checkInDate");
    const checkOutDate = document.getElementById("checkOutDate");
    const availabilityStatus = document.getElementById("availabilityStatus");
    const couponCodeInput = document.getElementById("couponCode");
    const couponStatus = document.getElementById("couponStatus");
    const couponIdInput = document.getElementById("couponId");
    const discountAmountInput = document.getElementById("discountAmount");

    let availabilityCheckTimer = null;
    let isDatesAvailable = false;
    let appliedCoupon = null;
    const MAX_GUESTS = 30;

    function showTab(id, el) {
        // Update tab buttons
        document.querySelectorAll(".tab-btn").forEach(t => t.classList.remove("active"));
        el.classList.add("active");

        // Update content
        document.querySelectorAll(".tab-content").forEach(c => c.classList.add("hidden"));
        document.getElementById(id).classList.remove("hidden");

        // Show/hide guest count input for fullboard
        const guestCountDiv = document.getElementById("fullboardGuestCount");
        guestCountDiv.classList.toggle("hidden", id !== "fullboard");
        guestCountDiv.classList.toggle("block", id === "fullboard");
    }

    function selectCard(card, type) {
        // Remove selected class from all cards
        document.querySelectorAll(".package-card").forEach(c => c.classList.remove("selected"));
        card.classList.add("selected");

        // Check the radio button
        const radio = card.querySelector("input[type='radio']");
        if(radio) radio.checked = true;

        // Auto-calculate checkout date based on package duration
        const durationText = radio?.getAttribute('data-duration');
        if(durationText && checkInDate.value) {
            const days = parseDuration(durationText);
            if(days > 0) {
                updateCheckoutDate(days);
            }
        }
    }

    // Parse duration string like "2D1N" to get number of nights
    function parseDuration(duration) {
        // Match pattern like "2D1N", "3D2N", "1D0N" etc.
        // Extract the nights (N) value - e.g., "2D1N" = 1 night, "3D2N" = 2 nights
        const match = duration.match(/(\d+)N/);
        return match ? parseInt(match[1]) : 1; // Default to 1 night if not found
    }

    // Update checkout date based on number of nights
    function updateCheckoutDate(nights) {
        const checkIn = new Date(checkInDate.value);
        const checkOut = new Date(checkIn);
        checkOut.setDate(checkOut.getDate() + nights);
        checkOutDate.value = checkOut.toISOString().split('T')[0];

        // Trigger availability check
        checkAvailability();
    }

    function addGuest() {
        if (guestCount >= MAX_GUESTS) {
            guestCountText.textContent = `${guestCount} / ${MAX_GUESTS} (Maximum reached)`;
            return;
        }

        const isFirstGuest = guestCount === 0;
        const div = document.createElement("div");
        div.className = "guest-form-item border border-zinc-200 rounded-xl p-4";
        div.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-medium text-zinc-900">Guest ${guestCount + 1}</h4>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-600 mb-1.5">Full Name *</label>
                    <input type="text" name="guests[${guestCount}][name]" oninput="validateGuests()" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg text-sm" placeholder="Guest name">
                </div>
                ${isFirstGuest ? `
                <div>
                    <label class="block text-xs font-medium text-zinc-600 mb-1.5">Phone Number *</label>
                    <input type="tel" name="guests[${guestCount}][phone]" oninput="validateGuests()" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg text-sm" placeholder="0123456789">
                </div>
                ` : `
                <div>
                    <label class="block text-xs font-medium text-zinc-600 mb-1.5">IC / Passport Number *</label>
                    <input type="text" name="guests[${guestCount}][id_number]" oninput="validateGuests()" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg text-sm" placeholder="IC/Passport number">
                </div>
                `}
            </div>
            ${isFirstGuest ? `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-600 mb-1.5">Email Address *</label>
                    <input type="email" name="guests[${guestCount}][email]" oninput="validateGuests();" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg text-sm" placeholder="your@email.com">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-600 mb-1.5">ID Type *</label>
                    <select name="guests[${guestCount}][id_type]" onchange="toggleIdInput(this, ${guestCount}); validateGuests();" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg text-sm">
                        <option value="ic">IC (Malaysian)</option>
                        <option value="passport">Passport (Foreigner)</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-zinc-600 mb-1.5" id="id-label-${guestCount}">IC Number *</label>
                    <input type="text" name="guests[${guestCount}][id_number]" oninput="calculateAge(${guestCount}); validateGuests();" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg text-sm" id="id-input-${guestCount}" placeholder="e.g., 010101010101">
                    <p class="text-xs text-zinc-500 mt-1" id="age-display-${guestCount}"></p>
                </div>
                <div class="flex items-end">
                    <div class="w-full">
                        <p class="text-xs text-zinc-500">Adults: 13+ years | Children: 12 years and below</p>
                    </div>
                </div>
            </div>
            <!-- Date of Birth field for passport holders (hidden by default) -->
            <div class="mt-3 hidden" id="dob-container-${guestCount}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-zinc-600 mb-1.5">Date of Birth *</label>
                        <input type="date" name="guests[${guestCount}][date_of_birth]" onchange="calculateAgeFromDob(${guestCount})" class="w-full px-3 py-2 border border-zinc-300 rounded-lg text-sm" id="dob-input-${guestCount}">
                        <p class="text-xs text-zinc-500 mt-1" id="dob-age-display-${guestCount}"></p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="guests[${guestCount}][age]" id="age-hidden-${guestCount}">
            ` : `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-600 mb-1.5">ID Type *</label>
                    <select name="guests[${guestCount}][id_type]" onchange="toggleIdInput(this, ${guestCount}); validateGuests();" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg text-sm">
                        <option value="ic">IC (Malaysian)</option>
                        <option value="passport">Passport (Foreigner)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-600 mb-1.5">Date of Birth *</label>
                    <input type="date" name="guests[${guestCount}][date_of_birth]" onchange="calculateAgeFromDob(${guestCount}); validateGuests();" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg text-sm">
                    <p class="text-xs text-zinc-500 mt-1" id="age-display-${guestCount}"></p>
                </div>
            </div>
            <input type="hidden" name="guests[${guestCount}][age]" id="age-hidden-${guestCount}">
            `}
        `;
        guestContainer.appendChild(div);
        guestCount++;
        validateGuests();
    }

    function toggleIdInput(select, guestNum) {
        const label = document.getElementById(`id-label-${guestNum}`);
        const input = document.getElementById(`id-input-${guestNum}`);
        const dobContainer = document.getElementById(`dob-container-${guestNum}`);
        const ageDisplay = document.getElementById(`age-display-${guestNum}`);

        if (select.value === 'passport') {
            label.textContent = 'Passport Number *';
            input.placeholder = 'e.g., A1234567';
            // Show DOB field for passport holders
            if (dobContainer) dobContainer.classList.remove('hidden');
            ageDisplay.textContent = '';
        } else {
            label.textContent = 'IC Number *';
            input.placeholder = 'e.g., 010101010101';
            // Hide DOB field for IC holders (age calculated from IC)
            if (dobContainer) {
                dobContainer.classList.add('hidden');
                document.getElementById(`dob-input-${guestNum}`).value = '';
            }
        }

        // Clear age hidden input when ID type changes
        document.getElementById(`age-hidden-${guestNum}`).value = '';
        validateGuests();
        updateGuestCounts();
    }

    // Calculate age from date of birth (for passport holders)
    function calculateAgeFromDob(guestNum) {
        const dobInput = document.getElementById(`dob-input-${guestNum}`);
        const ageHidden = document.getElementById(`age-hidden-${guestNum}`);
        // For first guest, use dob-age-display, for others use age-display
        const ageDisplay = document.getElementById(`dob-age-display-${guestNum}`) || document.getElementById(`age-display-${guestNum}`);

        if (dobInput.value) {
            const dob = new Date(dobInput.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            ageHidden.value = age;
            if (ageDisplay) {
                ageDisplay.textContent = `Age: ${age} years old`;
                ageDisplay.className = 'text-xs text-emerald-600 mt-1';
            }
        } else {
            ageHidden.value = '';
            if (ageDisplay) {
                ageDisplay.textContent = '';
            }
        }

        validateGuests();
        updateGuestCounts();
    }

    function calculateAge(guestNum) {
        const idType = document.querySelector(`select[name="guests[${guestNum}][id_type]"]`).value;
        const idNumber = document.getElementById(`id-input-${guestNum}`).value;
        const ageDisplay = document.getElementById(`age-display-${guestNum}`);
        const ageHidden = document.getElementById(`age-hidden-${guestNum}`);

        if (idType === 'ic' && idNumber.length >= 12) {
            // Malaysian IC format: YYMMDD-PB-###G
            // Extract birth year from first 2 digits
            const yy = parseInt(idNumber.substring(0, 2));
            const currentYear = new Date().getFullYear();
            const currentCentury = Math.floor(currentYear / 100) * 100;

            // Determine if born in 1900s or 2000s
            let birthYear = currentCentury + yy;
            if (birthYear > currentYear) {
                birthYear -= 100;
            }

            const age = currentYear - birthYear;
            ageDisplay.textContent = `Age: ${age} years old`;
            ageDisplay.className = 'text-xs text-emerald-600 mt-1';
            ageHidden.value = age;

            // Update guest count after age calculation
            updateGuestCounts();
        } else if (idType === 'passport') {
            // For passport, we can't calculate age from the number
            // You may want to add a separate date of birth field
            ageDisplay.textContent = '';
            ageHidden.value = '';

            // Update guest count after passport input
            updateGuestCounts();
        } else {
            ageDisplay.textContent = '';
            ageHidden.value = '';

            // Update guest count when cleared
            updateGuestCounts();
        }
        validateGuests();
    }

    // Update adults and children count based on ages
    function updateGuestCounts() {
        let adults = 0;
        let children = 0;

        document.querySelectorAll(".guest-form-item").forEach(g => {
            const ageHidden = g.querySelector("input[type='hidden'][name$='[age]']");
            const age = ageHidden ? parseInt(ageHidden.value) : null;

            // If no age data, count as adult by default
            if (age === null || age === NaN || age === '') {
                adults++;
            } else if (age <= 12) {
                children++;
            } else {
                adults++;
            }
        });

        // Update the fullboard guest count inputs
        const adultsInput = document.querySelector("input[name='total_adults']");
        const childrenInput = document.querySelector("input[name='total_children']");

        if (adultsInput) adultsInput.value = adults;
        if (childrenInput) childrenInput.value = children;
    }

    function removeGuest() {
        const guestForms = guestContainer.querySelectorAll(".guest-form-item");
        if (guestForms.length > 0) {
            guestForms[guestForms.length - 1].remove();
            guestCount--;
            validateGuests();
            updateGuestCounts();
        }
    }

    function validateGuests() {
        let filled = 0;
        document.querySelectorAll(".guest-form-item").forEach(g => {
            // Check visible inputs only (name, id_type, id_number)
            // For IC holders: name, id_type (ic), id_number are required
            // For passport holders: name, id_type (passport), id_number, date_of_birth are required
            const inputs = g.querySelectorAll("input:not(.hidden), select:not(.hidden)");
            let allFilled = true;
            inputs.forEach(i => {
                // Skip validation for date_of_birth if it's hidden (IC holders)
                if (i.classList.contains('hidden') || i.closest('.hidden')) {
                    return;
                }
                if (!i.value || i.value.trim() === "") {
                    allFilled = false;
                }
            });
            if (allFilled) filled++;
        });
        totalGuestsInput.value = filled;

        const guestCountTextEl = document.getElementById("guestCountText");
        if (guestCountTextEl) {
            guestCountTextEl.textContent = `${filled} / ${MAX_GUESTS} guests`;
        }

        // Enable submit if at least 1 guest and dates are available
        const canSubmit = filled >= 1 && isDatesAvailable;
        submitBtn.disabled = !canSubmit;

        console.log('Validation:', { filled, isDatesAvailable, canSubmit });

        // Update guest count status styling
        const guestCountStatus = document.getElementById("guestCountStatus");
        if (filled >= 1) {
            guestCountStatus.innerHTML = `
                <span class="inline-flex items-center gap-2 text-emerald-600 text-sm">
                    <iconify-icon icon="lucide:check-circle" width="16"></iconify-icon>
                    <span>${filled} / ${MAX_GUESTS} guests added</span>
                </span>
            `;
        } else {
            guestCountStatus.innerHTML = `
                <span class="inline-flex items-center gap-2 text-zinc-500 text-sm">
                    <iconify-icon icon="lucide:users" width="16"></iconify-icon>
                    <span>${filled} / ${MAX_GUESTS} guests</span>
                </span>
            `;
        }

        // Re-validate coupon if applied
        if (appliedCoupon) {
            validateCoupon();
        }
    }

    // Validate coupon code
    async function validateCoupon() {
        const code = couponCodeInput.value.trim().toUpperCase();

        if (!code) {
            couponStatus.innerHTML = '';
            appliedCoupon = null;
            couponIdInput.value = '';
            discountAmountInput.value = '0';
            return;
        }

        const guestCount = parseInt(totalGuestsInput.value) || 0;

        if (guestCount < 1) {
            couponStatus.innerHTML = `
                <span class="inline-flex items-center gap-2 text-amber-600">
                    <iconify-icon icon="lucide:alert-circle" width="14"></iconify-icon>
                    Please add guest details first
                </span>
            `;
            return;
        }

        couponStatus.innerHTML = `
            <span class="inline-flex items-center gap-2 text-zinc-500">
                <iconify-icon icon="lucide:loader-2" width="14" class="animate-spin"></iconify-icon>
                Validating coupon...
            </span>
        `;

        try {
            const response = await fetch('/api/coupons/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({
                    code: code,
                    guests: guestCount
                })
            });

            const data = await response.json();

            if (data.valid) {
                appliedCoupon = data.coupon;
                couponIdInput.value = code;
                couponStatus.innerHTML = `
                    <span class="inline-flex items-center gap-2 text-emerald-600">
                        <iconify-icon icon="lucide:check-circle" width="14"></iconify-icon>
                        Coupon applied: ${data.coupon.type === 'percentage' ? data.coupon.value + '%' : 'RM ' + data.coupon.value} off
                    </span>
                `;
                couponCodeInput.classList.remove('border-red-300');
                couponCodeInput.classList.add('border-emerald-300');
            } else {
                appliedCoupon = null;
                couponIdInput.value = '';
                discountAmountInput.value = '0';
                couponStatus.innerHTML = `
                    <span class="inline-flex items-center gap-2 text-red-600">
                        <iconify-icon icon="lucide:x-circle" width="14"></iconify-icon>
                        ${data.message}
                    </span>
                `;
                couponCodeInput.classList.remove('border-emerald-300');
                couponCodeInput.classList.add('border-red-300');
            }
        } catch (error) {
            console.error('Coupon validation failed:', error);
            couponStatus.innerHTML = `
                <span class="inline-flex items-center gap-2 text-red-600">
                    <iconify-icon icon="lucide:x-circle" width="14"></iconify-icon>
                    Unable to validate coupon
                </span>
            `;
        }
    }

    // Check availability when dates change
    function checkAvailability() {
        const checkIn = checkInDate.value;
        const checkOut = checkOutDate.value;

        // Clear previous timer
        if(availabilityCheckTimer){
            clearTimeout(availabilityCheckTimer);
        }

        // Reset if dates are incomplete
        if(!checkIn || !checkOut){
            availabilityStatus.classList.add("hidden");
            isDatesAvailable = false;
            validateGuests();
            return;
        }

        // Validate check-out is after check-in
        if(new Date(checkOut) <= new Date(checkIn)){
            availabilityStatus.classList.remove("hidden");
            availabilityStatus.className = "mt-4 rounded-xl p-4 text-sm bg-red-50 text-red-700 border border-red-200";
            availabilityStatus.innerHTML = `
                <div class="flex items-center gap-2">
                    <iconify-icon icon="lucide:alert-circle" width="16"></iconify-icon>
                    Check-out date must be after check-in date.
                </div>
            `;
            isDatesAvailable = false;
            validateGuests();
            return;
        }

        // Show checking status
        availabilityStatus.classList.remove("hidden");
        availabilityStatus.className = "mt-4 rounded-xl p-4 text-sm bg-amber-50 text-amber-700 border border-amber-200";
        availabilityStatus.innerHTML = `
            <div class="flex items-center gap-2">
                <iconify-icon icon="lucide:loader-2" width="16" class="animate-spin"></iconify-icon>
                Checking availability...
            </div>
        `;

        // Debounce API call
        availabilityCheckTimer = setTimeout(async() => {
            try{
                const response = await fetch(`/api/availability/check?check_in_date=${checkIn}&check_out_date=${checkOut}`);

                // Check if response has JSON content
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Invalid response format');
                }

                const data = await response.json();

                // Handle validation errors (422) and other responses
                if(data.available){
                    availabilityStatus.className = "mt-4 rounded-xl p-4 text-sm bg-emerald-50 text-emerald-700 border border-emerald-200";
                    availabilityStatus.innerHTML = `
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="lucide:check-circle" width="16"></iconify-icon>
                            These dates are available for booking!
                        </div>
                    `;
                    isDatesAvailable = true;
                } else {
                    availabilityStatus.className = "mt-4 rounded-xl p-4 text-sm bg-red-50 text-red-700 border border-red-200";
                    availabilityStatus.innerHTML = `
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="lucide:x-circle" width="16"></iconify-icon>
                            ${data.message || 'These dates are not available.'}
                        </div>
                    `;
                    isDatesAvailable = false;
                }
            } catch(error){
                console.error("Availability check failed:", error);
                // Don't show availability status on error, allow booking to proceed
                availabilityStatus.classList.add("hidden");
                isDatesAvailable = true;
            }
            validateGuests();
        }, 500);
    }

    // Add event listeners for date inputs
    checkOutDate.addEventListener("change", checkAvailability);

    // Update minimum check-out date when check-in changes
    checkInDate.addEventListener("change", function(){
        const checkIn = new Date(this.value);
        checkIn.setDate(checkIn.getDate() + 1);
        const minCheckOut = checkIn.toISOString().split('T')[0];
        checkOutDate.min = minCheckOut;
        if(checkOutDate.value && new Date(checkOutDate.value) <= new Date(this.value)){
            checkOutDate.value = minCheckOut;
        }

        // If a package is selected, auto-calculate checkout date
        const selectedCard = document.querySelector(".package-card.selected");
        if(selectedCard) {
            const radio = selectedCard.querySelector("input[type='radio']");
            const durationText = radio?.getAttribute('data-duration');
            if(durationText) {
                const days = parseDuration(durationText);
                if(days > 0) {
                    updateCheckoutDate(days);
                    return; // Skip the rest since updateCheckoutDate handles availability check
                }
            }
        }

        checkAvailability();
    });

    // Pre-select package if URL parameter exists
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');

    if(type === 'fullboard') {
        const fullboardTab = document.querySelector('.tab-btn[onclick*="fullboard"]');
        if(fullboardTab) fullboardTab.click();
    }
</script>
@endpush
