<!-- Footer -->
<footer class="bg-white border-t border-zinc-200 py-12 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">

            <!-- Brand -->
            <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-2 mb-3">
                    <img src="{{ asset('storage/pic/logo_alamanda.png') }}" alt="Alamanda Logo" class="w-20 h-20">
                    <span class="text-zinc-900 font-semibold tracking-tight"></span>
                </div>
                <p class="text-sm text-zinc-500">Experience the serenity of Kenyir Lake aboard the Alamanda Houseboat.</p>
            </div>

            <!-- Contact Info -->
            <div class="text-center">
                <h4 class="font-semibold text-zinc-900 mb-3">Contact Us</h4>
                <div class="space-y-2 text-sm text-zinc-500">
                    <a href="tel:+60123456789" class="flex items-center justify-center gap-2 hover:text-zinc-900 transition-colors">
                        <iconify-icon icon="lucide:phone" width="16"></iconify-icon>
                        +60 14-334 8344
                    </a>
                    <a href="mailto:alamandahousebot@gmail.com" class="flex items-center justify-center gap-2 hover:text-zinc-900 transition-colors">
                        <iconify-icon icon="lucide:mail" width="16"></iconify-icon>
                        alamandahousebotkenyir@gmail.com
                    </a>
                </div>
            </div>

            <!-- Social Links -->
            <div class="text-center md:text-right">
                <h4 class="font-semibold text-zinc-900 mb-3">Follow Us</h4>
                <div class="flex items-center justify-center md:justify-end gap-3">
                    <a href="https://facebook.com/alamandahouseboat" target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 flex items-center justify-center rounded-full bg-zinc-100 text-zinc-600 hover:bg-blue-600 hover:text-white transition-all">
                        <iconify-icon icon="lucide:facebook" width="18"></iconify-icon>
                    </a>
                    <a href="https://instagram.com/alamandahouseboat" target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 flex items-center justify-center rounded-full bg-zinc-100 text-zinc-600 hover:bg-pink-600 hover:text-white transition-all">
                        <iconify-icon icon="lucide:instagram" width="18"></iconify-icon>
                    </a>
                </div>
            </div>

        </div>

        <!-- Copyright -->
        <div class="border-t border-zinc-100 pt-6 text-center">
            <p class="text-xs text-zinc-400">&copy; {{ date('Y') }} Alamanda Houseboat. All rights reserved.</p>
        </div>
    </div>
</footer>
