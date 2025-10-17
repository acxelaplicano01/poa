<x-rk.flux.layouts.auth.simple :title="$title ?? null">
    <div class="min-h-screen flex flex-col justify-center items-center bg-white text-gray-800 dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900 dark:text-gray-200">
        <div class="text-center max-w-xl px-4">
            <div class="flex justify-center mb-6">
                {{-- Ícono estilo Laravel --}}
                <svg class="w-16 h-16 text-gray-900" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2l9 4.9v9.8L12 22l-9-5.3V6.9L12 2z" />
                </svg>
            </div>

            <h1 class="text-3xl font-bold mb-4">Welcome to Our Platform</h1>
            <p class="text-gray-600 mb-8">A modern solution to manage everything in one place. Start today and
                discover the difference.</p>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('register') }}"
                    class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">Get Started</a>
                <a href="{{ route('login') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">Login</a>
            </div>
        </div>

        <footer class="mt-16 text-sm text-gray-400">
            © {{ now()->year }} Your Company. All rights reserved.
        </footer>
    </div>
</x-rk.flux.layouts.auth.simple>