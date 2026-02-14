@extends('front.layouts.app')

@section('title', 'NEXO.GLOBAL · Shaping Nepal’s Digital Future')

@section('content')

<!-- ================= HERO ================= -->
<section class="relative min-h-[70vh] md:min-h-[85vh] flex items-center justify-center px-4 md:px-6">

    <!-- Background Symbol -->
    <div class="absolute inset-0 opacity-[0.02] md:opacity-[0.04] flex items-center justify-center pointer-events-none">
        <i class="fa-solid fa-atom text-[30rem] md:text-[60rem] animate-slow-spin"></i>
    </div>

    <div class="relative z-10 max-w-5xl text-center space-y-6 md:space-y-10">

        <span class="inline-block text-[9px] md:text-[11px] uppercase tracking-[0.3em] md:tracking-[0.5em] text-amber-600 font-semibold">
            Building Nepal’s Digital Tomorrow
        </span>

        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-7xl font-serif font-bold tracking-tight leading-tight">
            Technology that <br class="hidden md:block">
            <span class="italic">understands</span> where it lives.
        </h1>

        <p class="text-base sm:text-lg md:text-xl text-amber-700 max-w-3xl mx-auto leading-relaxed">
            NEXA.Global is a people-first digital ecosystem designed for Nepal —
            respecting geography, culture, and community while unlocking
            modern innovation.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-3 md:gap-4 pt-4 md:pt-6">
            <a href="#systems"
               class="px-6 sm:px-8 md:px-10 py-3 md:py-4 rounded-full bg-amber-900 text-white uppercase tracking-wider md:tracking-widest text-[10px] md:text-xs font-semibold hover:-translate-y-1 transition">
                Explore the Platform
            </a>
            <a href="#vision"
               class="px-6 sm:px-8 md:px-10 py-3 md:py-4 rounded-full border border-amber-600 text-[10px] md:text-xs uppercase tracking-wider md:tracking-widest font-semibold hover:bg-amber-50 transition">
                Our Vision
            </a>
        </div>

    </div>
</section>

<!-- ================= VISION ================= -->
<section id="vision" class="max-w-7xl mx-auto px-4 md:px-6 py-16 md:py-32 grid lg:grid-cols-2 gap-10 md:gap-20 items-center">

    <div class="space-y-6 md:space-y-8">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold">
            Innovation, rooted in Nepal.
        </h2>

        <p class="text-lg text-amber-700 leading-relaxed">
            From the plains of the Terai to the Himalayan highlands,
            digital systems must adapt — not demand adaptation.
        </p>

        <p class="text-lg text-amber-700 leading-relaxed">
            We design technology that works with people,
            languages, infrastructure, and realities of Nepal.
        </p>
    </div>

    <div class="relative rounded-[3rem] overflow-hidden shadow-2xl">
        <img src="https://images.unsplash.com/photo-1544735716-392fe2489ffa?q=80&w=900"
             class="w-full h-full object-cover">
    </div>

</section>

<!-- ================= SYSTEMS ================= -->
<section id="systems" class="bg-amber-50 py-32 px-6">

    <div class="max-w-7xl mx-auto text-center mb-20">
        <h2 class="text-4xl md:text-5xl font-serif font-bold">
            An Integrated National Ecosystem
        </h2>
        <p class="mt-4 text-lg text-amber-700">
            Built module by module — connected by purpose.
        </p>
    </div>

    <div class="max-w-7xl mx-auto grid sm:grid-cols-2 lg:grid-cols-4 gap-10">

        @php
            $systems = [
                ['Education','Smarter learning for every child','fa-graduation-cap'],
                ['Health','Accessible, predictive care','fa-heart-pulse'],
                ['Agriculture','Empowering farmers with data','fa-leaf'],
                ['Mobility','Reliable logistics across terrain','fa-mountain-sun'],
            ];
        @endphp

        @foreach($systems as $sys)
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm hover:shadow-xl hover:-translate-y-2 transition text-left">
                <i class="fa-solid {{ $sys[2] }} text-3xl text-amber-600"></i>
                <h3 class="mt-6 text-xl font-serif font-bold">{{ $sys[0] }}</h3>
                <p class="mt-2 text-amber-700">
                    {{ $sys[1] }}
                </p>
            </div>
        @endforeach

    </div>
</section>

@endsection
