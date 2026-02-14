@php
    $user = auth()->user();
@endphp

<header class="fixed top-0 right-0 left-0 md:left-72 z-40 bg-amber-50/90 backdrop-blur-xl border-b border-amber-200/50 px-4 md:px-8 py-3 md:py-4 shadow-sm">
    <div class="flex items-center justify-between">

        {{-- Left Side: Date & Branding --}}
        <div class="flex items-center gap-2 md:gap-3 text-amber-800 font-serif italic font-bold tracking-tight ml-14 md:ml-0">
            <i class="fa-solid fa-layer-group text-amber-700 text-sm md:text-lg"></i>
            <span class="text-xs md:text-sm hidden sm:inline">{{ now()->format('l, F j, Y') }}</span>
            <span class="text-xs md:text-sm sm:hidden">{{ now()->format('M j, Y') }}</span>
        </div>

        {{-- Right Side: Wallet & User --}}
        <div class="flex items-center gap-2 md:gap-4">

            {{-- Nexo Paisa Wallet --}}
            <a href="{{ route('user.loadNexoPaisa') }}"
               class="group flex items-center gap-2 md:gap-3 px-2 md:px-4 py-2 md:py-2.5 bg-gradient-to-r from-amber-50 to-amber-100/50 hover:from-amber-100 hover:to-amber-200/50 rounded-lg md:rounded-xl border border-amber-200/50 hover:border-amber-300 transition-all duration-300 hover:shadow-lg">

                {{-- Wallet Icon --}}
                <div class="relative">
                    <div class="w-8 h-8 md:w-9 md:h-9 rounded-lg bg-gradient-to-br from-amber-700 to-amber-800 flex items-center justify-center shadow-md transition-all">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    {{-- Notification Dot --}}
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-white animate-pulse"></span>
                </div>

                {{-- Balance Info --}}
                <div class="flex flex-col">
                    <span class="text-[9px] md:text-[10px] font-black text-amber-800 uppercase tracking-wider hidden sm:inline">Nexo Paisa</span>
                    <span class="text-sm md:text-base font-bold text-stone-900">{{ number_format($user->nexo_paisa, 2) }}</span>
                </div>

                {{-- Arrow Icon --}}
                <i class="fa-solid fa-chevron-right text-amber-700 text-xs opacity-0 group-hover:opacity-100 -ml-2 group-hover:ml-0 transition-all duration-300 hidden md:inline"></i>
            </a>

            {{-- Divider --}}
            <div class="h-8 md:h-10 w-px bg-amber-200"></div>

            {{-- User Avatar Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center gap-2 md:gap-3 px-2 md:px-3 py-2 rounded-lg md:rounded-xl hover:bg-stone-50 transition-all duration-300 group">

                    {{-- Avatar with Status --}}
                    <div class="relative">
                        <div class="h-8 w-8 md:h-10 md:w-10 rounded-full bg-gradient-to-br from-amber-700 to-amber-800 flex items-center justify-center text-white font-bold text-xs md:text-sm shadow-md ring-2 ring-amber-200 transition-all">
                            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <span class="absolute bottom-0 right-0 w-2 h-2 md:w-3 md:h-3 bg-green-500 rounded-full border-2 border-white"></span>
                    </div>

                    {{-- User Info --}}
                    <div class="hidden md:flex flex-col items-start font-serif italic">
                        <span class="text-sm font-bold text-stone-900">{{ $user->name }}</span>
                        <span class="text-xs text-amber-700">{{ ucfirst($user->role) }}</span>
                    </div>

                    {{-- Chevron --}}
                    <i class="fa-solid fa-chevron-down text-amber-700 text-xs transition-transform duration-300 hidden md:inline"
                       :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                     class="absolute right-0 mt-3 w-56 md:w-64 bg-white rounded-2xl shadow-2xl border border-amber-200/50 overflow-hidden z-50"
                     style="display: none;">

                    {{-- User Header --}}
                    <div class="px-4 py-4 bg-gradient-to-br from-amber-50 to-white border-b border-amber-200 flex items-center gap-3">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-amber-700 to-amber-800 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-stone-900">{{ $user->name }}</span>
                            <span class="text-xs text-amber-700">{{ $user->email }}</span>
                        </div>
                    </div>

                    {{-- Menu Items --}}
                    <div class="py-2">
                        <a href="{{ route('user.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-amber-900 hover:bg-amber-50 hover:text-amber-700 transition-all group">
                            <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-grip-vertical text-amber-700 group-hover:text-amber-700"></i>
                            </div>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('user.profile') }}"
                           class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-amber-900 hover:bg-amber-50 hover:text-amber-700 transition-all group">
                            <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-user text-amber-700 group-hover:text-amber-700"></i>
                            </div>
                            <span>Profile Settings</span>
                        </a>
                        <a href="{{ route('user.loadNexoPaisa') }}"
                           class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-amber-900 hover:bg-amber-50 hover:text-amber-700 transition-all group md:hidden">
                            <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-wallet text-amber-700 group-hover:text-amber-700"></i>
                            </div>
                            <span>Load Nexo Paisa</span>
                        </a>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-amber-200"></div>

                    {{-- Logout --}}
                    <div class="py-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-amber-900 hover:bg-amber-100 hover:text-amber-800 transition-all group">
                                <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-sign-out-alt text-amber-700 group-hover:text-amber-800"></i>
                                </div>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>
