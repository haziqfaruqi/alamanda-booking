<!-- Dynamic Navigation - Alpine.js powered scroll detection -->
@php
    $canBeTransparent = $transparent ?? false;
@endphp

<nav
    x-data="{
        scrolled: false,
        userMenuOpen: false,
        init() {
            // Check initial scroll position
            this.scrolled = window.scrollY > 10;
            // Listen for scroll events
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 10;
            }, { passive: true });
        },
        toggleUserMenu() {
            this.userMenuOpen = !this.userMenuOpen;
        },
        closeUserMenu() {
            this.userMenuOpen = false;
        }
    }"
    class="fixed top-0 w-full z-50 h-16 transition-all duration-300"
    :class="{
        'bg-transparent': {{ $canBeTransparent ? 'true' : 'false' }} && !scrolled,
        'bg-white shadow-md': ({{ $canBeTransparent ? 'true' : 'false' }} && scrolled) || !{{ $canBeTransparent ? 'true' : 'false' }}
    }"
>
    <div class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ url('/home') }}"
           class="font-semibold tracking-tighter text-lg flex items-center gap-2 transition-colors duration-300"
           :class="{
               'text-white': {{ $canBeTransparent ? 'true' : 'false' }} && !scrolled,
               'text-zinc-900': ({{ $canBeTransparent ? 'true' : 'false' }} && scrolled) || !{{ $canBeTransparent ? 'true' : 'false' }}
           }">
            <img src="{{ asset('storage/pic/logo_alamanda.png') }}" alt="Alamanda Logo" class="w-20 h-20" />
            
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-8">
            @if(isset($links) && is_array($links))
                @foreach($links as $label => $href)
                    <a href="{{ $href }}"
                       class="text-sm font-medium transition-colors duration-300 hover:text-orange-500"
                       :class="{
                           'text-white': {{ $canBeTransparent ? 'true' : 'false' }} && !scrolled,
                           'text-zinc-600': ({{ $canBeTransparent ? 'true' : 'false' }} && scrolled) || !{{ $canBeTransparent ? 'true' : 'false' }}
                       }">
                        {{ $label }}
                    </a>
                @endforeach
            @endif
        </div>

        <!-- Right Navigation -->
        <div class="flex items-center gap-3" @click.away="closeUserMenu">

            <!-- Book Trip Button (separate container) -->
            @if(isset($ctaHref) && isset($ctaLabel))
                <a href="{{ $ctaHref }}"
                   class="px-4 py-2 rounded-full text-xs font-medium transition-all duration-300"
                   :class="{
                       'bg-orange-500 text-white hover:bg-orange-600': {{ $canBeTransparent ? 'true' : 'false' }} && !scrolled,
                       'bg-zinc-900 text-white hover:bg-zinc-800': ({{ $canBeTransparent ? 'true' : 'false' }} && scrolled) || !{{ $canBeTransparent ? 'true' : 'false' }}
                   }">
                    {{ $ctaLabel }}
                </a>
            @endif

            @auth
                <!-- User Dropdown Container (separate, with relative positioning) -->
                <div class="relative">
                    <!-- User Menu Button -->
                    <button
                        @click="toggleUserMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-full transition-all duration-300"
                        :class="{
                            'text-white hover:bg-white/10': {{ $canBeTransparent ? 'true' : 'false' }} && !scrolled,
                            'text-zinc-700 hover:bg-zinc-100': ({{ $canBeTransparent ? 'true' : 'false' }} && scrolled) || !{{ $canBeTransparent ? 'true' : 'false' }}
                       }">
                        <iconify-icon icon="lucide:user" width="18" stroke-width="1.5"></iconify-icon>
                        <span class="text-sm font-medium">{{ auth()->user()->name ?? 'Account' }}</span>
                        <iconify-icon icon="lucide:chevron-down" width="16" stroke-width="1.5" class="transition-transform duration-200"
                            :class="{ 'rotate-180': userMenuOpen }"></iconify-icon>
                    </button>

                    <!-- Dropdown Menu (anchored to the relative div above) -->
                    <div
                        x-show="userMenuOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-full mt-4 w-56 bg-white rounded-xl shadow-lg border border-zinc-200 py-2 origin-top-right z-50"
                        style="display: none;">
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-zinc-100">
                            <p class="text-sm font-semibold text-zinc-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <!-- Menu Items -->
                        <a href="{{ route('view-booking.view') }}"
                           @click="closeUserMenu()"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">
                            <iconify-icon icon="lucide:calendar" width="16" class="text-zinc-400"></iconify-icon>
                            My Bookings
                        </a>
                        <a href="{{ url('/edit_profile') }}"
                           @click="closeUserMenu()"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">
                            <iconify-icon icon="lucide:settings" width="16" class="text-zinc-400"></iconify-icon>
                            Edit Profile
                        </a>
                        <div class="border-t border-zinc-100 my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                <iconify-icon icon="lucide:log-out" width="16" class="text-red-400"></iconify-icon>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Login Link for guests -->
                <a href="{{ route('login') }}"
                   class="text-sm font-medium transition-colors duration-300 hover:text-orange-500"
                   :class="{
                       'text-white': {{ $canBeTransparent ? 'true' : 'false' }} && !scrolled,
                       'text-zinc-600': ({{ $canBeTransparent ? 'true' : 'false' }} && scrolled) || !{{ $canBeTransparent ? 'true' : 'false' }}
                   }">Log in</a>
            @endauth

        </div>
    </div>
</nav>
