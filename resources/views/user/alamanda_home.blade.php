@extends('layouts.app', [
    'title' => 'Alamanda Houseboat - Home',
    'transparent' => true,
    'transparentBg' => true,
    'header' => [
        'links' => [
            'Adventure' => '#adventure',
            'Destination' => '#kenyir',
            'Packages' => '#packages',
            'Gallery' => route('gallery'),
        ],
        'ctaHref' => '#packages',
        'ctaLabel' => 'Book Trip',
    ],
])

@section('content')
    <!-- Hero Section -->
    <section id="hero" class="relative h-screen min-h-[600px] flex flex-col justify-center items-center text-center px-6 overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/pic/bg_home.jpg') }}" class="w-full h-full object-cover" alt="Lake Background">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/80"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-4xl mx-auto space-y-8 fade-in">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-white/20 bg-white/5 backdrop-blur-sm text-xs text-white font-medium tracking-wide uppercase">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Available for Summer 2024
            </span>

            <h1 class="text-5xl md:text-7xl font-semibold text-white tracking-tighter leading-[1.1]">
                The best place <br class="hidden md:block" /> is right here.
            </h1>

            <p class="text-lg text-zinc-300 max-w-xl mx-auto leading-relaxed">
                Experience the serenity of Kenyir Lake aboard the Alamanda.
                Your private gateway to 340 islands and cascading waterfalls.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="#packages" class="w-full sm:w-auto px-8 py-3 rounded-lg bg-white text-zinc-900 text-sm font-medium hover:bg-zinc-200 transition-all flex items-center justify-center gap-2">
                    Book Now
                    <iconify-icon icon="lucide:arrow-right" width="16" stroke-width="1.5"></iconify-icon>
                </a>
                <a href="#adventure" class="w-full sm:w-auto px-8 py-3 rounded-lg border border-white/20 bg-white/5 backdrop-blur-sm text-white text-sm font-medium hover:bg-white/10 transition-all">
                    Explore Packages
                </a>
            </div>
        </div>
    </section>

    <!-- Adventure Section -->
    <section id="adventure" class="py-24 md:py-32 px-6 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <!-- Image Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4 translate-y-8">
                    <img src="{{ asset('storage/pic/img1.jpeg') }}" alt="Deck view" class="w-full h-64 object-cover rounded-2xl shadow-sm">
                    <img src="{{ asset('storage/pic/img2.jpg') }}" alt="Water view" class="w-full h-48 object-cover rounded-2xl shadow-sm">
                </div>
                <div class="space-y-4">
                    <img src="{{ asset('storage/pic/img3.jpg') }}" alt="Interior" class="w-full h-48 object-cover rounded-2xl shadow-sm">
                    <img src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800&q=80" alt="Camping" class="w-full h-64 object-cover rounded-2xl shadow-sm">
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 text-indigo-600 font-medium text-sm tracking-wide uppercase">
                    <iconify-icon icon="lucide:compass" width="16" stroke-width="1.5"></iconify-icon>
                    About Us
                </div>
                <h2 class="text-3xl md:text-5xl font-semibold tracking-tight text-zinc-900">
                    Alamanda Houseboat <br> Adventures.
                </h2>
                <blockquote class="pl-4 border-l-2 border-indigo-500 italic text-xl text-zinc-600">
                    "Embark on an Unforgettable Summer Cruise at Kenyir Lake"
                </blockquote>
                <div class="space-y-6 text-zinc-500 leading-relaxed">
                    <p>
                        Set sail on an extraordinary journey through Kenyir Lake. With over 340 islands, cascading waterfalls, and ancient rainforests, your adventure begins the moment you step aboard.
                    </p>
                    <p>
                        Whether you are looking for a peaceful retreat or an action-packed holiday with fishing and jungle trekking, Alamanda provides the perfect floating sanctuary.
                    </p>
                </div>

                <div class="pt-4">
                    <div class="grid grid-cols-2 gap-8 border-t border-zinc-100 pt-8">
                        <div>
                            <p class="text-3xl font-semibold text-zinc-900 tracking-tight">340+</p>
                            <p class="text-sm text-zinc-500 mt-1">Islands to explore</p>
                        </div>
                        <div>
                            <p class="text-3xl font-semibold text-zinc-900 tracking-tight">100%</p>
                            <p class="text-sm text-zinc-500 mt-1">Nature immersion</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kenyir Section (Dark Theme for Contrast) -->
    <section id="kenyir" class="py-24 bg-zinc-900 text-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24 items-center">
                <!-- Text -->
                <div class="lg:col-span-5 space-y-8 order-2 lg:order-1">
                    <div class="inline-flex items-center gap-2 text-emerald-400 font-medium text-sm tracking-wide uppercase">
                        <iconify-icon icon="lucide:map-pin" width="16" stroke-width="1.5"></iconify-icon>
                        The Destination
                    </div>
                    <h2 class="text-3xl md:text-4xl font-semibold tracking-tight">
                        Kenyir Lake Experience
                    </h2>
                    <div class="space-y-6 text-zinc-400 leading-relaxed">
                        <p>
                            Kenyir is a large man-made lake located in Hulu Terengganu, Terengganu, Malaysia. Created in 1985 by the damming of the Kenyir River, it is the largest man-made lake in Southeast Asia.
                        </p>
                        <p>
                            The lake is surrounded by lush tropical rainforest that serves as a habitat for rare flora and fauna. It is also a popular angling destination, home to species like the Giant Snakehead and Kelah.
                        </p>
                    </div>
                    <a href="{{ route('gallery') }}" class="text-sm font-medium text-white underline underline-offset-4 decoration-zinc-600 hover:decoration-white transition-all">
                        View Gallery
                    </a>
                </div>

                <!-- Featured Image -->
                <div class="lg:col-span-7 relative order-1 lg:order-2">
                    <img src="{{ asset('storage/pic/kenyir1.jpg') }}" alt="Kenyir Lake" class="rounded-3xl shadow-2xl border border-white/10 w-full object-cover aspect-video">

                    <!-- Floating Card -->
                    <div class="absolute -bottom-6 -left-6 md:bottom-8 md:left-8 bg-white/10 backdrop-blur-md border border-white/10 p-6 rounded-2xl max-w-xs hidden md:block">
                        <div class="flex items-start gap-4">
                            <div class="bg-emerald-500/20 p-2 rounded-lg text-emerald-400">
                                <iconify-icon icon="lucide:droplets" width="24"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="text-white font-medium text-sm">Lasir Waterfall</h4>
                                <p class="text-zinc-400 text-xs mt-1">One of the many majestic waterfalls accessible by boat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="packages" class="py-24 md:py-32 px-6 bg-zinc-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-semibold tracking-tight text-zinc-900 mb-4">Select your package</h2>
                <p class="text-zinc-500">Choose between a private charter for your group or a per-person full board experience.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">

                <!-- Package 1: Standard (Charter) -->
                <div class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                    <div class="mb-6">
                        <span class="bg-zinc-100 text-zinc-600 px-3 py-1 rounded-full text-xs font-semibold tracking-wide uppercase">Private Charter</span>
                        <h3 class="text-2xl font-semibold mt-4 text-zinc-900 tracking-tight">Standard Package</h3>
                        <p class="text-zinc-500 text-sm mt-2">Perfect for families and private groups renting the whole boat.</p>
                    </div>

                    <div class="space-y-4 mb-8 flex-grow">
                        <!-- Pricing Row -->
                        <div class="flex justify-between items-center py-3 border-b border-zinc-100">
                            <span class="text-zinc-700 font-medium">2 Days 1 Night</span>
                            <span class="text-zinc-900 font-semibold">RM 3,900</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-zinc-100">
                            <span class="text-zinc-700 font-medium">3 Days 2 Nights</span>
                            <span class="text-zinc-900 font-semibold">RM 6,000</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-zinc-100">
                            <span class="text-zinc-700 font-medium">4 Days 3 Nights</span>
                            <span class="text-zinc-900 font-semibold">RM 8,900</span>
                        </div>
                    </div>

                    <a href="{{ route('booking') }}?type=standard" class="w-full block text-center bg-zinc-900 text-white py-3 rounded-lg text-sm font-medium hover:bg-zinc-800 transition-colors">
                        Book Standard
                    </a>
                </div>

                <!-- Package 2: Full Board (Per Pax) -->
                <div class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">POPULAR</div>

                    <div class="mb-6">
                        <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold tracking-wide uppercase">All Inclusive</span>
                        <h3 class="text-2xl font-semibold mt-4 text-zinc-900 tracking-tight">Full Board Package</h3>
                        <p class="text-zinc-500 text-sm mt-2">Includes meals and activities. Price per person.</p>
                    </div>

                    <div class="space-y-4 mb-8 flex-grow">
                        <!-- Pricing Row -->
                        <div class="py-3 border-b border-zinc-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-zinc-700 font-medium">2 Days 1 Night</span>
                            </div>
                            <div class="flex gap-4 text-sm text-zinc-500">
                                <span>Adult: <strong class="text-zinc-900">RM 350</strong></span>
                                <span>Child: <strong class="text-zinc-900">RM 250</strong></span>
                            </div>
                        </div>
                        <div class="py-3 border-b border-zinc-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-zinc-700 font-medium">3 Days 2 Nights</span>
                            </div>
                            <div class="flex gap-4 text-sm text-zinc-500">
                                <span>Adult: <strong class="text-zinc-900">RM 550</strong></span>
                                <span>Child: <strong class="text-zinc-900">RM 350</strong></span>
                            </div>
                        </div>
                         <div class="py-3 border-b border-zinc-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-zinc-700 font-medium">4 Days 3 Nights</span>
                            </div>
                            <div class="flex gap-4 text-sm text-zinc-500">
                                <span>Adult: <strong class="text-zinc-900">RM 550</strong></span>
                                <span>Child: <strong class="text-zinc-900">RM 350</strong></span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('booking') }}?type=fullboard" class="w-full block text-center bg-indigo-600 text-white py-3 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                        Book Full Board
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Activities Grid -->
    <section class="py-24 px-6 border-t border-zinc-200">
        <div class="max-w-6xl mx-auto">
            <h3 class="text-2xl font-semibold tracking-tight text-zinc-900 mb-10 text-center">Included Activities</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <!-- Activity Items -->
                <div class="flex flex-col items-center text-center gap-3 p-4 rounded-xl hover:bg-zinc-50 transition-colors">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                        <iconify-icon icon="lucide:waves" width="24"></iconify-icon>
                    </div>
                    <span class="text-sm font-medium text-zinc-700">Kayaking</span>
                </div>
                <div class="flex flex-col items-center text-center gap-3 p-4 rounded-xl hover:bg-zinc-50 transition-colors">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full">
                        <iconify-icon icon="lucide:binoculars" width="24"></iconify-icon>
                    </div>
                    <span class="text-sm font-medium text-zinc-700">Bird Watching</span>
                </div>
                <div class="flex flex-col items-center text-center gap-3 p-4 rounded-xl hover:bg-zinc-50 transition-colors">
                    <div class="bg-orange-100 text-orange-600 p-3 rounded-full">
                        <iconify-icon icon="lucide:map" width="24"></iconify-icon>
                    </div>
                    <span class="text-sm font-medium text-zinc-700">Island Hopping</span>
                </div>
                <div class="flex flex-col items-center text-center gap-3 p-4 rounded-xl hover:bg-zinc-50 transition-colors">
                    <div class="bg-cyan-100 text-cyan-600 p-3 rounded-full">
                        <iconify-icon icon="lucide:fish" width="24"></iconify-icon>
                    </div>
                    <span class="text-sm font-medium text-zinc-700">Fishing</span>
                </div>
                <div class="flex flex-col items-center text-center gap-3 p-4 rounded-xl hover:bg-zinc-50 transition-colors">
                    <div class="bg-purple-100 text-purple-600 p-3 rounded-full">
                        <iconify-icon icon="lucide:gamepad-2" width="24"></iconify-icon>
                    </div>
                    <span class="text-sm font-medium text-zinc-700">Indoor Games</span>
                </div>
                <div class="flex flex-col items-center text-center gap-3 p-4 rounded-xl hover:bg-zinc-50 transition-colors">
                    <div class="bg-pink-100 text-pink-600 p-3 rounded-full">
                        <iconify-icon icon="lucide:mic" width="24"></iconify-icon>
                    </div>
                    <span class="text-sm font-medium text-zinc-700">Karaoke</span>
                </div>
                <div class="flex flex-col items-center text-center gap-3 p-4 rounded-xl hover:bg-zinc-50 transition-colors">
                    <div class="bg-red-100 text-red-600 p-3 rounded-full">
                        <iconify-icon icon="lucide:flame" width="24"></iconify-icon>
                    </div>
                    <span class="text-sm font-medium text-zinc-700">Barbeque</span>
                </div>
                 <div class="flex flex-col items-center text-center gap-3 p-4 rounded-xl hover:bg-zinc-50 transition-colors">
                    <div class="bg-teal-100 text-teal-600 p-3 rounded-full">
                        <iconify-icon icon="lucide:palmtree" width="24"></iconify-icon>
                    </div>
                    <span class="text-sm font-medium text-zinc-700">Waterfall Swimming</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-24 bg-zinc-900 text-white">
        <div class="w-full px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-medium tracking-tight text-white mb-4">
                    Loved by Our Guests
                </h2>
                <p class="text-lg text-zinc-400">Real experiences from those who sailed with us</p>
            </div>

            @if($testimonials->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @foreach($testimonials as $testimonial)
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/10">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center text-white font-semibold text-lg">
                            {{ strtoupper(substr($testimonial->user->name ?? 'G', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="font-medium text-white">{{ $testimonial->user->name ?? 'Guest' }}</h4>
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <iconify-icon icon="lucide:star" width="14"
                                            class="@if($i <= $testimonial->rating) text-yellow-400 @else text-zinc-600 @endif"
                                            @if($i <= $testimonial->rating) style="fill: currentColor;" @endif>
                                        </iconify-icon>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-zinc-400">
                                {{ $testimonial->created_at->format('M Y') }}
                                @if($testimonial->package)
                                    · {{ $testimonial->package->name ?? 'Package' }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <p class="text-sm text-zinc-300 leading-relaxed">
                        "{{ $testimonial->feedback }}"
                    </p>
                    @if($testimonial->admin_reply)
                    <div class="mt-4 p-3 bg-white/5 rounded-lg border-l-2 border-emerald-400">
                        <p class="text-xs font-medium text-emerald-400 mb-1">Admin Reply:</p>
                        <p class="text-sm text-zinc-300">{{ $testimonial->admin_reply }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center text-zinc-400 py-12">
                <iconify-icon icon="lucide:quote" width="48" class="mb-4 opacity-50"></iconify-icon>
                <p>No reviews yet. Be the first to share your experience!</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Exclusions & Terms -->
    <section class="bg-zinc-50 py-20 px-6">
        <div class="max-w-4xl mx-auto space-y-16">

            <!-- Excluded -->
            <div class="bg-white border border-dashed border-zinc-300 rounded-2xl p-8 text-center">
                <h4 class="text-lg font-medium text-zinc-900 mb-6 flex items-center justify-center gap-2">
                    <iconify-icon icon="lucide:alert-circle" class="text-amber-500"></iconify-icon>
                    Packages Exclude
                </h4>
                <div class="flex flex-wrap justify-center gap-4 md:gap-12 text-sm text-zinc-600">
                    <span class="flex items-center gap-2">
                        <iconify-icon icon="lucide:x" class="text-red-400"></iconify-icon> Gawi Jetty Parking
                    </span>
                    <span class="flex items-center gap-2">
                        <iconify-icon icon="lucide:x" class="text-red-400"></iconify-icon> Entrance Tickets
                    </span>
                    <span class="flex items-center gap-2">
                        <iconify-icon icon="lucide:x" class="text-red-400"></iconify-icon> Insurance
                    </span>
                </div>
            </div>

            <!-- Terms -->
            <div>
                <h2 class="text-xl font-semibold text-zinc-900 mb-6 text-center">Important Information</h2>
                <div class="grid md:grid-cols-3 gap-6 text-sm text-zinc-600">
                    <div class="bg-white p-6 rounded-xl border border-zinc-100 shadow-sm">
                        <div class="font-medium text-zinc-900 mb-2">Departure</div>
                        Summer Cruise departs from Gawi Jetty at <span class="text-zinc-900">1:00 PM</span> sharp.
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-zinc-100 shadow-sm">
                        <div class="font-medium text-zinc-900 mb-2">Check-in / Out</div>
                        Check-in: <span class="text-zinc-900">2:30 PM</span><br>
                        Check-out: <span class="text-zinc-900">9:30 AM</span>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-zinc-100 shadow-sm">
                        <div class="font-medium text-zinc-900 mb-2">Payment Policy</div>
                        Full payment is required to confirm booking. No refunds upon cancellation.
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
