@extends('layouts.app')

@section('content')
<div x-data="healthApp()" x-init="loadTravelData()" class="space-y-6 md:space-y-10 pb-10 md:pb-20 animate-fadeIn">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4 md:mb-6">
        <h2 class="text-2xl sm:text-3xl font-bold italic text-stone-900 tracking-tighter uppercase">
            Nexo<span class="text-amber-700">.Health</span>
        </h2>
    </div>

    <!-- Travel Info Card (Only show if destination exists) -->
    <div x-show="destination" class="bg-stone-900 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3.5rem] p-4 sm:p-6 md:p-8 lg:p-12 text-stone-50 shadow-lg sm:shadow-xl md:shadow-2xl relative overflow-hidden group animate-slideDown">
        <div class="relative z-10">
            <span class="bg-amber-50/20 border border-amber-100/20 px-3 md:px-4 py-1.5 md:py-2 rounded-full text-[9px] md:text-[10px] font-bold italic uppercase tracking-wider md:tracking-widest text-amber-300">Travel Health Tips</span>
            <h3 class="text-2xl sm:text-3xl font-bold italic mt-4 md:mt-6 uppercase tracking-tighter">Trip to: <span x-text="destination"></span></h3>

            <!-- Loading State -->
            <div x-show="loadingTips" class="mt-8 md:mt-12 space-y-3 md:space-y-4">
                <div class="h-20 bg-white/10 rounded-2xl animate-pulse"></div>
                <div class="h-20 bg-white/10 rounded-2xl animate-pulse"></div>
                <div class="h-20 bg-white/10 rounded-2xl animate-pulse"></div>
            </div>

            <!-- Tips Grid -->
            <div x-show="!loadingTips" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-8 mt-8 md:mt-12">
                <template x-for="(tip, idx) in travelTips" :key="idx">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-3xl flex items-center gap-5">
                        <span class="text-3xl" x-text="tip.icon || '✈️'"></span>
                        <p class="text-sm font-medium leading-relaxed italic" x-text="tip.tip || tip.category"></p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-12">
        <!-- Symptom Checker Section -->
        <div class="lg:col-span-2 space-y-6 md:space-y-10">
            <div class="bg-white border-2 md:border-3 border-amber-700 p-0 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3.5rem] shadow-md sm:shadow-lg md:shadow-xl overflow-hidden">
                <!-- Dark heading box -->
                <div class="bg-stone-900 p-5 md:p-8 border-b-2 md:border-b-3 border-amber-700">
                    <h3 class="text-base md:text-xl font-bold italic text-white flex items-center gap-3 md:gap-5 uppercase tracking-tighter">
                        <i class="fa-solid fa-microscope text-amber-300"></i> Symptom Checker
                    </h3>
                </div>

                <!-- Content area -->
                <div class="p-6 sm:p-8 md:p-12">
                    <textarea
                        x-model="symptoms"
                        placeholder="Describe how you are feeling...(Please give as much precise detail as possible, including duration, severity, and any other relevant information.)"
                        class="w-full h-32 sm:h-40 md:h-48 lg:h-56 bg-amber-50 border border-amber-200 text-stone-900 rounded-lg sm:rounded-xl md:rounded-2xl lg:rounded-[2.5rem] p-4 sm:p-6 md:p-8 lg:p-10 focus:ring-2 focus:ring-amber-700 font-medium text-sm sm:text-base md:text-lg resize-none placeholder:text-black/20"
                    ></textarea>
                <button
                    type="button"
                    @click="checkSymptoms()"
                    :disabled="loading"
                    class="mt-6 sm:mt-8 md:mt-10 w-full h-14 sm:h-16 md:h-[72px] bg-stone-900 hover:bg-amber-700 disabled:bg-stone-400 text-white rounded-xl sm:rounded-2xl font-bold italic uppercase tracking-widest shadow-lg sm:shadow-xl transition-all flex items-center justify-center gap-3 sm:gap-4 cursor-pointer">
                    <span x-show="!loading">CHECK SYMPTOMS</span>
                    <span x-show="loading"><i class="fa-solid fa-spinner fa-spin"></i></span>
                    </button>
                </div>
            </div>

            <!-- Results -->
            <div x-show="result" class="bg-gradient-to-br from-white to-amber-50/30 border-2 md:border-3 border-amber-700 p-6 sm:p-8 md:p-12 rounded-2xl sm:rounded-3xl md:rounded-[3.5rem] shadow-xl md:shadow-2xl shadow-amber-700/20 animate-slideUp">
                <div class="flex flex-col md:flex-row justify-between items-start gap-4 sm:gap-6 md:gap-8 mb-6 sm:mb-8 md:mb-12 pb-4 sm:pb-6 md:pb-8 border-b-2 border-amber-200">
                    <div>
                        <h4 class="text-2xl sm:text-3xl md:text-4xl font-bold italic text-stone-900 tracking-tighter" x-text="result?.diagnosis"></h4>
                        <p class="text-amber-700 font-bold italic uppercase tracking-[0.15em] sm:tracking-[0.2em] md:tracking-[0.25em] text-[10px] md:text-[11px] mt-2 md:mt-3">Urgency: <span class="text-amber-900 font-black" x-text="result?.urgency"></span></p>
                    </div>
                    <div class="bg-gradient-to-br from-amber-700 to-amber-600 px-4 sm:px-6 md:px-8 py-3 sm:py-4 md:py-5 rounded-xl md:rounded-2xl shadow-lg shadow-amber-700/30">
                        <p class="text-[9px] md:text-[10px] font-bold italic text-amber-50 uppercase tracking-widest mb-1 md:mb-2">Specialist</p>
                        <p class="font-bold italic text-white text-xs sm:text-sm" x-text="result?.specialist"></p>
                    </div>
                </div>

                <div class="space-y-10">
                    <div>
                        <label class="text-[11px] font-bold italic text-amber-900 uppercase tracking-widest mb-6 block">Recommended Hospitals</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <template x-for="(hospital, idx) in (result?.hospitals || [])" :key="idx">
                                <div class="bg-gradient-to-br from-amber-50 to-white p-6 rounded-2xl border-2 border-amber-300 flex items-center gap-4 hover:border-amber-700 hover:shadow-lg hover:shadow-amber-700/20 transition-all duration-300 group cursor-pointer">
                                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-amber-700 to-amber-600 text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                        <i class="fa-solid fa-building-h text-base"></i>
                                    </div>
                                    <span class="font-bold italic text-stone-900 text-sm group-hover:text-amber-700 transition-colors" x-text="hospital"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doctors Sidebar -->
        <div class="bg-gradient-to-br from-amber-50 to-white border-2 border-amber-300 rounded-2xl sm:rounded-3xl md:rounded-[3.5rem] p-6 sm:p-8 md:p-12 h-fit space-y-6 md:space-y-8 shadow-xl shadow-amber-700/10">
            <div class="pb-4 md:pb-6 border-b-2 border-amber-300">
                <h4 class="font-bold italic text-xl sm:text-2xl text-stone-900 uppercase tracking-tighter">Recommended Doctors</h4>
            </div>

            <!-- Loading State -->
            <div x-show="result && loadingDoctors" class="space-y-4">
                <div class="h-24 bg-stone-200 rounded-2xl animate-pulse"></div>
                <div class="h-24 bg-stone-200 rounded-2xl animate-pulse"></div>
                <div class="h-24 bg-stone-200 rounded-2xl animate-pulse"></div>
            </div>

            <!-- Doctors List -->
            <div x-show="result && !loadingDoctors" class="space-y-4 md:space-y-5">
                <template x-for="(doctor, idx) in doctors" :key="idx">
                    <div class="p-4 sm:p-5 md:p-6 rounded-xl md:rounded-2xl bg-white border-2 border-amber-200 hover:border-amber-700 hover:shadow-lg hover:shadow-amber-700/20 transition-all duration-300 shadow-sm group">
                        <p class="font-bold italic text-stone-900 text-sm sm:text-base group-hover:text-amber-700 group-hover:translate-x-1 transition-all duration-300" x-text="doctor.name || 'Doctor Name'"></p>
                        <p class="text-[9px] md:text-[10px] font-bold italic text-amber-700 uppercase mt-2 tracking-widest opacity-90" x-text="(doctor.specialty || 'Specialty') + ' • ' + (doctor.hospital || 'Hospital')"></p>
                        <div class="mt-3 md:mt-4 pt-2 md:pt-3 border-t border-amber-200">
                            <p class="text-[9px] md:text-[10px] font-semibold italic text-amber-600" x-text="'Exp: ' + (doctor.experience || 'N/A') + ' | ' + (doctor.availability || 'Available')"></p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Placeholder when no diagnosis -->
            <div x-show="!result" class="space-y-4">
                <div class="p-6 rounded-2xl bg-white border-2 border-amber-200 shadow-sm hover:border-amber-300 transition-colors">
                    <p class="font-bold italic text-amber-700 text-sm">Check symptoms to get doctor recommendations</p>
                </div>
            </div>

            {{-- <button type="button" class="w-full bg-stone-900 text-white py-5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-purple-600 transition-all shadow-xl font-serif cursor-pointer">
                ONLINE CONSULTATION
            </button> --}}
        </div>
    </div>
</div>

<script>
function healthApp() {
    return {
        symptoms: '',
        result: null,
        loading: false,
        loadingTips: false,
        loadingDoctors: false,
        destination: '',
        travelTips: [],
        doctors: [],

        loadTravelData() {
            // Get destination from sessionStorage (set by travel module)
            console.log('Loading travel data from sessionStorage...');
            const travelData = sessionStorage.getItem('travel_destination');
            console.log('Travel destination:', travelData);
            if (travelData) {
                this.destination = travelData;
                this.fetchTravelTips();
            }
        },

        async fetchTravelTips() {
            if (!this.destination) return;

            this.loadingTips = true;
            try {
                const res = await fetch("{{ route('user.health.travel-tips') }}?destination=" + encodeURIComponent(this.destination), {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                console.log('Travel tips response:', res.status);
                if (res.ok) {
                    this.travelTips = await res.json();
                    console.log('Travel tips:', this.travelTips);
                }
            } catch (error) {
                console.error('Error fetching travel tips:', error);
            } finally {
                this.loadingTips = false;
            }
        },

        async fetchDoctors() {
            if (!this.result || !this.result.diagnosis) return;

            this.loadingDoctors = true;
            try {
                const res = await fetch("{{ route('user.health.doctors') }}?diagnosis=" + encodeURIComponent(this.result.diagnosis) + "&urgency=" + encodeURIComponent(this.result.urgency || 'Medium'), {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                console.log('Doctors response:', res.status);
                if (res.ok) {
                    this.doctors = await res.json();
                    console.log('Doctors:', this.doctors);
                }
            } catch (error) {
                console.error('Error fetching doctors:', error);
            } finally {
                this.loadingDoctors = false;
            }
        },

        async checkSymptoms() {
            console.log('Check symptoms called with:', this.symptoms);
            if (!this.symptoms.trim()) {
                alert('Please describe your symptoms');
                return;
            }

            this.loading = true;
            try {
                const res = await fetch("{{ route('user.health.check') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        symptoms: this.symptoms
                    })
                });

                console.log('Response status:', res.status);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                this.result = await res.json();
                console.log('Result received:', this.result);

                // Fetch doctors based on diagnosis
                await this.fetchDoctors();
            } catch (error) {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-in;
    }

    .animate-slideUp {
        animation: slideUp 0.5s ease-out;
    }

    .animate-slideDown {
        animation: slideDown 0.3s ease-out;
    }
</style>

@endsection
