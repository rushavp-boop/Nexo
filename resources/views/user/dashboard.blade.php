@extends('layouts.app')

@section('content')
    @php
        $username = auth()->user()->name;
        $status = 'User Node';
    @endphp

    <div class="space-y-10 md:space-y-20 pb-10">

        {{-- Hero Greeting Section --}}
        <section class="relative overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-r from-amber-500/5 to-amber-400/5 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3rem] transform -skew-y-1">
            </div>
            <div
                class="relative bg-amber-100/70 backdrop-blur-xl rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3rem] border-2 md:border-3 lg:border-4 border-amber-700/40 shadow-lg sm:shadow-xl md:shadow-2xl p-4 sm:p-6 md:p-8 lg:p-10 xl:p-14 animate-slide-up hover:border-amber-800/60 transition-all duration-500">

                {{-- Animated Tattoo Decoration - Big Corner Bubble --}}
                <div class="absolute -top-20 -right-20 sm:-top-32 sm:-right-32 md:-top-40 md:-right-40 lg:-top-48 lg:-right-48 w-48 h-48 sm:w-64 sm:h-64 md:w-80 md:h-80 lg:w-[32rem] lg:h-[32rem] overflow-visible cursor-pointer z-10 group">
                    <svg viewBox="0 0 400 400" class="w-full h-full mandala-spin origin-center">
                        <defs>
                            <linearGradient id="tattooGradient">
                                <stop offset="0%" class="mandala-color-1" style="stop-color:#d97706;stop-opacity:1">
                                    <animate attributeName="stop-color"
                                             values="#d97706;#b45309;#92400e;#b45309;#d97706"
                                             dur="10s"
                                             repeatCount="indefinite" />
                                </stop>
                                <stop offset="100%" class="mandala-color-2" style="stop-color:#92400e;stop-opacity:1">
                                    <animate attributeName="stop-color"
                                             values="#92400e;#78350f;#92400e;#78350f;#92400e"
                                             dur="10s"
                                             repeatCount="indefinite" />
                                </stop>
                            </linearGradient>

                            <linearGradient id="tattooGradientDark">
                                <stop offset="0%" style="stop-color:#78350f;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#451a03;stop-opacity:1" />
                            </linearGradient>

                            <filter id="glow">
                                <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                                <feMerge>
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>

                        {{-- Mandala Pattern - Normal State --}}
                        <g transform="translate(200,200)" class="mandala-normal opacity-25 group-hover:opacity-0 transition-opacity duration-500" filter="url(#glow)">
                            {{-- Outermost layer with 12 petals --}}
                            @for ($i = 0; $i < 12; $i++)
                                <path d="M 0,-150 Q -15,-170 0,-185 Q 15,-170 0,-150"
                                      fill="url(#tattooGradient)"
                                      opacity="0.7"
                                      transform="rotate({{ $i * 30 }})"
                                      stroke="url(#tattooGradient)"
                                      stroke-width="0.5" />
                            @endfor

                            {{-- Large outer circles --}}
                            <circle cx="0" cy="0" r="165" fill="none" stroke="url(#tattooGradient)" stroke-width="3" opacity="0.5" />
                            <circle cx="0" cy="0" r="145" fill="none" stroke="url(#tattooGradient)" stroke-width="2" opacity="0.6" />
                            <circle cx="0" cy="0" r="130" fill="none" stroke="url(#tattooGradient)" stroke-width="2.5" opacity="0.6" />

                            {{-- Large decorative petals (16 petals) --}}
                            @for ($i = 0; $i < 16; $i++)
                                <ellipse cx="0" cy="-120" rx="12" ry="35" fill="url(#tattooGradient)" opacity="0.75" transform="rotate({{ $i * 22.5 }})" stroke="url(#tattooGradient)" stroke-width="1" />
                            @endfor

                            {{-- Middle layer circles --}}
                            <circle cx="0" cy="0" r="110" fill="none" stroke="url(#tattooGradient)" stroke-width="2" opacity="0.7" />
                            <circle cx="0" cy="0" r="95" fill="none" stroke="url(#tattooGradient)" stroke-width="1.5" opacity="0.6" />

                            {{-- Radiating lines from center --}}
                            @for ($i = 0; $i < 16; $i++)
                                <line x1="0" y1="0" x2="0" y2="-90" stroke="url(#tattooGradient)" stroke-width="2" opacity="0.6" transform="rotate({{ $i * 22.5 }})" />
                            @endfor

                            {{-- Medium petals (12 petals) --}}
                            @for ($i = 0; $i < 12; $i++)
                                <ellipse cx="0" cy="-75" rx="10" ry="28" fill="url(#tattooGradient)" opacity="0.8" transform="rotate({{ $i * 30 }})" />
                            @endfor

                            {{-- Inner decorative layer --}}
                            <circle cx="0" cy="0" r="65" fill="none" stroke="url(#tattooGradient)" stroke-width="2.5" opacity="0.8" />
                            <circle cx="0" cy="0" r="55" fill="none" stroke="url(#tattooGradient)" stroke-width="1.5" opacity="0.7" />

                            {{-- Inner petals (8 large petals) --}}
                            @for ($i = 0; $i < 8; $i++)
                                <path d="M 0,-45 Q -12,-55 0,-65 Q 12,-55 0,-45"
                                      fill="url(#tattooGradient)"
                                      opacity="0.85"
                                      transform="rotate({{ $i * 45 }})" />
                            @endfor

                            {{-- Central design --}}
                            <circle cx="0" cy="0" r="40" fill="none" stroke="url(#tattooGradient)" stroke-width="3" opacity="0.9" />
                            <circle cx="0" cy="0" r="30" fill="none" stroke="url(#tattooGradient)" stroke-width="2" opacity="0.85" />
                            <circle cx="0" cy="0" r="20" fill="url(#tattooGradient)" opacity="0.3" stroke="url(#tattooGradient)" stroke-width="2" />

                            {{-- Center star/flower --}}
                            @for ($i = 0; $i < 8; $i++)
                                <ellipse cx="0" cy="-12" rx="3" ry="12" fill="url(#tattooGradient)" opacity="0.95" transform="rotate({{ $i * 45 }})" />
                            @endfor

                            {{-- Central circle --}}
                            <circle cx="0" cy="0" r="8" fill="url(#tattooGradient)" opacity="1" />

                            {{-- Decorative outer dots --}}
                            @for ($i = 0; $i < 24; $i++)
                                <circle cx="0" cy="-140" r="3" fill="url(#tattooGradient)" opacity="0.8" transform="rotate({{ $i * 15 }})" />
                            @endfor

                            {{-- Additional decorative elements between petals --}}
                            @for ($i = 0; $i < 12; $i++)
                                <circle cx="0" cy="-105" r="4" fill="url(#tattooGradient)" opacity="0.7" transform="rotate({{ $i * 30 + 15 }})" />
                            @endfor
                        </g>

                        {{-- Mandala Pattern - Hover State (Darker) --}}
                        <g transform="translate(200,200)" class="mandala-hover opacity-0 group-hover:opacity-50 transition-opacity duration-500" filter="url(#glow)">
                            {{-- Outermost layer with 12 petals --}}
                            @for ($i = 0; $i < 12; $i++)
                                <path d="M 0,-150 Q -15,-170 0,-185 Q 15,-170 0,-150"
                                      fill="url(#tattooGradientDark)"
                                      opacity="0.7"
                                      transform="rotate({{ $i * 30 }})"
                                      stroke="url(#tattooGradientDark)"
                                      stroke-width="0.5" />
                            @endfor

                            {{-- Large outer circles --}}
                            <circle cx="0" cy="0" r="165" fill="none" stroke="url(#tattooGradientDark)" stroke-width="3" opacity="0.5" />
                            <circle cx="0" cy="0" r="145" fill="none" stroke="url(#tattooGradientDark)" stroke-width="2" opacity="0.6" />
                            <circle cx="0" cy="0" r="130" fill="none" stroke="url(#tattooGradientDark)" stroke-width="2.5" opacity="0.6" />

                            {{-- Large decorative petals (16 petals) --}}
                            @for ($i = 0; $i < 16; $i++)
                                <ellipse cx="0" cy="-120" rx="12" ry="35" fill="url(#tattooGradientDark)" opacity="0.75" transform="rotate({{ $i * 22.5 }})" stroke="url(#tattooGradientDark)" stroke-width="1" />
                            @endfor

                            {{-- Middle layer circles --}}
                            <circle cx="0" cy="0" r="110" fill="none" stroke="url(#tattooGradientDark)" stroke-width="2" opacity="0.7" />
                            <circle cx="0" cy="0" r="95" fill="none" stroke="url(#tattooGradientDark)" stroke-width="1.5" opacity="0.6" />

                            {{-- Radiating lines from center --}}
                            @for ($i = 0; $i < 16; $i++)
                                <line x1="0" y1="0" x2="0" y2="-90" stroke="url(#tattooGradientDark)" stroke-width="2" opacity="0.6" transform="rotate({{ $i * 22.5 }})" />
                            @endfor

                            {{-- Medium petals (12 petals) --}}
                            @for ($i = 0; $i < 12; $i++)
                                <ellipse cx="0" cy="-75" rx="10" ry="28" fill="url(#tattooGradientDark)" opacity="0.8" transform="rotate({{ $i * 30 }})" />
                            @endfor

                            {{-- Inner decorative layer --}}
                            <circle cx="0" cy="0" r="65" fill="none" stroke="url(#tattooGradientDark)" stroke-width="2.5" opacity="0.8" />
                            <circle cx="0" cy="0" r="55" fill="none" stroke="url(#tattooGradientDark)" stroke-width="1.5" opacity="0.7" />

                            {{-- Inner petals (8 large petals) --}}
                            @for ($i = 0; $i < 8; $i++)
                                <path d="M 0,-45 Q -12,-55 0,-65 Q 12,-55 0,-45"
                                      fill="url(#tattooGradientDark)"
                                      opacity="0.85"
                                      transform="rotate({{ $i * 45 }})" />
                            @endfor

                            {{-- Central design --}}
                            <circle cx="0" cy="0" r="40" fill="none" stroke="url(#tattooGradientDark)" stroke-width="3" opacity="0.9" />
                            <circle cx="0" cy="0" r="30" fill="none" stroke="url(#tattooGradientDark)" stroke-width="2" opacity="0.85" />
                            <circle cx="0" cy="0" r="20" fill="url(#tattooGradientDark)" opacity="0.3" stroke="url(#tattooGradientDark)" stroke-width="2" />

                            {{-- Center star/flower --}}
                            @for ($i = 0; $i < 8; $i++)
                                <ellipse cx="0" cy="-12" rx="3" ry="12" fill="url(#tattooGradientDark)" opacity="0.95" transform="rotate({{ $i * 45 }})" />
                            @endfor

                            {{-- Central circle --}}
                            <circle cx="0" cy="0" r="8" fill="url(#tattooGradientDark)" opacity="1" />

                            {{-- Decorative outer dots --}}
                            @for ($i = 0; $i < 24; $i++)
                                <circle cx="0" cy="-140" r="3" fill="url(#tattooGradientDark)" opacity="0.8" transform="rotate({{ $i * 15 }})" />
                            @endfor

                            {{-- Additional decorative elements between petals --}}
                            @for ($i = 0; $i < 12; $i++)
                                <circle cx="0" cy="-105" r="4" fill="url(#tattooGradientDark)" opacity="0.7" transform="rotate({{ $i * 30 + 15 }})" />
                            @endfor
                        </g>
                    </svg>
                </div>

                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 md:gap-10">
                    <div class="space-y-4 md:space-y-6 flex-1">
                        <!-- Status Badge -->
                        <div
                            class="inline-flex items-center gap-2 md:gap-3 px-4 md:px-5 py-2 md:py-2.5 bg-gradient-to-r from-amber-700 to-amber-800 text-white rounded-full text-[10px] md:text-xs font-black uppercase tracking-[0.15em] md:tracking-[0.2em] shadow-lg md:shadow-xl shadow-amber-700/30 hover:shadow-2xl hover:shadow-amber-700/40 transition-all duration-500 cursor-default group">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                            </span>
                            <span class="group-hover:tracking-[0.3em] transition-all duration-300">
                                {{ now()->format('H') < 12 ? 'Good Morning' : (now()->format('H') < 17 ? 'Good Afternoon' : 'Good Evening') }},
                                {{ $username }}
                            </span>
                        </div>

                        <!-- Main Greeting -->
                        <div class="space-y-3 md:space-y-5">
                            <div class="flex flex-col md:flex-row md:items-end gap-3 md:gap-5">
                                <h1
                                    class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black tracking-tight bg-gradient-to-br from-amber-900 via-amber-800 to-amber-900 bg-clip-text text-transparent leading-none hover:scale-105 transition-transform duration-700 cursor-default font-serif italic origin-left">
                                    Namaste<span class="text-amber-700">.</span>
                                </h1>
                                {{-- <div class="inline-flex items-center gap-3 px-6 py-3 bg-stone-900 text-white rounded-2xl shadow-2xl mb-2 hover:shadow-3xl hover:-translate-y-1 transition-all duration-500 group cursor-default">
                                <span class="text-[9px] font-black uppercase tracking-[0.3em] opacity-70 group-hover:opacity-100 transition-opacity">Node:</span>
                                <span class="text-xs font-bold text-emerald-400">{{ $status }}</span>
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            </div> --}}
                            </div>
                            <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-amber-700 font-medium max-w-3xl leading-relaxed">
                                Welcome to your integrated <span class="font-bold text-amber-900">Himalayan
                                    dashboard</span>. Your Nexo node is fully synchronized.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- Premium Stats Grid --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 md:gap-6 lg:gap-7 animate-fade-in">

            <!-- Nexo Paisa Card -->
            <a href="{{ route('user.loadNexoPaisa') }}"
                class="group relative bg-gradient-to-br from-amber-700 to-amber-800 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[2rem] p-5 sm:p-6 md:p-7 lg:p-9 shadow-lg sm:shadow-xl md:shadow-2xl shadow-amber-700/20 hover:shadow-2xl sm:hover:shadow-3xl hover:shadow-amber-700/40 transition-all duration-700 hover:-translate-y-2 sm:hover:-translate-y-3 cursor-pointer overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                </div>
                <div
                    class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 bg-white/10 rounded-full -mr-12 -mt-12 sm:-mr-16 sm:-mt-16 md:-mr-20 md:-mt-20 group-hover:scale-150 transition-transform duration-1000">
                </div>
                <div class="relative z-10 space-y-3 sm:space-y-4 md:space-y-5">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] sm:text-[10px] font-black text-amber-100 uppercase tracking-[0.15em] sm:tracking-[0.2em]">Nexo Paisa</span>
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 backdrop-blur-sm rounded-lg sm:rounded-xl flex items-center justify-center group-hover:rotate-12 group-hover:scale-110 transition-all duration-500">
                            <i class="fa-solid fa-wallet text-white text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="space-y-2 sm:space-y-3">
                        <div
                            class="text-2xl sm:text-3xl md:text-4xl font-black text-white group-hover:scale-110 transition-transform duration-500 origin-left font-serif">
                            Rs. {{ number_format(auth()->user()->nexo_paisa, 2) }}
                        </div>
                        <div
                            class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-white/90 hover:text-white transition-colors">
                            <span>Load More</span>
                            <i
                                class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform duration-300"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Profile Card -->
            <a href="{{ route('user.profile') }}"
                class="group relative bg-amber-50 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[2rem] p-5 sm:p-6 md:p-7 lg:p-9 shadow-lg sm:shadow-xl border border-amber-200 hover:shadow-2xl sm:hover:shadow-3xl hover:border-amber-300 transition-all duration-700 hover:-translate-y-2 sm:hover:-translate-y-3 overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-amber-200 to-transparent rounded-full -mr-20 -mt-20 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                </div>
                <div class="relative z-10 space-y-5">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] sm:text-[10px] font-black text-amber-700 uppercase tracking-[0.15em] sm:tracking-[0.2em]">Profile</span>
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-lg sm:rounded-xl flex items-center justify-center group-hover:bg-amber-200 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500">
                            <i class="fa-solid fa-user text-amber-700 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <div
                        class="text-2xl sm:text-3xl md:text-4xl font-black text-amber-900 group-hover:scale-110 transition-transform duration-500 origin-left font-serif">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="text-xs sm:text-sm text-amber-700 font-bold">View & Edit Profile</div>
                </div>
            </a>

            <!-- No. of Transactions Card -->
            <div
                class="group relative bg-amber-50 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[2rem] p-5 sm:p-6 md:p-7 lg:p-9 shadow-lg sm:shadow-xl border border-amber-200 hover:shadow-2xl sm:hover:shadow-3xl hover:border-amber-300 transition-all duration-700 hover:-translate-y-2 sm:hover:-translate-y-3 overflow-hidden">
                @php
                    $transactionsCount = \App\Models\NexoPaisaTransaction::where('user_id', auth()->id())->count();
                @endphp
                <div
                    class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 bg-gradient-to-br from-amber-200 to-transparent rounded-full -mr-12 -mt-12 sm:-mr-16 sm:-mt-16 md:-mr-20 md:-mt-20 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                </div>
                <div class="relative z-10 space-y-3 sm:space-y-4 md:space-y-5">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] sm:text-[10px] font-black text-amber-700 uppercase tracking-[0.15em] sm:tracking-[0.2em]">Transactions</span>
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-lg sm:rounded-xl flex items-center justify-center group-hover:bg-amber-200 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500">
                            <i class="fa-solid fa-list-check text-amber-700 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <div
                        class="text-2xl sm:text-3xl md:text-4xl font-black text-amber-900 group-hover:scale-110 transition-transform duration-500 origin-left font-serif">
                        {{ $transactionsCount }}
                    </div>
                    <div class="text-xs sm:text-sm text-amber-700 font-bold">Total Transactions</div>
                </div>
            </div>

            <!-- Climate Card -->
            <div x-data="weatherCard()" x-init="fetchWeather()"
                class="group relative rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[2rem] p-5 sm:p-6 md:p-7 lg:p-9 shadow-lg sm:shadow-xl border border-amber-200 transition-all duration-700 hover:-translate-y-2 sm:hover:-translate-y-3 overflow-hidden"
                :class="cardBg">

                <!-- Glow -->
                <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 rounded-full -mr-12 -mt-12 sm:-mr-16 sm:-mt-16 md:-mr-20 md:-mt-20 opacity-0 group-hover:opacity-100 transition-opacity duration-700"
                    :class="glowBg"></div>

                <div class="relative z-10 space-y-3 sm:space-y-4 md:space-y-5">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.15em] sm:tracking-[0.2em] text-amber-700">
                            Climate · Kathmandu
                        </span>

                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl flex items-center justify-center transition-all duration-500"
                            :class="iconBg">
                            <i :class="icon" class="text-lg sm:text-xl"></i>
                        </div>
                    </div>

                    <div class="text-2xl sm:text-3xl md:text-4xl font-black font-serif transition-transform duration-500 origin-left group-hover:scale-110"
                        x-text="temp"></div>

                    <div class="text-xs sm:text-sm font-bold" x-text="desc"></div>
                </div>
            </div>

        </section>

        {{-- Premium Modules Grid --}}
        <section class="space-y-6 sm:space-y-8 md:space-y-10 animate-slide-up">
            <div class="flex items-center justify-between">
                <div class="space-y-2 sm:space-y-3">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-black text-amber-900 font-serif italic">Nexo Modules</h3>
                    <p class="text-sm sm:text-base text-amber-700 font-medium">Access your integrated services</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-5 md:gap-6 lg:gap-7">
                @php
                    $modules = [
                        [
                            'title' => 'EduSync',
                            'icon' => 'fa-book-open-reader',
                            'route' => 'user.education',
                            'desc' => 'Learning Hub',
                        ],
                        [
                            'title' => 'Voyage',
                            'icon' => 'fa-compass',
                            'route' => 'user.travel',
                            'desc' => 'Travel Planning',
                        ],
                        [
                            'title' => 'Health',
                            'icon' => 'fa-heart-pulse',
                            'route' => 'user.health',
                            'desc' => 'Wellness Tracking',
                        ],
                        [
                            'title' => 'Agro',
                            'icon' => 'fa-seedling',
                            'route' => 'user.agro',
                            'desc' => 'Farm Management',
                        ],
                        [
                            'title' => 'Calendar',
                            'icon' => 'fa-calendar-days',
                            'route' => 'user.calendar',
                            'desc' => 'Schedule & Events',
                        ],
                    ];
                @endphp

                @foreach ($modules as $index => $mod)
                    <a href="{{ route($mod['route'] ?? '#') }}"
                        class="group relative rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[2rem] p-6 sm:p-8 md:p-9 lg:p-10 shadow-lg sm:shadow-xl md:shadow-2xl border border-amber-200 hover:border-transparent transition-all duration-700 hover:-translate-y-2 sm:hover:-translate-y-3 overflow-hidden flex flex-col justify-between aspect-square"
                        style="animation-delay: {{ $index * 100 }}ms; background-image: url('/old-paper-texture.jpg'); background-size: cover; background-position: center;"
                        onmouseover="this.style.backgroundImage=''"
                        onmouseout="this.style.backgroundImage='url(/old-paper-texture.jpg)'">

                        <div
                            class="absolute inset-0 bg-gradient-to-br from-amber-800 to-amber-900 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                        </div>

                        <div
                            class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                        </div>

                        <div class="relative z-10">
                            <div
                                class="h-12 w-12 sm:h-14 sm:w-14 md:h-16 md:w-16 bg-white/30 rounded-lg sm:rounded-xl md:rounded-2xl flex items-center justify-center group-hover:bg-white group-hover:text-amber-800 shadow-md sm:shadow-lg group-hover:scale-110 group-hover:rotate-12 transition-all duration-700">
                                <i class="fa-solid {{ $mod['icon'] }} text-2xl sm:text-3xl text-amber-900 group-hover:text-amber-800"></i>
                            </div>
                        </div>

                        <div class="relative z-10 space-y-2 sm:space-y-3">
                            <h4
                                class="text-2xl sm:text-3xl font-black font-serif italic text-amber-900 group-hover:text-white transition-colors duration-500">
                                {{ $mod['title'] }}
                            </h4>
                            <p
                                class="text-xs sm:text-sm text-amber-800 group-hover:text-white/90 font-medium transition-colors duration-500">
                                {{ $mod['desc'] }}
                            </p>
                            <div
                                class="w-8 sm:w-10 h-1 bg-amber-400 group-hover:bg-white group-hover:w-12 sm:group-hover:w-16 rounded-full transition-all duration-700">
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

    </div>

    <!-- Simple Floating Chatbot -->
    <div class="fixed bottom-6 sm:bottom-8 right-6 sm:right-8 z-50">
        <a href="{{ route('user.chat') }}"
            class="relative h-12 w-12 sm:h-14 sm:w-14
               bg-gradient-to-br from-amber-700 to-amber-800
               text-white rounded-xl sm:rounded-2xl
               shadow-lg sm:shadow-xl
               flex items-center justify-center
               hover:scale-105 transition-transform duration-200">

            <!-- Robot Icon -->
            <i class="fa-solid fa-robot text-lg sm:text-xl"></i>

            <!-- Online Dot -->
            <span
                class="absolute -top-0.5 sm:-top-1 -right-0.5 sm:-right-1
                   h-2.5 w-2.5 sm:h-3 sm:w-3
                   bg-emerald-500
                   border-2 border-white
                   rounded-full">
            </span>
        </a>
    </div>
@endsection

<script>
    function weatherCard() {
        return {
            temp: '--°C',
            desc: 'Loading...',
            icon: 'fa-solid fa-sun text-amber-600',
            cardBg: 'bg-amber-50',
            glowBg: 'bg-amber-100',
            iconBg: 'bg-amber-100',

            async fetchWeather() {
                const lat = 27.7172;
                const lon = 85.3240;

                try {
                    const res = await fetch(
                        `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`
                    );
                    const data = await res.json();

                    const w = data.current_weather;
                    const isDay = w.is_day === 1;

                    this.temp = `${Math.round(w.temperature)}°C`;

                    // Weather + Day/Night mapping
                    const map = {
                        0: {
                            d: 'Clear Sky',
                            day: 'fa-sun',
                            night: 'fa-moon'
                        },
                        1: {
                            d: 'Mainly Clear',
                            day: 'fa-sun',
                            night: 'fa-moon'
                        },
                        2: {
                            d: 'Partly Cloudy',
                            day: 'fa-cloud-sun',
                            night: 'fa-cloud-moon'
                        },
                        3: {
                            d: 'Overcast',
                            day: 'fa-cloud',
                            night: 'fa-cloud'
                        },
                        45: {
                            d: 'Foggy',
                            day: 'fa-smog',
                            night: 'fa-smog'
                        },
                        61: {
                            d: 'Rain',
                            day: 'fa-cloud-rain',
                            night: 'fa-cloud-rain'
                        },
                        63: {
                            d: 'Heavy Rain',
                            day: 'fa-cloud-showers-heavy',
                            night: 'fa-cloud-showers-heavy'
                        },
                        71: {
                            d: 'Snow',
                            day: 'fa-snowflake',
                            night: 'fa-snowflake'
                        },
                        95: {
                            d: 'Thunderstorm',
                            day: 'fa-bolt',
                            night: 'fa-bolt'
                        },
                    };

                    const weather = map[w.weathercode] || map[0];
                    this.desc = weather.d;

                    if (isDay) {
                        this.icon = `fa-solid ${weather.day} text-amber-600`;
                        this.cardBg = 'bg-amber-50';
                        this.glowBg = 'bg-amber-100';
                        this.iconBg = 'bg-amber-100';
                    } else {
                        this.icon = `fa-solid ${weather.night} text-amber-400`;
                        this.cardBg = 'bg-gradient-to-br from-amber-900 to-amber-800 text-white';
                        this.glowBg = 'bg-amber-600/30';
                        this.iconBg = 'bg-white/10';
                    }

                } catch (e) {
                    console.error(e);
                    this.desc = 'Unable to load';
                }
            }
        }
    }
</script>                    this.desc = 'Unavailable';
                }
            }
        }
    }
</script>
