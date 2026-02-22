<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'NEXO.GLOBAL · Shaping Nepal’s Digital Future')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <style>
        html {
            scroll-behavior: smooth;
        }

        @keyframes slow-spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-slow-spin {
            animation: slow-spin 80s linear infinite;
            color: rgb(180, 83, 9);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-amber-50 text-amber-900 antialiased">

    <!-- ================= NAVBAR ================= -->
    <header class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-black/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-4 md:py-5 flex items-center justify-between">

            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 md:gap-3 group">
                <img src="{{ asset('Nexo_logo.png') }}" alt="NEXO.GLOBAL Logo"
                    class="h-20 w-14 md:h-30 md:w-20 object-contain transition-transform duration-300 group-hover:scale-105">
                <span class="font-serif italic font-bold tracking-tight text-base md:text-lg">
                    NEXO.GLOBAL
                </span>
            </a>

            <!-- Navigation -->
            <nav
                class="hidden md:flex items-center gap-8 lg:gap-14 text-[10px] md:text-xs uppercase tracking-[0.2em] md:tracking-[0.3em] font-medium text-amber-700">
                <a href="{{ url('/#vision') }}" class="relative group">
                    <span class="group-hover:text-amber-900 transition">Vision</span>
                    <span
                        class="absolute left-0 -bottom-1 w-0 h-[1px] bg-amber-900 transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ url('/#systems') }}" class="relative group">
                    <span class="group-hover:text-amber-900 transition">Ecosystem</span>
                    <span
                        class="absolute left-0 -bottom-1 w-0 h-[1px] bg-amber-900 transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ url('/about') }}" class="relative group">
                    <span class="group-hover:text-amber-900 transition">About</span>
                    <span
                        class="absolute left-0 -bottom-1 w-0 h-[1px] bg-amber-900 transition-all group-hover:w-full"></span>
                </a>

                <a href="{{ url('/contact') }}" class="relative group">
                    <span class="group-hover:text-amber-900 transition">Contact</span>
                    <span
                        class="absolute left-0 -bottom-1 w-0 h-[1px] bg-amber-900 transition-all group-hover:w-full"></span>
                </a>
            </nav>

            <!-- CTA -->
            <a href="{{ route('login') }}"
                class="px-6 md:px-10 py-3 md:py-4 rounded-full bg-amber-900 text-white text-[10px] md:text-xs uppercase tracking-[0.2em] md:tracking-[0.25em] font-semibold
                  hover:scale-105 hover:bg-amber-800 transition-all duration-300 shadow-lg">
                Get Started
            </a>

        </div>
    </header>

    <!-- Spacer for fixed navbar -->
    <div class="h-20 md:h-24"></div>

    <!-- ================= PAGE CONTENT ================= -->
    <main>
        @yield('content')
    </main>

    <!-- ================= CONTACT STRIP ================= -->
    <section class="bg-amber-900 text-white py-16 md:py-32 px-4 md:px-6 mt-16 md:mt-32">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 md:gap-20">

            <div>
                <h2 class="text-3xl md:text-4xl font-serif font-bold">
                    Let's build Nepal's future together.
                </h2>

                <p class="mt-4 md:mt-6 text-base md:text-lg opacity-80">
                    Whether you’re a student, educator, policymaker,
                    developer, or organization, we’d love to hear from you.
                </p>
            </div>

            <div class="space-y-4 md:space-y-6 text-sm">
                <p><i class="fa-solid fa-location-dot mr-3"></i> Kathmandu, Nepal</p>
                <p><i class="fa-solid fa-envelope mr-3"></i> hello@nexo.global</p>
                <p><i class="fa-solid fa-phone mr-3"></i> +977 98XXXXXXXX</p>
                <p class="opacity-60">
                    We respect privacy. No spam. No noise.
                </p>
            </div>

        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-amber-950 text-white/60 py-6 md:py-10 text-center text-xs md:text-sm">
        © {{ date('Y') }} NEXO.GLOBAL · Crafted with care in Nepal 🇳🇵
    </footer>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Toastr options
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        // Display success or error messages from session
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>


    @stack('scripts')
</body>

</html>
