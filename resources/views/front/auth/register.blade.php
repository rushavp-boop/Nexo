@extends('front.layouts.app')

@section('title', 'Register · NEXO.GLOBAL')

@section('content')
<div class="min-h-[70vh] md:min-h-[80vh] flex items-center justify-center px-4 md:px-6 py-12 md:py-16">

    <div class="bg-white rounded-2xl md:rounded-[3rem] shadow-xl md:shadow-2xl w-full max-w-md p-6 sm:p-8 md:p-12 space-y-6 md:space-y-8">

        <h2 class="text-2xl md:text-3xl font-serif font-bold italic text-center">Create Your Account</h2>

        <form action="{{ route('register') }}" method="POST" class="space-y-5 md:space-y-6">
            @csrf

            <div>
                <label class="text-[10px] md:text-xs uppercase tracking-wider md:tracking-widest opacity-60">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full mt-2 bg-amber-50 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 font-semibold focus:ring-1 focus:ring-amber-600 border-none text-sm md:text-base">
                @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-[10px] md:text-xs uppercase tracking-wider md:tracking-widest opacity-60">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full mt-2 bg-amber-50 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 font-semibold focus:ring-1 focus:ring-amber-600 border-none text-sm md:text-base">
                @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-[10px] md:text-xs uppercase tracking-wider md:tracking-widest opacity-60">Contact</label>
                <input type="text" name="contact" value="{{ old('contact') }}"
                       class="w-full mt-2 bg-amber-50 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 font-semibold focus:ring-1 focus:ring-amber-600 border-none text-sm md:text-base">
                @error('contact')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-[10px] md:text-xs uppercase tracking-wider md:tracking-widest opacity-60">Password</label>
                <input type="password" name="password"
                       class="w-full mt-2 bg-amber-50 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 font-semibold focus:ring-1 focus:ring-amber-600 border-none text-sm md:text-base">
                @error('password')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-[10px] md:text-xs uppercase tracking-wider md:tracking-widest opacity-60">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full mt-2 bg-amber-50 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 font-semibold focus:ring-1 focus:ring-amber-600 border-none text-sm md:text-base">
            </div>

            <button type="submit"
                    class="w-full bg-amber-900 text-white py-4 md:py-5 rounded-xl md:rounded-2xl uppercase tracking-wider md:tracking-widest text-[10px] md:text-xs font-semibold hover:bg-amber-800 transition">
                Register
            </button>

        </form>

        <p class="text-center text-sm text-amber-700">
            Already have an account? <a href="{{ route('login') }}" class="text-amber-600 font-semibold hover:underline">Login</a>
        </p>
    </div>
</div>
@endsection
