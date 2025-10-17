@props([
    'title' => 'Bienvenido a DesignAli Creative Suite',
    'description' =>
        'Libera tu creatividad con nuestra suite completa de herramientas de diseño profesional y recursos.',
    'gradientFrom' => 'from-violet-600',
    'gradientVia' => 'via-indigo-600',
    'gradientTo' => 'to-blue-600',
    'buttonPrimaryText' => 'Explorar Planes',
    'buttonSecondaryText' => 'Hacer un Tour',
])

@php
    // Define solid gradient colors for maximum contrast
    $gradientColors = [
        'from-violet-600 via-indigo-600 to-blue-600' => 'linear-gradient(to right, #7c3aed, #4f46e5, #2563eb)',
        'from-pink-600 via-red-600 to-orange-600' => 'linear-gradient(to right, #db2777, #dc2626, #ea580c)',
        'from-green-600 via-emerald-600 to-teal-600' => 'linear-gradient(to right, #16a34a, #059669, #0d9488)',
        'from-purple-600 via-violet-600 to-indigo-600' => 'linear-gradient(to right, #9333ea, #7c3aed, #4f46e5)',
    ];

    $gradientKey = $gradientFrom . ' ' . $gradientVia . ' ' . $gradientTo;
    $inlineGradient = $gradientColors[$gradientKey] ?? 'linear-gradient(to right, #7c3aed, #4f46e5, #2563eb)';
@endphp

<!-- Hero card with guaranteed gradient background and high contrast -->
<div class="overflow-hidden rounded-3xl p-8 text-white shadow-xl relative"
    style="background: {{ $inlineGradient }}; min-height: 200px;">

    <!-- Overlay for additional contrast if needed -->
    <div class="absolute inset-0 bg-black/10 rounded-3xl"></div>

    <!-- Content with relative positioning to appear above overlay -->
    <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div class="space-y-4">
            <!-- High contrast badge with solid dark background -->
            {{ $badge ?? '' }}

            <!-- Title with guaranteed white text and shadow -->
            {{ $slot }}

           {{ $footer ?? '' }}
        </div>

        <!-- Decorative element -->
        <div class="hidden lg:block">
            <div class="relative h-40 w-40 rotate-slow">
                <div
                    class="absolute inset-0 rounded-full bg-white/20 backdrop-blur-md border border-white/40 shadow-lg">
                </div>
                <div class="absolute inset-4 rounded-full bg-white/30 border border-white/50 shadow-md"></div>
                <div class="absolute inset-8 rounded-full bg-white/40 border border-white/60 shadow-sm"></div>
                <div class="absolute inset-12 rounded-full bg-white/50 border border-white/70"></div>
                <div class="absolute inset-16 rounded-full bg-white/70 border border-white/80"></div>
            </div>
        </div>
    </div>
</div>
