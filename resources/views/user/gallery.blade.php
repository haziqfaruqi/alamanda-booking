@extends('layouts.app', [
    'title' => 'Gallery - Alamanda Houseboat',
    'transparent' => true,
    'transparentBg' => true,
    'header' => [
        'links' => [
            'Home' => route('home'),
            'Gallery' => route('gallery'),
            'Contact' => '#contact',
        ],
        'ctaHref' => route('booking'),
        'ctaLabel' => 'Book Now',
    ],
])

@section('content')
    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] flex flex-col justify-center items-center text-center px-6 overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/pic/bg_home.jpg') }}" class="w-full h-full object-cover" alt="Lake Background">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/80"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-4xl mx-auto space-y-6">
            <h1 class="text-4xl md:text-6xl font-semibold text-white tracking-tight leading-[1.1]">
                Gallery
            </h1>
            <p class="text-lg text-zinc-300 max-w-xl mx-auto">
                Glimpses of the unforgettable experiences awaiting you at Alamanda Houseboat
            </p>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-24 md:py-32 px-6 max-w-7xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 text-indigo-600 font-medium text-sm tracking-wide uppercase mb-4">
                <iconify-icon icon="lucide:camera" width="16" stroke-width="1.5"></iconify-icon>
                Photo Gallery
            </div>
            <h2 class="text-3xl md:text-4xl font-semibold tracking-tight text-zinc-900 mb-4">
                Experience Alamanda
            </h2>
            <p class="text-zinc-500">
                From stunning sunsets over Kenyir Lake to cozy interiors, see what makes your stay special.
            </p>
        </div>

        <!-- Gallery Grid - 4 Large Images -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Image 1 - Exterior/Deck -->
            <div class="group relative overflow-hidden rounded-3xl shadow-lg">
                <img src="{{ asset('storage/pic/img1.jpeg') }}"
                     alt="Alamanda Houseboat Exterior Deck"
                     class="w-full h-[500px] object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <div class="inline-flex items-center gap-2 text-emerald-400 text-sm font-medium mb-2">
                        <iconify-icon icon="lucide:sun" width="16"></iconify-icon>
                        Exterior
                    </div>
                    <h3 class="text-2xl font-semibold text-white mb-2">Spacious Sun Deck</h3>
                    <p class="text-zinc-300 text-sm">Relax on our open-air deck and take in the breathtaking views of Kenyir Lake. Perfect for morning coffee or evening stargazing.</p>
                </div>
            </div>

            <!-- Image 2 - Living Area -->
            <div class="group relative overflow-hidden rounded-3xl shadow-lg">
                <img src="{{ asset('storage/pic/img3.jpg') }}"
                     alt="Alamanda Houseboat Interior"
                     class="w-full h-[500px] object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <div class="inline-flex items-center gap-2 text-amber-400 text-sm font-medium mb-2">
                        <iconify-icon icon="lucide:home" width="16"></iconify-icon>
                        Interior
                    </div>
                    <h3 class="text-2xl font-semibold text-white mb-2">Cozy Living Space</h3>
                    <p class="text-zinc-300 text-sm">Our air-conditioned interior offers a comfortable retreat with panoramic windows, lounge seating, and entertainment for the whole family.</p>
                </div>
            </div>

            <!-- Image 3 - Lake View -->
            <div class="group relative overflow-hidden rounded-3xl shadow-lg">
                <img src="{{ asset('storage/pic/kenyir1.jpg') }}"
                     alt="Kenyir Lake Scenic View"
                     class="w-full h-[500px] object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <div class="inline-flex items-center gap-2 text-blue-400 text-sm font-medium mb-2">
                        <iconify-icon icon="lucide:waves" width="16"></iconify-icon>
                        Scenery
                    </div>
                    <h3 class="text-2xl font-semibold text-white mb-2">Kenyir Lake Paradise</h3>
                    <p class="text-zinc-300 text-sm">Wake up to serene waters surrounded by lush rainforest. With over 340 islands to explore, every view is a picture-perfect moment.</p>
                </div>
            </div>

            <!-- Image 4 - Water Activities -->
            <div class="group relative overflow-hidden rounded-3xl shadow-lg">
                <img src="{{ asset('storage/pic/img2.jpg') }}"
                     alt="Water Activities at Kenyir"
                     class="w-full h-[500px] object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <div class="inline-flex items-center gap-2 text-cyan-400 text-sm font-medium mb-2">
                        <iconify-icon icon="lucide:anchor" width="16"></iconify-icon>
                        Activities
                    </div>
                    <h3 class="text-2xl font-semibold text-white mb-2">Adventure Awaits</h3>
                    <p class="text-zinc-300 text-sm">Kayak through calm waters, fish for Kelah, or take a dip near a cascading waterfall. Adventure is always within reach.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="py-24 bg-zinc-900 text-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-semibold tracking-tight text-white mb-6">
                Ready to Create Your Own Memories?
            </h2>
            <p class="text-lg text-zinc-400 mb-8">
                Book your stay at Alamanda Houseboat and experience the beauty of Kenyir Lake firsthand.
            </p>
            <a href="{{ route('booking') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-lg bg-white text-zinc-900 text-sm font-medium hover:bg-zinc-200 transition-all">
                Book Your Trip
                <iconify-icon icon="lucide:arrow-right" width="16" stroke-width="1.5"></iconify-icon>
            </a>
        </div>
    </section>
@endsection
