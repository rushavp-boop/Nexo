@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

@section('content')
    <div x-data="travelApp()" x-init="initialize()" class="space-y-6 md:space-y-8 pb-10 md:pb-20 animate-fadeIn">

        <!-- Tabs -->
        <div
            class="flex bg-amber-50 p-1.5 rounded-xl md:rounded-[1.5rem] border border-amber-200 w-full md:w-fit shadow-inner overflow-x-auto no-scrollbar">
            <template x-for="(tab, idx) in tabs" :key="idx">
                <button @click="selectTab(tab.id)"
                    :class="currentTab === tab.id ? 'bg-stone-900 text-white' : 'text-amber-700 hover:text-amber-900'"
                    class="px-4 sm:px-6 md:px-8 py-2.5 md:py-3 rounded-xl md:rounded-2xl text-[9px] md:text-[10px] font-bold italic uppercase tracking-[0.15em] md:tracking-[0.2em] transition-all whitespace-nowrap flex-shrink-0">
                    <span x-text="tab.name"></span>
                </button>
            </template>
        </div>

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 md:gap-4">
            <h2 class="text-2xl sm:text-3xl font-bold italic text-stone-900 tracking-tighter uppercase">
                Nexo<span class="text-amber-700">.Travel</span>
            </h2>
        </div>

        <!-- Plan Tab -->
        <div x-show="currentTab === 'plan'" class="space-y-6 md:space-y-8">
            <!-- Input Form -->
            <div class="bg-white border border-black/5 p-4 sm:p-6 md:p-8 lg:p-12 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3rem] shadow-md sm:shadow-lg md:shadow-xl">
                <h3
                    class="text-lg md:text-xl font-bold italic text-stone-900 mb-6 md:mb-10 flex items-center gap-3 md:gap-4 uppercase tracking-tighter">
                    <i class="fa-solid fa-compass text-amber-700"></i> Plan your Trip
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
                    <div class="space-y-2">
                        <label class="text-[9px] md:text-[10px] font-bold italic text-amber-700 uppercase tracking-wider md:tracking-widest ml-1">Where
                            to?</label>
                        <input type="text" x-model="destination" @input="saveDestination()" placeholder="e.g. Pokhara"
                            class="w-full bg-amber-50 border border-amber-200 text-stone-900 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 focus:ring-1 focus:ring-amber-700 font-bold" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] md:text-[10px] font-bold italic text-amber-700 uppercase tracking-wider md:tracking-widest ml-1">How
                            many
                            days?</label>
                        <input type="number" x-model.number="days" min="1"
                            class="w-full bg-amber-50 border border-amber-200 text-stone-900 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 focus:ring-1 focus:ring-amber-700 font-bold" />
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-[9px] md:text-[10px] font-bold italic text-amber-700 uppercase tracking-wider md:tracking-widest ml-1">Budget</label>
                        <select x-model="budget"
                            class="w-full bg-amber-50 border border-amber-200 text-stone-900 rounded-xl md:rounded-2xl px-4 md:px-6 py-3 md:py-4 focus:ring-1 focus:ring-amber-700 font-bold">
                            <option>Affordable</option>
                            <option>Standard</option>
                            <option>Premium</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button @click="fetchPlan()" :disabled="loading"
                            class="w-full h-12 sm:h-14 md:h-16 lg:h-[64px] bg-stone-900 hover:bg-amber-700 disabled:bg-stone-400 text-white rounded-xl sm:rounded-2xl font-bold italic uppercase tracking-widest shadow-lg sm:shadow-xl transition-all flex items-center justify-center gap-2 sm:gap-3">
                            <span x-show="!loading">GENERATE ITINERARY</span>
                            <span x-show="loading"><i class="fa-solid fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="bg-white border border-black/5 p-4 sm:p-6 md:p-8 lg:p-10 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3rem] shadow-md sm:shadow-lg md:shadow-xl space-y-6">
                <div
                    class="bg-gradient-to-r from-amber-50 via-yellow-50 to-amber-50 border border-amber-200 rounded-2xl p-4 md:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h3
                                class="text-lg md:text-xl font-bold italic text-stone-900 flex items-center gap-3 uppercase tracking-tighter">
                                <i class="fa-solid fa-map-location-dot text-amber-700"></i> Nepal Map & Popular Destinations
                            </h3>
                            <p class="text-xs md:text-sm text-stone-600 font-medium mt-2">
                                Explore Nepal-only map, click a destination, and generate a tailored itinerary instantly.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full bg-white border border-amber-200 text-amber-700">
                                <span x-text="filteredPopularDestinations().length"></span> destinations
                            </span>
                            <span x-show="selectedDestinationSummary" x-cloak
                                class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full bg-stone-900 text-white">
                                selected: <span x-text="selectedDestinationSummary?.name"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-6">
                    <div class="xl:col-span-2 space-y-4">
                        <div id="nepalMap"
                            class="h-80 md:h-[34rem] lg:h-[38rem] rounded-2xl border-2 border-amber-300 overflow-hidden shadow-inner"></div>

                        <div x-show="selectedDestinationSummary" x-cloak
                            class="bg-amber-50 border border-amber-200 rounded-2xl p-4 md:p-5 animate-fadeIn">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-stretch">
                                <div class="md:col-span-1 overflow-hidden rounded-xl border border-amber-200 bg-white">
                                    <img :src="selectedDestinationSummary?.image || defaultDestinationImage"
                                        :alt="selectedDestinationSummary?.name || 'Destination'"
                                        class="w-full h-36 md:h-full object-cover" />
                                </div>
                                <div class="md:col-span-2">
                                    <h4 class="text-base font-bold italic text-stone-900 flex items-center gap-2">
                                        <i class="fa-solid fa-compass text-amber-700"></i>
                                        <span x-text="selectedDestinationSummary?.name"></span>
                                    </h4>
                                    <p class="text-sm text-stone-700 mt-2 leading-relaxed" x-text="selectedDestinationSummary?.description"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="xl:col-span-1 bg-amber-50/60 border border-amber-200 rounded-2xl p-4 md:p-5 space-y-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold italic text-amber-700 uppercase tracking-widest">Search destination</label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-amber-700 text-xs"></i>
                                <input type="text" x-model="destinationSearch" placeholder="Type e.g. Pokhara"
                                    class="w-full bg-white border border-amber-200 text-stone-900 rounded-xl pl-9 pr-4 py-2.5 focus:ring-1 focus:ring-amber-700 font-semibold text-sm" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-[10px] font-bold italic text-amber-700 uppercase tracking-widest">Regions</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="region in regionOptions()" :key="region">
                                    <button @click="selectedRegion = region"
                                        :class="selectedRegion === region ? 'bg-stone-900 text-white border-stone-900' : 'bg-white text-amber-700 border-amber-200 hover:border-amber-700'"
                                        class="text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full border transition-all">
                                        <span x-text="region"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="space-y-2 max-h-[22rem] overflow-y-auto pr-1">
                            <p class="text-[10px] font-bold italic text-amber-700 uppercase tracking-widest">Popular Destinations</p>
                            <template x-for="place in filteredPopularDestinations()" :key="place.name">
                                <button @click="selectPopularDestination(place)"
                                    :class="activeDestinationName === place.name ? 'border-stone-900 bg-stone-900 text-white' : 'border-amber-200 bg-white hover:border-amber-700 text-stone-900'"
                                    class="w-full text-left border rounded-xl px-3 py-3 transition-all shadow-sm hover:shadow">
                                    <div class="flex items-center gap-3">
                                        <img :src="getDestinationImage(place.name)" :alt="place.name"
                                            class="h-12 w-16 rounded-lg object-cover border border-amber-200" />
                                        <div>
                                            <p class="text-sm font-bold italic flex items-center gap-2">
                                                <i class="fa-solid fa-location-dot text-amber-700 text-[10px]"
                                                    :class="activeDestinationName === place.name ? 'text-amber-300' : 'text-amber-700'"></i>
                                                <span x-text="place.name"></span>
                                            </p>
                                            <p class="text-[10px] font-bold uppercase tracking-widest mt-1"
                                                :class="activeDestinationName === place.name ? 'text-amber-200' : 'text-amber-700'"
                                                x-text="place.region"></p>
                                        </div>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Itinerary Cards Grid -->
            <div x-show="itinerary" class="grid grid-cols-1 md:grid-cols-3 gap-8 animate-slideUp">
                <template x-for="(day, dayIdx) in parseItinerary(itinerary)" :key="dayIdx">
                    <div @click="selectedDay = day"
                        class="bg-gradient-to-br from-amber-50 to-amber-50/50 border-2 border-amber-200 p-8 rounded-3xl hover:border-amber-700 hover:shadow-2xl hover:shadow-amber-700/20 transition-all duration-300 cursor-pointer group">
                        <div class="flex justify-between items-start mb-6">
                            <span class="text-sm font-bold italic text-stone-900 uppercase tracking-widest"
                                x-text="'Day ' + day.day"></span>
                            <div
                                class="h-3 w-3 rounded-full bg-amber-700 group-hover:scale-150 transition-transform duration-300">
                            </div>
                        </div>

                        <!-- Preview Activities (first 2) -->
                        <div class="space-y-3 mb-6">
                            <template x-for="(activity, idx) in day.desc.slice(0, 2)" :key="idx">
                                <div class="flex items-start gap-2">
                                    <div class="min-w-[6px] h-[6px] rounded-full bg-amber-700 mt-2"></div>
                                    <p class="text-sm text-stone-700 font-medium line-clamp-2" x-text="activity">
                                    </p>
                                </div>
                            </template>
                        </div>

                        <div class="pt-4 border-t border-black/10 space-y-4">
                            <div>
                                <p class="text-[9px] font-bold text-amber-900/60 uppercase tracking-widest mb-1">Daily Budget</p>
                                <span class="text-sm font-bold italic text-amber-700"
                                    x-text="formatBudget(day.budget)"></span>
                            </div>
                            <div class="flex justify-end">
                                <span
                                    class="text-xs font-semibold text-stone-500 group-hover:text-amber-700 transition-colors">View
                                    Details →</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="savedItineraries.length > 0"
                class="bg-white border border-black/5 p-4 sm:p-6 md:p-8 lg:p-10 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3rem] shadow-md sm:shadow-lg md:shadow-xl">
                <h3
                    class="text-lg md:text-xl font-bold italic text-stone-900 mb-4 sm:mb-6 flex items-center gap-3 uppercase tracking-tighter">
                    <i class="fa-solid fa-clock-rotate-left text-amber-700"></i> Saved Itineraries
                </h3>

                <div class="space-y-4">
                    <template x-for="item in savedItineraries" :key="item.id">
                        <div class="border border-amber-200 rounded-2xl p-4 sm:p-5 hover:border-amber-700 transition-all">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <p class="text-base font-bold italic text-stone-900" x-text="item.destination"></p>
                                    <p class="text-xs text-amber-700 font-bold uppercase tracking-widest"
                                        x-text="`${item.days} days • ${item.budget}`"></p>
                                    <p class="text-xs text-stone-500 mt-1" x-text="item.createdAt"></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="itinerary = item.itinerary; currentTab = 'plan'; selectedDay = null"
                                        class="bg-stone-900 hover:bg-amber-700 text-white px-4 py-2 rounded-xl text-xs font-bold italic uppercase tracking-widest transition-all">
                                        View Itinerary
                                    </button>
                                    <button @click="deleteSavedItinerary(item.id)"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-xs font-bold italic uppercase tracking-widest transition-all">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Expanded Day Modal -->
            <div x-show="selectedDay" x-cloak @click.self="selectedDay = null"
                class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fadeIn">
                <div @click.stop
                    class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-2xl animate-scaleIn">

                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-amber-700 to-amber-600 p-8 relative">
                        <button @click="selectedDay = null"
                            class="absolute top-6 right-6 w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-all group">
                            <span
                                class="text-white text-2xl group-hover:rotate-90 transition-transform duration-300">×</span>
                        </button>
                        <h2 class="text-3xl font-bold italic text-white uppercase tracking-wider"
                            x-text="selectedDay ? 'Day ' + selectedDay.day : ''"></h2>
                    </div>

                    <!-- Modal Content -->
                    <div class="overflow-y-auto max-h-[calc(90vh-180px)]">
                        <template x-if="selectedDay">
                            <div>
                                <!-- Time Periods -->
                                <div class="p-8 space-y-8">
                                    <template x-for="(period, periodName) in groupActivitiesByTime(selectedDay.desc)"
                                        :key="periodName">
                                        <div x-show="period.length > 0">
                                            <!-- Period Header -->
                                            <div class="flex items-center gap-3 mb-4">
                                                <div
                                                    class="w-10 h-10 bg-gradient-to-br from-amber-700 to-amber-600 rounded-full flex items-center justify-center shadow-lg shadow-amber-700/30">
                                                    <span class="text-lg"
                                                        x-text="periodName === 'morning' ? '🌅' : periodName === 'afternoon' ? '☀️' : periodName === 'evening' ? '🌆' : '🌙'"></span>
                                                </div>
                                                <h3 class="text-xl font-bold italic text-amber-700 uppercase tracking-wide"
                                                    x-text="periodName.charAt(0).toUpperCase() + periodName.slice(1)"></h3>
                                            </div>

                                            <!-- Period Activities -->
                                            <div class="ml-12 space-y-3">
                                                <template x-for="activity in period" :key="activity">
                                                    <div class="flex items-start gap-3 group">
                                                        <div
                                                            class="min-w-[8px] h-[8px] rounded-full bg-amber-700 mt-2 group-hover:scale-150 transition-transform">
                                                        </div>
                                                        <p class="text-stone-800 font-medium leading-relaxed"
                                                            x-text="activity.replace(/^(Morning|Afternoon|Evening|Night):\s*/, '')">
                                                        </p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Budget Section -->
                                <div
                                    class="bg-gradient-to-r from-amber-50 to-amber-50 border-t-2 border-amber-700/20 p-8">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4
                                                class="text-xs font-bold italic text-amber-900 uppercase tracking-widest mb-1">
                                                Daily Budget</h4>
                                            <p class="text-2xl font-bold italic text-amber-700"
                                                x-text="selectedDay.budget"></p>
                                        </div>
                                        <div
                                            class="w-16 h-16 bg-gradient-to-br from-amber-700 to-amber-600 rounded-2xl flex items-center justify-center shadow-xl shadow-amber-700/30">
                                            <span class="text-3xl">💰</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hotels Tab -->
        <div x-show="currentTab === 'hotels'" class="space-y-12">
            <div x-show="!destination"
                class="bg-amber-50 p-16 rounded-[3rem] border border-dashed border-amber-200 text-center">
                <i class="fa-solid fa-hotel text-5xl text-amber-100 mb-6"></i>
                <p class="text-amber-700 font-bold italic uppercase tracking-widest text-xs">Enter a destination
                    to see live hotel options.</p>
            </div>

            <div x-show="destination" class="grid grid-cols-1 lg:grid-cols-2 gap-10 animate-fadeIn">
                <div x-show="loading && !hotels.length" class="col-span-full py-20 text-center">
                    <div
                        class="h-12 w-12 border-4 border-orange-600 border-t-transparent rounded-full animate-spin mx-auto mb-4">
                    </div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] italic font-serif">Loading hotels...</p>
                </div>

                <template x-for="(hotel, hotelIdx) in hotels" :key="hotelIdx">
                    <div
                        class="bg-white border border-black/5 rounded-[3.5rem] overflow-hidden shadow-xl flex flex-col transition-all hover:shadow-2xl">
                        <!-- Hotel Image -->
                        <div class="h-64 relative overflow-hidden group">
                            <img :src="hotel.image || 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                :alt="hotel.name" />
                            <div
                                class="absolute top-6 left-6 bg-stone-900/90 text-white px-4 py-1.5 rounded-full text-[10px] font-bold italic uppercase tracking-widest backdrop-blur-md">
                                <span>★ <span x-text="hotel.rating || '4.5'"></span></span>
                            </div>
                        </div>

                        <!-- Hotel Details -->
                        <div class="p-10 flex-1 space-y-6">
                            <div>
                                <h4 class="text-3xl font-bold italic text-stone-900" x-text="hotel.name"></h4>
                                <p
                                    class="text-[11px] font-bold italic text-amber-700 uppercase tracking-widest mt-2 flex items-center gap-2">
                                    <i class="fa-solid fa-location-dot text-amber-700"></i>
                                    <span x-text="hotel.location"></span>
                                </p>
                            </div>

                            <!-- Amenities -->
                            <div class="flex flex-wrap gap-2">
                                <template x-for="(amenity, aidx) in (hotel.amenities || [])" :key="aidx">
                                    <span
                                        class="text-[9px] font-bold italic uppercase tracking-widest bg-amber-100 px-3 py-1.5 rounded-full text-amber-900"
                                        x-text="amenity"></span>
                                </template>
                            </div>

                            <!-- Price & Book -->
                            <div class="pt-6 border-t border-black/5">
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold italic text-stone-900">
                                        Rs. <span x-text="hotel.pricePerNight || '5000'"></span>
                                        <span class="text-xs font-medium text-stone-400 uppercase opacity-60">/
                                            night</span>
                                    </span>
                                    <button @click="bookHotel(hotel)"
                                        class="bg-stone-900 text-white px-8 py-3 rounded-xl font-bold italic text-[10px] uppercase tracking-widest hover:bg-amber-700 transition-all shadow-md">Book
                                        Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Cars Tab -->
        <div x-show="currentTab === 'cars'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 animate-fadeIn">

            <template x-for="(car, carIdx) in cars" :key="car.id ?? carIdx">
                <div
                    class="group bg-gradient-to-br from-amber-50 to-white border-2 border-amber-200 rounded-[3.5rem] overflow-hidden hover:border-amber-700 hover:shadow-2xl hover:shadow-amber-700/20 transition-all shadow-lg">

                    <!-- Car Image -->
                    <div class="h-60 overflow-hidden relative">
                        <img :src="car.image_url ?? 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=400'"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            :alt="car.name" />
                        <div class="absolute bottom-6 left-6 bg-stone-900/90 backdrop-blur-md px-5 py-2 rounded-full text-[10px] font-bold italic text-white uppercase tracking-widest shadow-md"
                            x-text="car.type">
                        </div>
                    </div>

                    <!-- Car Details -->
                    <div class="p-10 space-y-4">
                        <h4 class="text-2xl font-bold italic text-stone-900" x-text="car.name"></h4>

                        <p class="text-xs text-amber-700 font-bold italic uppercase mt-3 tracking-tight">
                            <span x-text="car.transmission"></span> •
                            <span x-text="car.fuelType"></span> •
                            <span x-text="car.seatingCapacity + ' Seater'"></span>
                        </p>

                        <div class="mt-6 flex items-center justify-between border-t border-amber-200 pt-6">
                            <div>
                                <span class="text-2xl font-bold italic text-amber-700">
                                    Rs. <span x-text="car.pricePerDay"></span>
                                </span>
                                <span class="text-[10px] text-amber-600 font-bold italic ml-1 uppercase">/ day</span>
                            </div>

                            <button @click="rentCar(car)"
                                class="bg-stone-900 text-white px-6 py-4 rounded-xl font-bold italic text-[10px] uppercase tracking-widest hover:bg-amber-700 transition-all shadow-md hover:shadow-lg">
                                Rent
                            </button>
                        </div>
                    </div>

                </div>
            </template>

            <!-- Empty State -->
            <div x-show="!loading && cars.length === 0"
                class="col-span-full text-center py-24 text-amber-700 font-bold italic uppercase tracking-widest">
                No cars available
            </div>
        </div>

        <!-- Loading Indicator -->
        <div x-show="loading" class="fixed inset-0 bg-black/20 flex items-center justify-center z-50 rounded-2xl">
            <div class="bg-white p-8 rounded-[2rem] shadow-2xl">
                <div
                    class="h-12 w-12 border-4 border-amber-700 border-t-transparent rounded-full animate-spin mx-auto mb-4">
                </div>
                <p class="text-xs font-bold italic uppercase tracking-[0.2em] text-center">Processing...</p>
            </div>
        </div>
    </div>

    <script>
        window.travelApp = function travelApp() {
            return {
                tabs: [{
                        id: 'plan',
                        name: 'Plan'
                    },
                    {
                        id: 'hotels',
                        name: 'Hotels'
                    },
                    {
                        id: 'cars',
                        name: 'Cars'
                    }
                ],
                currentTab: 'plan',
                destination: sessionStorage.getItem('travel_destination') || '',
                days: 3,
                budget: 'Standard',
                loading: false,
                itinerary: null,
                hotels: [],
                cars: [],
                savedItineraries: [],
                selectedDay: null,
                map: null,
                destinationMarker: null,
                selectedDestinationSummary: null,
                destinationSearch: '',
                selectedRegion: 'All',
                activeDestinationName: '',
                destinationImages: {},
                defaultDestinationImage: 'https://images.unsplash.com/photo-1528127269322-539801943592?q=80&w=1200&auto=format&fit=crop',
                popularDestinations: [{
                        name: 'Pokhara',
                        wikiTitle: 'Pokhara',
                        region: 'Gandaki',
                        lat: 28.2096,
                        lng: 83.9856,
                        description: 'Lakeside city with mountain views, boating on Phewa Lake, and easy access to Annapurna treks.'
                    },
                    {
                        name: 'Kathmandu',
                        wikiTitle: 'Kathmandu',
                        region: 'Bagmati',
                        lat: 27.7172,
                        lng: 85.324,
                        description: 'Historic capital with UNESCO heritage sites, temples, local markets, and rich Newari culture.'
                    },
                    {
                        name: 'Chitwan',
                        wikiTitle: 'Chitwan_National_Park',
                        region: 'Bagmati',
                        lat: 27.5291,
                        lng: 84.3542,
                        description: 'Wildlife destination known for Chitwan National Park, jungle safaris, and river-side activities.'
                    },
                    {
                        name: 'Lumbini',
                        wikiTitle: 'Lumbini',
                        region: 'Lumbini',
                        lat: 27.4833,
                        lng: 83.276,
                        description: 'Birthplace of Buddha with sacred gardens, monasteries, and peaceful heritage zones.'
                    },
                    {
                        name: 'Mustang',
                        wikiTitle: 'Mustang_District',
                        region: 'Gandaki',
                        lat: 29.1624,
                        lng: 83.996,
                        description: 'High-altitude Himalayan region with dramatic landscapes, monasteries, and unique mountain culture.'
                    },
                    {
                        name: 'Ilam',
                        wikiTitle: 'Ilam_District',
                        region: 'Koshi',
                        lat: 26.911,
                        lng: 87.9236,
                        description: 'Scenic eastern hills known for tea gardens, cool weather, and panoramic sunrise viewpoints.'
                    },
                    {
                        name: 'Nagarkot',
                        wikiTitle: 'Nagarkot',
                        region: 'Bagmati',
                        lat: 27.7172,
                        lng: 85.521,
                        description: 'Popular hill station near Kathmandu for Himalayan sunrise views and short scenic hikes.'
                    },
                    {
                        name: 'Bandipur',
                        wikiTitle: 'Bandipur',
                        region: 'Gandaki',
                        lat: 27.9397,
                        lng: 84.406,
                        description: 'Preserved hill town with traditional architecture, caves, and panoramic mountain ridges.'
                    },
                    {
                        name: 'Janakpur',
                        wikiTitle: 'Janakpur',
                        region: 'Madhesh',
                        lat: 26.7288,
                        lng: 85.925,
                        description: 'Cultural and religious center famous for Janaki Temple and Mithila heritage traditions.'
                    },
                    {
                        name: 'Rara Lake',
                        wikiTitle: 'Rara_Lake',
                        region: 'Karnali',
                        lat: 29.529,
                        lng: 82.078,
                        description: 'Nepal\'s largest lake with pristine alpine scenery, quiet trails, and remote wilderness.'
                    },
                    {
                        name: 'Gosaikunda',
                        wikiTitle: 'Gosaikunda',
                        region: 'Bagmati',
                        lat: 28.083,
                        lng: 85.417,
                        description: 'Sacred high-altitude lake trek destination with dramatic terrain and mountain passes.'
                    },
                    {
                        name: 'Bardiya',
                        wikiTitle: 'Bardiya_National_Park',
                        region: 'Lumbini',
                        lat: 28.3852,
                        lng: 81.389,
                        description: 'Wildlife reserve known for quieter safari experiences, river landscapes, and tiger habitat.'
                    },
                    {
                        name: 'Patan Durbar Square',
                        wikiTitle: 'Patan_Durbar_Square',
                        region: 'Bagmati',
                        lat: 27.6734,
                        lng: 85.3256,
                        description: 'Historic royal square with Newari art, temples, courtyards, and museum-rich heritage zones.'
                    },
                    {
                        name: 'Bhaktapur',
                        wikiTitle: 'Bhaktapur',
                        region: 'Bagmati',
                        lat: 27.672,
                        lng: 85.4298,
                        description: 'Medieval city with preserved squares, pottery lanes, woodwork, and classic Newari architecture.'
                    },
                    {
                        name: 'Dharan',
                        wikiTitle: 'Dharan',
                        region: 'Koshi',
                        lat: 26.8121,
                        lng: 87.2834,
                        description: 'Vibrant eastern city gateway to hills and tea regions, known for food and viewpoints.'
                    },
                    {
                        name: 'Tansen',
                        wikiTitle: 'Tansen',
                        region: 'Lumbini',
                        lat: 27.8686,
                        lng: 83.546,
                        description: 'Charming hill town with old bazaars, mountain views, and rich local handicraft culture.'
                    },
                    {
                        name: 'Khaptad',
                        wikiTitle: 'Khaptad_National_Park',
                        region: 'Sudurpashchim',
                        lat: 29.377,
                        lng: 81.029,
                        description: 'Highland meadows and forests ideal for nature retreat, short treks, and serene landscapes.'
                    }
                ],

                initialize() {
                    this.loadSavedItineraries();
                    this.$nextTick(() => {
                        this.initMap();
                        this.loadDestinationImages();
                        if (this.destination) {
                            this.pinDestinationByName(this.destination);
                        }
                    });
                },

                saveDestination() {
                    sessionStorage.setItem('travel_destination', this.destination);
                },

                regionOptions() {
                    const regions = [...new Set(this.popularDestinations.map(place => place.region))].sort();
                    return ['All', ...regions];
                },

                filteredPopularDestinations() {
                    const search = this.destinationSearch.trim().toLowerCase();

                    return this.popularDestinations.filter(place => {
                        const matchesRegion = this.selectedRegion === 'All' || place.region === this.selectedRegion;
                        const matchesSearch = !search ||
                            place.name.toLowerCase().includes(search) ||
                            place.region.toLowerCase().includes(search);

                        return matchesRegion && matchesSearch;
                    });
                },

                async loadDestinationImages() {
                    const fetches = this.popularDestinations.map(async (place) => {
                        const wikiTitle = place.wikiTitle || place.name.replace(/\s+/g, '_');
                        try {
                            const response = await fetch(`https://en.wikipedia.org/api/rest_v1/page/summary/${encodeURIComponent(wikiTitle)}`);
                            if (!response.ok) return;
                            const payload = await response.json();
                            const source = payload?.thumbnail?.source;
                            if (source) {
                                this.destinationImages[place.name] = source;
                            }
                        } catch (error) {
                            console.error('Image fetch failed for', place.name, error);
                        }
                    });

                    await Promise.allSettled(fetches);
                },

                getDestinationImage(placeName) {
                    return this.destinationImages[placeName] || this.defaultDestinationImage;
                },

                initMap() {
                    if (this.map || typeof L === 'undefined') return;

                    this.map = L.map('nepalMap', {
                        zoomControl: true,
                        minZoom: 6,
                        maxZoom: 17,
                        maxBoundsViscosity: 1.0,
                        worldCopyJump: false
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        noWrap: true,
                        bounds: [
                            [26.347, 80.058],
                            [30.447, 88.201]
                        ],
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(this.map);

                    const nepalBounds = [
                        [26.347, 80.058],
                        [30.447, 88.201]
                    ];

                    this.map.fitBounds(nepalBounds);
                    this.map.setMaxBounds([
                        [26.347, 80.058],
                        [30.447, 88.201]
                    ]);

                    this.map.on('click', async (event) => {
                        const locationName = await this.reverseGeocode(event.latlng.lat, event.latlng.lng);
                        const resolvedName = locationName || `${event.latlng.lat.toFixed(4)}, ${event.latlng.lng.toFixed(4)}`;
                        this.destination = resolvedName;
                        this.activeDestinationName = resolvedName;
                        this.saveDestination();
                        this.selectedDestinationSummary = {
                            name: resolvedName,
                            description: 'Location selected from the map. Adjust days and budget, then generate your itinerary.',
                            image: this.getDestinationImage(resolvedName)
                        };
                        this.setMapMarker(event.latlng.lat, event.latlng.lng, resolvedName, this.selectedDestinationSummary.description);
                    });
                },

                async reverseGeocode(lat, lng) {
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
                        if (!response.ok) return null;
                        const payload = await response.json();
                        return payload?.name || payload?.address?.city || payload?.address?.town || payload?.address?.village ||
                            payload?.display_name?.split(',')[0] || null;
                    } catch (error) {
                        console.error('Reverse geocode failed:', error);
                        return null;
                    }
                },

                setMapMarker(lat, lng, title, description = '') {
                    if (!this.map) return;

                    if (this.destinationMarker) {
                        this.map.removeLayer(this.destinationMarker);
                    }

                    this.destinationMarker = L.marker([lat, lng]).addTo(this.map);
                    this.destinationMarker.bindPopup(`<b>${title}</b><br>${description || 'Selected destination in Nepal.'}`);
                    this.destinationMarker.openPopup();
                    this.map.setView([lat, lng], 10);
                },

                async pinDestinationByName(placeName) {
                    if (!placeName || !this.map) return;

                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/search?format=jsonv2&countrycodes=np&limit=1&q=${encodeURIComponent(placeName)}`
                        );
                        if (!response.ok) return;

                        const results = await response.json();
                        if (!Array.isArray(results) || results.length === 0) return;

                        const first = results[0];
                        this.activeDestinationName = placeName;
                        this.setMapMarker(Number(first.lat), Number(first.lon), placeName,
                            'Destination selected for itinerary planning.');
                    } catch (error) {
                        console.error('Forward geocode failed:', error);
                    }
                },

                selectPopularDestination(place) {
                    this.destination = place.name;
                    this.days = this.days || 3;
                    this.saveDestination();
                    this.activeDestinationName = place.name;
                    this.selectedDestinationSummary = {
                        name: place.name,
                        description: place.description,
                        image: this.getDestinationImage(place.name)
                    };
                    this.setMapMarker(place.lat, place.lng, place.name, place.description);
                },

                parseItinerary(data) {
                    try {
                        console.log('Raw data:', data);

                        if (!data) return [];

                        // Handle double-nested JSON string
                        let parsed = data;

                        // If data.plan is a string, parse it (remove trailing commas first)
                        if (data && typeof data.plan === 'string') {
                            // Remove trailing commas before closing brackets/braces
                            let cleanedJson = data.plan
                                .replace(/,(\s*[}\]])/g, '$1') // Remove trailing commas
                                .replace(/\n/g, ' ') // Remove newlines for easier parsing
                                .trim();

                            try {
                                parsed = JSON.parse(cleanedJson);
                            } catch (jsonError) {
                                console.error('JSON parse error, trying alternative method:', jsonError);
                                // If still fails, try to extract the plan array manually
                                return this.fallbackParse(data.plan);
                            }
                        }

                        // If data itself is a string, parse it
                        if (typeof data === 'string') {
                            let cleanedJson = data.replace(/,(\s*[}\]])/g, '$1').trim();
                            parsed = JSON.parse(cleanedJson);
                        }

                        console.log('Parsed data:', parsed);

                        if (!parsed || !parsed.plan) return [];

                        if (Array.isArray(parsed.plan)) {
                            return parsed.plan.map(day => ({
                                day: day.day,
                                desc: Array.isArray(day.desc) ? day.desc : (day.desc ? day.desc.split(
                                    '\n') : []),
                                budget: day.budget || this.budget
                            }));
                        }

                        if (typeof parsed.plan === 'string') {
                            return this.fallbackParse(parsed.plan);
                        }

                        return [];
                    } catch (e) {
                        console.error('Error parsing itinerary:', e);
                        return [];
                    }
                },

                fallbackParse(planString) {
                    // Fallback parser for malformed JSON
                    const days = [];
                    try {
                        // Try to extract day objects using regex
                        const dayPattern =
                            /"day":\s*(\d+)[\s\S]*?"desc":\s*\[([\s\S]*?)\][\s\S]*?"budget":\s*"([^"]+)"/g;
                        let match;

                        while ((match = dayPattern.exec(planString)) !== null) {
                            const dayNum = match[1];
                            const descString = match[2];
                            const budget = match[3];

                            // Extract activities from desc array
                            const activityPattern = /"([^"]+)"/g;
                            const activities = [];
                            let actMatch;

                            while ((actMatch = activityPattern.exec(descString)) !== null) {
                                activities.push(actMatch[1]);
                            }

                            if (activities.length > 0) {
                                days.push({
                                    day: dayNum,
                                    desc: activities,
                                    budget: budget || this.budget
                                });
                            }
                        }
                    } catch (e) {
                        console.error('Fallback parse error:', e);
                    }

                    return days;
                },

                formatBudget(budget) {
                    if (!budget) return 'Budget varies';

                    // If already in correct format (contains "Rs" or starts with "Approx"), return as-is
                    if (budget.includes('Rs') || budget.startsWith('Approx')) {
                        return budget;
                    }

                    // If it's a tier name (Standard, Luxury, etc.), show as budget range
                    const tierRanges = {
                        'Affordable': 'Approx. Rs. 2,000-3,500',
                        'Standard': 'Approx. Rs. 3,500-6,000',
                        'Premium': 'Approx. Rs. 6,000-12,000'
                    };

                    return tierRanges[budget] || `Approx. Rs. varies (${budget})`;
                },

                selectTab(tabId) {
                    this.currentTab = tabId;
                    if (tabId === 'cars') this.fetchCars();
                    if (tabId === 'hotels' && this.destination && this.hotels.length === 0) {
                        this.fetchHotels();
                    }
                },

                async fetchPlan() {
                    if (!this.destination) return alert('Please enter a destination');

                    this.loading = true;
                    try {
                        const res = await fetch("{{ route('user.travel.plan') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                destination: this.destination,
                                days: this.days,
                                budget: this.budget
                            })
                        });
                        if (!res.ok) throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                        this.itinerary = await res.json();

                        console.log('Itinerary received:', this.itinerary);

                        await this.pinDestinationByName(this.destination);

                        await this.loadSavedItineraries();

                        // Automatically fetch hotels when plan is created
                        await this.fetchHotels();

                    } catch (err) {
                        console.error(err);
                        alert('Error: ' + err.message);
                    } finally {
                        this.loading = false;
                    }
                },

                async loadSavedItineraries() {
                    try {
                        const res = await fetch("{{ route('user.travel.itineraries') }}", {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (!res.ok) {
                            return;
                        }

                        const payload = await res.json();
                        this.savedItineraries = Array.isArray(payload?.data) ? payload.data : [];
                    } catch (error) {
                        console.error('Failed to load saved itineraries:', error);
                    }
                },

                async deleteSavedItinerary(itineraryId) {
                    if (!confirm('Delete this itinerary?')) {
                        return;
                    }

                    try {
                        const res = await fetch(`{{ url('/travel/itineraries') }}/${itineraryId}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (!res.ok) {
                            throw new Error(`HTTP ${res.status}`);
                        }

                        this.savedItineraries = this.savedItineraries.filter(item => item.id !== itineraryId);
                    } catch (error) {
                        console.error('Failed to delete itinerary:', error);
                        alert('Could not delete itinerary. Please try again.');
                    }
                },

                async fetchHotels() {
                    if (!this.destination) return;
                    this.loading = true;
                    try {
                        const res = await fetch("{{ route('user.travel.hotels') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                destination: this.destination
                            })
                        });
                        if (!res.ok) throw new Error(`HTTP ${res.status}`);
                        const data = await res.json();
                        console.log('Hotels received:', data);
                        this.hotels = Array.isArray(data) ? data : (data.hotels || []);
                    } catch (err) {
                        console.error('Hotel fetch error:', err);
                    } finally {
                        this.loading = false;
                    }
                },

                async fetchCars() {
                    this.loading = true;
                    try {
                        const res = await fetch("{{ route('user.travel.cars') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        if (!res.ok) throw new Error(`HTTP ${res.status}`);
                        const data = await res.json();
                        this.cars = Array.isArray(data) ? data : [];
                    } catch (err) {
                        console.error(err);
                        alert('Failed to load cars');
                    } finally {
                        this.loading = false;
                    }
                },

                getTimeOfDay(activity) {
                    const lower = activity.toLowerCase();
                    if (lower.includes('morning')) return 'Morning';
                    if (lower.includes('afternoon')) return 'Afternoon';
                    if (lower.includes('evening')) return 'Evening';
                    if (lower.includes('night')) return 'Night';
                    return 'Activity';
                },

                getTimeIcon(activity) {
                    const lower = activity.toLowerCase();
                    if (lower.includes('morning')) return '🌅';
                    if (lower.includes('afternoon')) return '☀️';
                    if (lower.includes('evening')) return '🌆';
                    if (lower.includes('night')) return '🌙';
                    return '📍';
                },

                groupActivitiesByTime(activities) {
                    const groups = {
                        morning: [],
                        afternoon: [],
                        evening: [],
                        night: []
                    };

                    activities.forEach(activity => {
                        const lower = activity.toLowerCase();
                        if (lower.includes('morning')) {
                            groups.morning.push(activity);
                        } else if (lower.includes('afternoon')) {
                            groups.afternoon.push(activity);
                        } else if (lower.includes('evening')) {
                            groups.evening.push(activity);
                        } else if (lower.includes('night')) {
                            groups.night.push(activity);
                        }
                    });

                    return groups;
                },

                rentCar(car) {
                    // Save selected car in sessionStorage
                    sessionStorage.setItem('selectedCar', JSON.stringify(car));

                    // Redirect to booking page
                    window.location.href = "{{ route('user.travel.carBooking') }}";
                },

                bookHotel(hotel) {
                    // Save selected hotel in sessionStorage
                    sessionStorage.setItem('selectedHotel', JSON.stringify(hotel));

                    // Redirect to booking page
                    window.location.href = "{{ route('user.travel.hotelBooking') }}";
                }
            }
        }
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slideUp {
            animation: slideUp 0.6s ease-out;
        }

        .animate-scaleIn {
            animation: scaleIn 0.3s ease-out;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection
