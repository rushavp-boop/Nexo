@extends('front.layouts.app')

@section('title', 'About · NEXO.GLOBAL')
@section('content')

<section class="max-w-7xl mx-auto px-4 md:px-6 py-16 md:py-28 space-y-12 md:space-y-24">

    <!-- ================= INTRO ================= -->
    <div class="max-w-3xl space-y-4 md:space-y-6">
        <span class="text-[10px] md:text-[11px] uppercase tracking-[0.3em] md:tracking-[0.4em] text-amber-600 font-semibold block">
            Our Origin
        </span>

        <h1 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold italic tracking-tight leading-tight">
            Harmonizing Himalayan <br>
            Modernity.
        </h1>

        <p class="text-base sm:text-lg md:text-xl text-amber-700 leading-relaxed">
            NEXA was founded on the belief that geography should never limit opportunity.
            We build digital infrastructure that respects Nepal’s terrain, culture,
            and people — while delivering global-grade intelligence.
        </p>
    </div>

    <!-- ================= MISSION / VISION / VALUES ================= -->
    <div class="grid md:grid-cols-3 gap-6 md:gap-10">

        <div class="bg-white p-6 md:p-10 rounded-2xl md:rounded-[2.5rem] border border-black/5 shadow-sm hover:shadow-xl transition">
            <i class="fa-solid fa-bullseye text-xl md:text-2xl text-amber-600 mb-4 md:mb-6"></i>
            <h3 class="text-lg md:text-xl font-serif font-bold italic mb-2 md:mb-3">Mission</h3>
            <p class="text-sm md:text-base text-amber-700 leading-relaxed">
                To provide a unified digital ecosystem for education,
                healthcare, and logistics — especially in hard-to-reach regions.
            </p>
        </div>

        <div class="bg-white p-6 md:p-10 rounded-2xl md:rounded-[2.5rem] border border-black/5 shadow-sm hover:shadow-xl transition">
            <i class="fa-solid fa-eye text-xl md:text-2xl text-amber-600 mb-4 md:mb-6"></i>
            <h3 class="text-lg md:text-xl font-serif font-bold italic mb-2 md:mb-3">Vision</h3>
            <p class="text-sm md:text-base text-amber-700 leading-relaxed">
                A Nepal where every student, farmer, traveler,
                and patient is connected to a trusted, intelligent system of care.
            </p>
        </div>

        <div class="bg-white p-6 md:p-10 rounded-2xl md:rounded-[2.5rem] border border-black/5 shadow-sm hover:shadow-xl transition">
            <i class="fa-solid fa-heart text-xl md:text-2xl text-amber-600 mb-4 md:mb-6"></i>
            <h3 class="text-lg md:text-xl font-serif font-bold italic mb-2 md:mb-3">Values</h3>
            <p class="text-sm md:text-base text-amber-700 leading-relaxed">
                Local-first thinking, privacy by design,
                and a relentless pursuit of excellence — even at high altitude.
            </p>
        </div>

    </div>

    <!-- ================= FOUNDERS PROMISE ================= -->
    <div class="bg-[#f5f2eb] rounded-2xl md:rounded-[3rem] p-8 sm:p-10 md:p-12 lg:p-16 border border-dashed border-[#c4b5fd]">

        <h2 class="text-xl sm:text-2xl md:text-3xl font-serif font-bold italic mb-4 md:mb-6">
            The Founders’ Promise
        </h2>

        <p class="text-base md:text-lg text-[#2a1b18] leading-relaxed italic opacity-80 max-w-4xl">
            “NEXA is not just software — it is a commitment to the citizens of Nepal.
            We measure success by the reliability of the grid,
            the clarity of information,
            and the dignity with which technology serves human life.”
        </p>

        <div class="mt-8 flex items-center gap-4">
            <div class="h-[2px] w-12 bg-[#2a1b18]"></div>
            <span class="text-xs uppercase tracking-widest font-semibold italic">
                The NEXA Labs Collective
            </span>
        </div>

    </div>

</section>

@endsection
