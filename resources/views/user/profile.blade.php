@extends('layouts.app')

@section('content')
    <div class="space-y-6 md:space-y-8 pb-10 md:pb-20">
        <!-- Header -->
        <div class="animate-slide-up">
            <h2 class="text-3xl sm:text-4xl font-bold italic text-stone-900 tracking-tighter uppercase">
                My Profile
            </h2>
            <p class="text-amber-700 mt-2 font-medium italic">Manage your account and view your bookings</p>
        </div>

        <!-- User Information Card -->
        <div class="bg-gradient-to-br from-white to-amber-50/30 border-2 border-amber-300 rounded-2xl md:rounded-[3rem] shadow-lg p-6 sm:p-8 md:p-10 animate-fade-in hover:shadow-xl hover:shadow-amber-700/10 transition-all">
            <h3 class="text-xl md:text-2xl font-bold italic text-stone-900 mb-4 md:mb-6">Personal Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div class="animate-fade-in">
                    <label class="text-[10px] font-bold italic text-amber-900 uppercase tracking-widest mb-2 block">Name</label>
                    <p class="text-lg font-bold italic text-stone-900">{{ $user->name }}</p>
                </div>
                <div class="animate-fade-in">
                    <label class="text-[10px] font-bold italic text-amber-900 uppercase tracking-widest mb-2 block">Email</label>
                    <p class="text-lg font-bold italic text-stone-900">{{ $user->email }}</p>
                </div>
                @if ($user->contact)
                    <div class="animate-fade-in">
                        <label
                            class="text-[10px] font-bold italic text-amber-900 uppercase tracking-widest mb-2 block">Contact</label>
                        <p class="text-lg font-bold italic text-stone-900">{{ $user->contact }}</p>
                    </div>
                @endif
                <div class="animate-fade-in">
                    <label class="text-[10px] font-bold italic text-amber-900 uppercase tracking-widest mb-2 block">Member
                        Since</label>
                    <p class="text-lg font-bold italic text-stone-900">{{ $user->created_at->format('F Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Nexo Paisa Card -->
        <div
            class="bg-gradient-to-br from-white to-amber-50/30 border-2 border-amber-300 rounded-2xl md:rounded-[3rem] shadow-lg p-6 sm:p-8 md:p-10 animate-fade-in hover:shadow-xl hover:shadow-amber-700/10 transition-all">
            <h3 class="text-xl md:text-2xl font-bold italic text-stone-900 mb-4 md:mb-6">Nexo Paisa</h3>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0">
                <div>
                    <label class="text-[10px] font-bold italic text-amber-900 uppercase tracking-widest mb-2 block">Current
                        Balance</label>
                    <p class="text-3xl font-bold italic text-amber-700 animate-float">Rs. {{ number_format($user->nexo_paisa, 2) }}
                    </p>
                </div>
                <a href="{{ route('user.loadNexoPaisa') }}"
                    class="bg-stone-900 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-bold italic uppercase tracking-widest transition-all duration-300 hover:scale-105 hover:shadow-lg animate-bounce-in shadow-md">
                    Load Nexo Paisa
                </a>
            </div>
        </div>

        <!-- Transaction History Section -->
        <div class="bg-gradient-to-br from-white to-amber-50/30 border-2 border-amber-300 rounded-[3rem] shadow-lg p-10 animate-fade-in">
            <h3 class="text-2xl font-bold italic text-stone-900 mb-6">Transaction History</h3>

            <!-- Transaction Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-amber-50 to-white border-2 border-amber-300 rounded-2xl p-6 text-center shadow-md">
                    <div class="text-sm font-bold italic text-amber-900 uppercase tracking-widest mb-2">Total Loaded</div>
                    <div class="text-2xl font-bold italic text-amber-700">Rs. {{ number_format($totalLoaded, 2) }}</div>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-white border-2 border-red-300 rounded-2xl p-6 text-center shadow-md">
                    <div class="text-sm font-bold italic text-red-900 uppercase tracking-widest mb-2">Total Spent</div>
                    <div class="text-2xl font-bold italic text-red-600">Rs. {{ number_format($totalSpent, 2) }}</div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-white border-2 border-blue-300 rounded-2xl p-6 text-center shadow-md">
                    <div class="text-sm font-bold italic text-blue-900 uppercase tracking-widest mb-2">Current Balance</div>
                    <div class="text-2xl font-bold italic text-blue-600">Rs. {{ number_format($user->nexo_paisa, 2) }}
                    </div>
                </div>
            </div>

            @if ($transactions->isEmpty())
                <div class="text-center py-12 text-stone-400">
                    <i class="fa-solid fa-receipt text-5xl mb-4 animate-bounce"></i>
                    <p class="text-sm font-bold uppercase tracking-widest">No transactions yet</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($transactions as $index => $transaction)
                        <div
                            class="border-2 border-amber-200 rounded-2xl p-6 hover:border-amber-700 hover:shadow-lg hover:shadow-amber-700/20 transition-all duration-300 animate-fade-in hover:scale-[1.02]">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center {{ $transaction->type === 'load' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} animate-pulse">
                                        <i
                                            class="fa-solid {{ $transaction->type === 'load' ? 'fa-plus' : 'fa-minus' }} text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold italic text-stone-900">{{ $transaction->description }}</h4>
                                        <p class="text-sm text-stone-500">
                                            {{ $transaction->created_at->format('M d, Y \a\t h:i A') }}</p>
                                        @if ($transaction->metadata && isset($transaction->metadata['bank']))
                                            <p class="text-xs text-stone-400 mt-1">Bank:
                                                {{ $transaction->metadata['bank'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-3xl font-bold italic {{ $transaction->type === 'load' ? 'text-green-600' : 'text-red-600' }} animate-float">
                                        {{ $transaction->type === 'load' ? '+' : '-' }}Rs.
                                        {{ number_format($transaction->amount, 2) }}
                                    </p>
                                    <span
                                        class="text-xs font-bold italic uppercase tracking-widest px-3 py-1 rounded-full {{ $transaction->type === 'load' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $transaction->type === 'load' ? 'Credit' : 'Debit' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

        <!-- Car Bookings Section -->
        <div class="bg-gradient-to-br from-white to-amber-50/30 border-2 border-amber-300 rounded-[3rem] shadow-lg p-10 animate-fade-in">
            <h3 class="text-2xl font-bold italic text-stone-900 mb-6">Car Bookings</h3>

            @if ($carBookings->isEmpty())
                <div class="text-center py-12 text-stone-400">
                    <i class="fa-solid fa-car text-5xl mb-4"></i>
                    <p class="text-sm font-bold uppercase tracking-widest">No car bookings yet</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($carBookings as $booking)
                        <div class="border-2 border-amber-200 rounded-2xl p-6 hover:border-amber-700 hover:shadow-lg hover:shadow-amber-700/20 transition-all">
                            <div class="flex flex-col md:flex-row gap-6">
                                @if ($booking->car && $booking->car->image_url)
                                    <div class="md:w-48 h-32 rounded-xl overflow-hidden">
                                        <img src="{{ asset($booking->car->image_url) }}" alt="{{ $booking->car->name }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="text-xl font-bold italic text-stone-900 mb-2">
                                        {{ $booking->car ? $booking->car->name : $booking->car_details['name'] ?? 'Car' }}
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <span class="text-amber-700 font-bold italic uppercase text-xs">Days</span>
                                            <p class="font-bold italic text-stone-900">{{ $booking->days }}</p>
                                        </div>
                                        <div>
                                            <span class="text-amber-700 font-bold italic uppercase text-xs">Price/Day</span>
                                            <p class="font-bold italic text-stone-900">Rs.
                                                {{ number_format($booking->price_per_day, 2) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-amber-700 font-bold italic uppercase text-xs">Total</span>
                                            <p class="font-bold italic text-amber-700">Rs.
                                                {{ number_format($booking->total_amount, 2) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-amber-700 font-bold italic uppercase text-xs">Date</span>
                                            <p class="font-bold italic text-stone-900">
                                                {{ $booking->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    @if ($booking->car)
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <span
                                                class="text-xs font-bold italic uppercase tracking-widest bg-amber-100 text-amber-900 px-3 py-1 rounded-full">
                                                {{ $booking->car->type }}
                                            </span>
                                            <span
                                                class="text-xs font-bold italic uppercase tracking-widest bg-amber-100 text-amber-900 px-3 py-1 rounded-full">
                                                {{ $booking->car->transmission }}
                                            </span>
                                            <span
                                                class="text-xs font-bold italic uppercase tracking-widest bg-amber-100 text-amber-900 px-3 py-1 rounded-full">
                                                {{ $booking->car->seating_capacity }} Seater
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Hotel Bookings Section -->
        <div class="bg-gradient-to-br from-white to-amber-50/30 border-2 border-amber-300 rounded-[3rem] shadow-lg p-10 animate-fade-in">
            <h3 class="text-2xl font-bold italic text-stone-900 mb-6">Hotel Bookings</h3>

            @if ($hotelBookings->isEmpty())
                <div class="text-center py-12 text-stone-400">
                    <i class="fa-solid fa-hotel text-5xl mb-4"></i>
                    <p class="text-sm font-bold uppercase tracking-widest">No hotel bookings yet</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($hotelBookings as $booking)
                        <div class="border-2 border-amber-200 rounded-2xl p-6 hover:border-amber-700 hover:shadow-lg hover:shadow-amber-700/20 transition-all">
                            <div class="flex flex-col md:flex-row gap-6">
                                @if ($booking->image_url)
                                    <div class="md:w-48 h-32 rounded-xl overflow-hidden">
                                        <img src="{{ $booking->image_url }}" alt="{{ $booking->hotel_name }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="text-xl font-bold italic text-stone-900">
                                            {{ $booking->hotel_name }}
                                        </h4>
                                        @if ($booking->rating)
                                            <div
                                                class="bg-stone-900 text-white px-3 py-1 rounded-full text-xs font-bold italic">
                                                ★ {{ $booking->rating }}
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-sm text-stone-600 mb-4 flex items-center gap-2 italic font-medium">
                                        <i class="fa-solid fa-location-dot text-amber-700"></i>
                                        {{ $booking->location }}
                                    </p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <span class="text-amber-700 font-bold italic uppercase text-xs">Nights</span>
                                            <p class="font-bold italic text-stone-900">{{ $booking->nights }}</p>
                                        </div>
                                        <div>
                                            <span class="text-amber-700 font-bold italic uppercase text-xs">Price/Night</span>
                                            <p class="font-bold italic text-stone-900">Rs.
                                                {{ number_format($booking->price_per_night, 2) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-amber-700 font-bold italic uppercase text-xs">Total</span>
                                            <p class="font-bold italic text-amber-700">Rs.
                                                {{ number_format($booking->total_amount, 2) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-amber-700 font-bold italic uppercase text-xs">Date</span>
                                            <p class="font-bold italic text-stone-900">
                                                {{ $booking->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    @if ($booking->amenities && count($booking->amenities) > 0)
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach ($booking->amenities as $amenity)
                                                <span
                                                    class="text-xs font-bold italic uppercase tracking-widest bg-amber-100 text-amber-900 px-3 py-1 rounded-full">
                                                    {{ $amenity }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
