@extends('layouts.app')

@section('content')
<div x-data="healthApp()" x-init="loadTravelData()" class="space-y-6 md:space-y-10 pb-10 md:pb-20 animate-fadeIn">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4 md:mb-6">
        <h2 class="text-2xl sm:text-3xl font-bold italic text-stone-900 tracking-tighter uppercase">
            Nexo<span class="text-amber-700">.Health</span>
        </h2>
    </div>

    <!-- Medical Records Quick Access -->
    <div class="bg-gradient-to-br from-white to-amber-50/30 border-2 md:border-3 border-amber-300 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3.5rem] p-5 sm:p-6 md:p-8 shadow-md sm:shadow-lg animate-slideDown">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <p class="text-[10px] md:text-xs font-bold italic text-amber-900 uppercase tracking-widest">Health Vault</p>
                <h3 class="text-xl sm:text-2xl md:text-3xl font-bold italic text-stone-900 tracking-tighter mt-2">Medical Records</h3>
                <p class="text-sm md:text-base text-stone-700 font-medium italic mt-2">Securely upload and manage your prescriptions.</p>
                <p class="text-xs md:text-sm text-amber-700 font-bold italic mt-3 uppercase tracking-wider">
                    Total Records: {{ $medicalRecordsCount ?? 0 }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <a href="{{ route('user.medical-records.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 border-2 border-stone-300 text-stone-900 font-bold italic rounded-xl hover:bg-stone-50 transition-all">
                    <i class="fa-solid fa-eye"></i>
                    View Records
                </a>
                <a href="{{ route('user.medical-records.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-stone-900 hover:bg-amber-700 text-white font-bold italic rounded-xl transition-all">
                    <i class="fa-solid fa-plus"></i>
                    Add Record
                </a>
            </div>
        </div>
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
                    <div class="flex-1">
                        <p class="text-sm sm:text-base md:text-lg font-bold italic text-amber-700 uppercase tracking-widest mb-2">Suspected Condition</p>
                        <h4 class="text-2xl sm:text-3xl md:text-4xl font-bold italic text-stone-900 tracking-tighter" x-text="result?.disease_name || 'Condition'"></h4>
                        <p class="text-sm sm:text-base font-medium italic text-stone-700 mt-3" x-text="result?.description"></p>
                        <p class="text-xs sm:text-sm font-semibold italic text-stone-600 mt-4 leading-relaxed border-l-4 border-amber-400 pl-4" x-text="result?.diagnosis"></p>
                    </div>
                    <div class="bg-gradient-to-br from-amber-700 to-amber-600 px-4 sm:px-6 md:px-8 py-3 sm:py-4 md:py-5 rounded-xl md:rounded-2xl shadow-lg shadow-amber-700/30 flex-shrink-0">
                        <div class="mb-4 pb-2 md:pb-3 border-b border-amber-500/50">
                            <p class="text-[9px] md:text-[10px] font-bold italic text-amber-50 uppercase tracking-widest">Urgency Level</p>
                            <p class="font-black italic text-white text-lg sm:text-xl mt-1" x-text="result?.urgency"></p>
                        </div>
                        <p class="text-[9px] md:text-[10px] font-bold italic text-amber-50 uppercase tracking-widest mb-1 md:mb-2">Specialist Needed</p>
                        <p class="font-bold italic text-white text-xs sm:text-sm" x-text="result?.specialist"></p>
                    </div>
                </div>

                <div class="space-y-10">
                    <div>
                        <label class="text-[11px] font-bold italic text-amber-900 uppercase tracking-widest mb-6 block flex items-center gap-2">
                            <i class="fa-solid fa-hospital-user text-amber-700"></i>
                            Select Hospital & Schedule Appointment
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                            <template x-for="(hospital, idx) in (result?.hospitals || [])" :key="idx">
                                <button
                                    type="button"
                                    @click="selectHospital(hospital, idx)"
                                    :class="{
                                        'border-amber-700 bg-amber-50 shadow-lg shadow-amber-700/20': selectedHospitalIdx === idx,
                                        'border-amber-300 hover:border-amber-700': selectedHospitalIdx !== idx
                                    }"
                                    class="text-left p-5 md:p-6 rounded-2xl border-2 transition-all duration-300 bg-white hover:shadow-lg group">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-bold italic text-stone-900 text-sm md:text-base group-hover:text-amber-700 transition-colors" x-text="hospital.name"></p>
                                            <div class="mt-3 space-y-2">
                                                <p class="text-xs font-semibold text-stone-600 flex items-center gap-2">
                                                    <i class="fa-solid fa-map-pin text-amber-600"></i>
                                                    <span x-text="hospital.city"></span>
                                                </p>
                                                <p class="text-xs font-semibold text-stone-600 flex items-center gap-2">
                                                    <i class="fa-solid fa-phone text-amber-600"></i>
                                                    <span x-text="hospital.phone"></span>
                                                </p>
                                            </div>
                                        </div>
                                        <div :class="{'bg-amber-700': selectedHospitalIdx === idx, 'bg-amber-200': selectedHospitalIdx !== idx}" class="h-6 w-6 rounded-full flex items-center justify-center flex-shrink-0 transition-all">
                                            <i :class="{'fa-check': selectedHospitalIdx === idx}" class="fa-solid text-white text-sm"></i>
                                        </div>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Schedule Appointment Button -->
                    <button
                        x-show="selectedHospitalIdx !== null"
                        @click="openSchedulingModal()"
                        class="w-full bg-gradient-to-r from-amber-700 to-amber-600 hover:from-amber-800 hover:to-amber-700 text-white font-bold italic uppercase tracking-widest py-4 md:py-5 rounded-2xl md:rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 gap-3 flex items-center justify-center text-sm md:text-base">
                        <i class="fa-solid fa-calendar-check"></i>
                        Schedule Appointment
                    </button>
                </div>
            </div>
        </div>

        <!-- Doctors Sidebar -->
        <div class="bg-white border-2 border-amber-300 rounded-2xl sm:rounded-3xl md:rounded-[3.5rem] p-6 sm:p-8 md:p-12 h-fit space-y-6 md:space-y-8 shadow-xl shadow-amber-700/10">
            <div class="pb-4 md:pb-6 border-b-2 border-amber-300">
                <h4 class="font-bold italic text-xl sm:text-2xl text-stone-900 uppercase tracking-tighter flex items-center gap-3">
                    <i class="fa-solid fa-user-doctor text-amber-700"></i>
                    Recommended Doctors
                </h4>
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
                    <button
                        type="button"
                        @click="selectedDoctor = doctor"
                        :class="{'border-amber-700 bg-amber-50': selectedDoctor?.name === doctor.name, 'border-amber-200': selectedDoctor?.name !== doctor.name}"
                        class="w-full text-left p-4 sm:p-5 md:p-6 rounded-xl md:rounded-2xl bg-white border-2 hover:border-amber-700 hover:shadow-lg hover:shadow-amber-700/20 transition-all duration-300 shadow-sm group cursor-pointer">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-bold italic text-stone-900 text-sm sm:text-base group-hover:text-amber-700 transition-all" x-text="doctor.name"></p>
                                <p class="text-[9px] md:text-[10px] font-bold italic text-amber-700 uppercase mt-2 tracking-widest opacity-90" x-text="(doctor.specialty || 'Specialty') + ' • ' + (doctor.hospital || 'Hospital')"></p>
                                <div class="mt-3 md:mt-4 pt-2 md:pt-3 border-t border-amber-200">
                                    <p class="text-[9px] md:text-[10px] font-semibold italic text-amber-600" x-text="'Exp: ' + (doctor.experience || 'N/A') + ' | ' + (doctor.availability || 'Available')"></p>
                                </div>
                            </div>
                            <div x-show="selectedDoctor?.name === doctor.name" class="h-5 w-5 rounded-full bg-amber-700 flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fa-solid fa-check text-white text-xs"></i>
                            </div>
                        </div>
                    </button>
                </template>
            </div>

            <!-- Placeholder when no diagnosis -->
            <div x-show="!result" class="space-y-4">
                <div class="p-6 rounded-2xl bg-amber-50 border-2 border-amber-200 shadow-sm">
                    <p class="font-bold italic text-amber-700 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-info"></i>
                        Check symptoms to get doctor recommendations
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scheduling Modal -->
    <div x-show="showSchedulingModal" x-transition class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div x-show="showSchedulingModal" x-transition class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-amber-700 to-amber-600 p-6 md:p-8 sticky top-0 z-10 flex items-center justify-between">
                <div>
                    <h3 class="text-xl md:text-2xl font-bold italic text-white uppercase tracking-tighter">Schedule Appointment</h3>
                    <p class="text-amber-100 text-sm mt-1">Book your medical consultation</p>
                </div>
                <button @click="closeSchedulingModal()" class="text-white hover:bg-amber-800 p-2 rounded-full transition-all">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6 md:p-8 space-y-6">
                <!-- Disease Info -->
                <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-6">
                    <p class="text-xs font-bold text-amber-900 uppercase tracking-widest mb-2">Condition</p>
                    <p class="text-lg md:text-2xl font-bold italic text-stone-900" x-text="result?.disease_name"></p>
                </div>

                <!-- Selected Doctor & Hospital -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-stone-50 border-2 border-stone-300 rounded-2xl p-5">
                        <p class="text-xs font-bold text-stone-700 uppercase tracking-widest mb-3">Selected Doctor</p>
                        <p class="text-sm font-bold italic text-stone-900" x-text="selectedDoctor?.name || 'No doctor selected'"></p>
                        <p class="text-xs text-stone-600 mt-2" x-text="selectedDoctor?.specialty || ''"></p>
                    </div>
                    <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-5">
                        <p class="text-xs font-bold text-amber-900 uppercase tracking-widest mb-3">Selected Hospital</p>
                        <p class="text-sm font-bold italic text-stone-900" x-text="selectedHospital?.name || 'No hospital selected'"></p>
                        <p class="text-xs text-stone-600 mt-2" x-text="selectedHospital?.city || ''"></p>
                    </div>
                </div>

                <!-- Appointment Date & Time -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-widest mb-3">
                            <i class="fa-solid fa-calendar"></i> Appointment Date
                        </label>
                        <input
                            type="date"
                            x-model="appointmentDate"
                            :min="today()"
                            class="w-full px-4 py-3 border-2 border-stone-300 rounded-xl focus:border-amber-700 focus:ring-2 focus:ring-amber-300 font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-widest mb-3">
                            <i class="fa-solid fa-clock"></i> Appointment Time
                        </label>
                        <input
                            type="time"
                            x-model="appointmentTime"
                            class="w-full px-4 py-3 border-2 border-stone-300 rounded-xl focus:border-amber-700 focus:ring-2 focus:ring-amber-300 font-medium">
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-widest mb-3">
                        <i class="fa-solid fa-note-sticky"></i> Additional Notes (Optional)
                    </label>
                    <textarea
                        x-model="appointmentNotes"
                        placeholder="Any additional symptoms, medications, or medical history..."
                        class="w-full px-4 py-3 h-24 border-2 border-stone-300 rounded-xl focus:border-amber-700 focus:ring-2 focus:ring-amber-300 font-medium resize-none"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4">
                    <button
                        @click="closeSchedulingModal()"
                        class="flex-1 px-6 py-3 border-2 border-stone-300 text-stone-900 font-bold italic rounded-xl hover:bg-stone-50 transition-all">
                        Cancel
                    </button>
                    <button
                        @click="scheduleAppointment()"
                        :disabled="!appointmentDate || !appointmentTime || !selectedDoctor"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-amber-700 to-amber-600 text-white font-bold italic rounded-xl hover:from-amber-800 hover:to-amber-700 disabled:from-stone-400 disabled:to-stone-400 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i>
                        Confirm & Save to Calendar
                    </button>
                </div>
            </div>
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
        selectedHospital: null,
        selectedHospitalIdx: null,
        selectedDoctor: null,
        showSchedulingModal: false,
        appointmentDate: '',
        appointmentTime: '',
        appointmentNotes: '',

        today() {
            const today = new Date();
            return today.toISOString().split('T')[0];
        },

        selectHospital(hospital, idx) {
            this.selectedHospital = hospital;
            this.selectedHospitalIdx = idx;
        },

        openSchedulingModal() {
            if (!this.selectedHospital || !this.selectedDoctor) {
                alert('Please select both a hospital and a doctor');
                return;
            }
            this.showSchedulingModal = true;
        },

        closeSchedulingModal() {
            this.showSchedulingModal = false;
            this.appointmentDate = '';
            this.appointmentTime = '';
            this.appointmentNotes = '';
        },

        async scheduleAppointment() {
            if (!this.appointmentDate || !this.appointmentTime || !this.selectedDoctor) {
                alert('Please fill in all required fields');
                return;
            }

            try {
                const eventData = {
                    title: `Medical Appointment - ${this.result.disease_name}`,
                    description: `Doctor: ${this.selectedDoctor.name}\nSpecialty: ${this.selectedDoctor.specialty}\nHospital: ${this.selectedHospital.name}\nCity: ${this.selectedHospital.city}\nPhone: ${this.selectedHospital.phone}\nNotes: ${this.appointmentNotes}`,
                    location: `${this.selectedHospital.name}, ${this.selectedHospital.city}`,
                    event_date: this.appointmentDate,
                    start_time: this.appointmentTime,
                    end_time: this.calculateEndTime(this.appointmentTime),
                    event_type: 'manual',
                    category: 'health',
                    priority: (this.result.urgency || 'Medium').toLowerCase()
                };

                const response = await fetch('/api/events', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(eventData)
                });

                if (!response.ok) throw new Error('Failed to save appointment');

                this.closeSchedulingModal();
                alert('✓ Appointment scheduled successfully! Check your calendar for details.');

                // Clear selections after successful booking
                this.selectedHospital = null;
                this.selectedHospitalIdx = null;
                this.selectedDoctor = null;
            } catch (error) {
                console.error('Error scheduling appointment:', error);
                alert('Error: ' + error.message);
            }
        },

        calculateEndTime(startTime) {
            if (!startTime) return '';
            const [hours, minutes] = startTime.split(':');
            let endHours = parseInt(hours) + 1;
            if (endHours >= 24) endHours -= 24;
            return `${String(endHours).padStart(2, '0')}:${minutes}`;
        },

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

                // Reset selections for new diagnosis
                this.selectedHospital = null;
                this.selectedHospitalIdx = null;
                this.selectedDoctor = null;

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
