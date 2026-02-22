@extends('layouts.app')

@section('content')
@php
    $username = auth()->user()->name;
    $status = 'Admin Node';
@endphp

<div class="space-y-12 md:space-y-24 pb-16 md:pb-32">

    {{-- Greeting --}}
    <section class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 md:gap-12">
        <div class="space-y-3 md:space-y-4">
            <div class="flex items-center gap-2 md:gap-3 text-orange-700 font-bold uppercase tracking-wider md:tracking-widest text-[10px] md:text-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-orange-600 animate-pulse"></span>
                Welcome Admin, {{ $username }}
            </div>
            <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-8xl font-serif italic font-bold tracking-tight leading-none">
                NEXO Admin Hub
            </h2>
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-stone-600 font-medium max-w-2xl leading-relaxed">
                Manage the platform, monitor nodes, and control system settings.
            </p>
        </div>
    </section>

    {{-- Admin Stats --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="nexo-card p-10 text-center">
            <span class="text-[10px] font-black text-stone-400 uppercase tracking-widest">Total Users</span>
            <span class="text-3xl font-bold font-serif">{{ \App\Models\User::count() }}</span>
        </div>
        <div class="nexo-card p-10 text-center">
            <span class="text-[10px] font-black text-stone-400 uppercase tracking-widest">Active Nodes</span>
            <span class="text-3xl font-bold font-serif">58</span>
        </div>
        <div class="nexo-card p-10 text-center">
            <span class="text-[10px] font-black text-stone-400 uppercase tracking-widest">Pending Requests</span>
            <span class="text-3xl font-bold font-serif">12</span>
        </div>
        <div class="nexo-card p-10 text-center">
            <span class="text-[10px] font-black text-stone-400 uppercase tracking-widest">System Load</span>
            <span class="text-3xl font-bold font-serif text-orange-600">72%</span>
        </div>
    </section>

</div>
@endsection
