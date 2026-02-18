@extends('layouts.app')

@section('title', 'NEXO Calender')

@section('content')
    <div x-data="calendarApp()" @init="await init()" class="space-y-8 md:space-y-12 pb-10 md:pb-20">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-6">
            <div class="space-y-1.5 md:space-y-2">
                <h2 class="text-3xl sm:text-4xl font-bold text-amber-900 font-serif tracking-tighter uppercase italic">
                    NEXO<span class="text-amber-600">.CALENDER</span>
                </h2>
                <p class="text-xs md:text-sm text-amber-700 font-medium font-serif">Nepali Calendar System - BS <span x-text="currentYear"></span></p>
            </div>

            <!-- Today's Quick Info -->
            <div class="bg-amber-900 text-white px-6 md:px-8 py-3 md:py-4 rounded-xl md:rounded-2xl shadow-lg md:shadow-xl">
                <p class="text-[9px] font-black uppercase tracking-widest opacity-70 mb-2 font-serif">Today</p>
                <p x-text="todayDateText" class="text-sm font-bold font-serif"></p>
            </div>
        </div>

        <!-- Stats Dashboard -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 sm:gap-4">
            <div class="bg-gradient-to-br from-amber-50 to-white border-2 border-amber-200 rounded-lg sm:rounded-xl p-3 sm:p-4 text-center hover:shadow-md sm:hover:shadow-lg transition-all">
                <p class="text-xl sm:text-2xl font-bold text-amber-900" x-text="stats.total || 0"></p>
                <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-amber-600 mt-1">Total Events</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-white border-2 border-yellow-300 rounded-xl p-4 text-center hover:shadow-lg transition-all">
                <p class="text-2xl font-bold text-yellow-900" x-text="stats.upcoming || 0"></p>
                <p class="text-[9px] font-black uppercase tracking-widest text-yellow-700 mt-1">Upcoming</p>
            </div>
            <div class="bg-gradient-to-br from-amber-100/50 to-white border-2 border-amber-300 rounded-xl p-4 text-center hover:shadow-lg transition-all">
                <p class="text-2xl font-bold text-amber-800" x-text="stats.past || 0"></p>
                <p class="text-[9px] font-black uppercase tracking-widest text-amber-700 mt-1">Past</p>
            </div>
            <div class="bg-gradient-to-br from-orange-50 to-white border-2 border-orange-300 rounded-xl p-4 text-center hover:shadow-lg transition-all">
                <p class="text-2xl font-bold text-orange-900" x-text="(stats.by_priority && stats.by_priority.high) || 0"></p>
                <p class="text-[9px] font-black uppercase tracking-widest text-orange-700 mt-1">High Priority</p>
            </div>
            <div class="bg-gradient-to-br from-amber-200/40 to-white border-2 border-amber-400 rounded-xl p-4 text-center hover:shadow-lg transition-all">
                <p class="text-2xl font-bold text-amber-900" x-text="Object.keys(stats.by_category || {}).length"></p>
                <p class="text-[9px] font-black uppercase tracking-widest text-amber-700 mt-1">Categories</p>
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-amber-50/50 border border-amber-200 rounded-2xl p-4 md:p-6">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" x-model="searchQuery" @input="searchEvents()"
                           placeholder="🔍 Search events..."
                           class="w-full px-4 py-2.5 border-2 border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-600 focus:border-transparent font-serif">
                </div>
                <select x-model="filterCategory" @change="filterEvents()"
                        class="px-4 py-2.5 border-2 border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-600 font-semibold font-serif">
                    <option value="">All Categories</option>
                    <option value="work">Work</option>
                    <option value="personal">Personal</option>
                    <option value="health">Health</option>
                    <option value="travel">Travel</option>
                    <option value="education">Education</option>
                </select>
                <select x-model="filterPriority" @change="filterEvents()"
                        class="px-4 py-2.5 border-2 border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-600 font-semibold font-serif">
                    <option value="">All Priorities</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
                <button @click="viewMode = (viewMode === 'month' ? 'list' : 'month')"
                        class="px-6 py-2.5 bg-amber-900 hover:bg-amber-700 text-white rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-md">
                    <span x-text="viewMode === 'month' ? '📋 List' : '📅 Month'"></span>
                </button>
            </div>
        </div>

        <!-- Main Calendar Card -->
        <div x-show="viewMode === 'month'" class="bg-white border border-black/5 rounded-2xl md:rounded-[3rem] shadow-xl md:shadow-2xl overflow-hidden">
            <!-- Calendar Header -->
            <div class="bg-gradient-to-r from-amber-600 to-amber-500 px-6 md:px-10 py-6 md:py-8">
                <div class="flex items-center justify-between">
                    <button @click="prevMonth()"
                            class="h-10 w-10 md:h-12 md:w-12 rounded-lg md:rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white flex items-center justify-center transition-all hover:scale-110 shadow-lg">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <div class="text-center">
                        <h3 class="text-2xl sm:text-3xl font-bold text-white font-serif italic" x-text="currentMonthName"></h3>
                        <p class="text-white/80 text-xs md:text-sm font-semibold mt-1 font-serif" x-text="'BS ' + currentYear"></p>
                    </div>

                    <button @click="nextMonth()"
                            class="h-10 w-10 md:h-12 md:w-12 rounded-lg md:rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white flex items-center justify-center transition-all hover:scale-110 shadow-lg">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="p-4 sm:p-6 md:p-8 lg:p-12">
                <!-- Days of Week -->
                <div class="grid grid-cols-7 gap-2 md:gap-4 mb-4 md:mb-6">
                    <template x-for="day in daysOfWeek" :key="day">
                        <div class="text-center text-[10px] font-black text-amber-600 uppercase tracking-widest font-serif" x-text="day"></div>
                    </template>
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-2 md:gap-4">
                    <template x-for="(cell, index) in getCalendarGrid()" :key="index">
                        <div class="aspect-square">
                            <div x-show="cell.day"
                                 @click="cell.day && openEventModal(cell.day)"
                                 :class="[
                                     isToday(cell.day)
                                         ? 'bg-amber-600 text-white shadow-xl ring-4 ring-amber-200 font-bold'
                                         : hasEvents(cell.day)
                                             ? 'bg-amber-50 hover:bg-amber-100 text-amber-900 ring-2 ring-amber-300 font-semibold'
                                             : 'bg-white hover:bg-amber-50 text-gray-700 border border-gray-200'
                                 ]"
                                 class="h-full rounded-lg flex flex-col items-center justify-center transition-all cursor-pointer hover:scale-105 hover:shadow-lg group relative">
                                <span class="text-2xl font-bold font-serif" x-text="cell.day?.date"></span>
                                <span class="text-[8px] font-black uppercase tracking-widest mt-1 font-serif"
                                      :class="isToday(cell.day) ? 'text-white/90' : hasEvents(cell.day) ? 'text-amber-600' : 'text-gray-500'"
                                      x-text="cell.day?.day"></span>
                                <!-- Today Badge -->
                                <div x-show="isToday(cell.day)" class="absolute -top-2 -right-2 bg-white text-amber-600 text-[7px] font-black px-2 py-0.5 rounded-full shadow-lg uppercase tracking-wider">
                                    Today
                                </div>
                                <!-- Event Indicators (Google Calendar Style) -->
                                <div x-show="hasEvents(cell.day)" class="absolute bottom-2 left-2 right-2">
                                    <template x-for="(event, idx) in getDayEvents(cell.day).slice(0, 3)" :key="event.id">
                                        <div class="text-[8px] font-bold px-1.5 py-0.5 rounded mb-0.5 truncate"
                                             :class="getEventColorClass(event)"
                                             :style="isToday(cell.day) ? 'color: white; opacity: 0.95;' : ''"
                                             x-text="event.title.substring(0, 12)"></div>
                                    </template>
                                    <div x-show="getDayEvents(cell.day).length > 3"
                                         class="text-[7px] font-bold text-center"
                                         :class="isToday(cell.day) ? 'text-white/80' : 'text-amber-600'">
                                        +<span x-text="getDayEvents(cell.day).length - 3"></span> more
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div x-show="viewMode === 'list'" class="bg-white border border-black/5 rounded-2xl md:rounded-[3rem] shadow-xl md:shadow-2xl p-6 md:p-10">
            <h3 class="text-2xl font-bold text-amber-900 font-serif italic mb-6">All Events</h3>

            <template x-if="filteredEvents.length === 0">
                <div class="text-center py-12">
                    <i class="fa-solid fa-calendar-xmark text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-semibold">No events found</p>
                </div>
            </template>

            <div class="space-y-4">
                <template x-for="event in filteredEvents.slice().sort((a, b) => new Date(a.event_date) - new Date(b.event_date))" :key="event.id">
                    <div class="border-l-4 rounded-lg p-4 bg-gradient-to-r from-amber-50/50 to-white hover:shadow-md transition-all"
                         :class="{
                             'border-orange-600': event.priority === 'high',
                             'border-amber-500': event.priority === 'medium',
                             'border-yellow-500': event.priority === 'low'
                         }">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h4 class="text-lg font-bold text-amber-900 font-serif" x-text="event.title"></h4>
                                    <span class="text-[8px] font-black uppercase px-2 py-0.5 rounded-full"
                                          :class="{
                                              'bg-amber-200 text-amber-900': event.category === 'work',
                                              'bg-yellow-200 text-yellow-900': event.category === 'personal',
                                              'bg-amber-300 text-amber-900': event.category === 'health',
                                              'bg-orange-200 text-orange-900': event.category === 'travel',
                                              'bg-yellow-300 text-yellow-900': event.category === 'education'
                                          }"
                                          x-text="event.category"></span>
                                    <span class="text-[8px] font-black uppercase px-2 py-0.5 rounded-full"
                                          :class="{
                                              'bg-orange-600 text-white': event.priority === 'high',
                                              'bg-amber-500 text-white': event.priority === 'medium',
                                              'bg-yellow-500 text-white': event.priority === 'low'
                                          }"
                                          x-text="event.priority"></span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2" x-text="event.description || 'No description'"></p>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                    <span><i class="fa-solid fa-calendar text-amber-600"></i> <span x-text="event.event_date"></span></span>
                                    <span x-show="event.start_time"><i class="fa-solid fa-clock text-amber-600"></i> <span x-text="event.start_time"></span><span x-show="event.end_time" x-text="' - ' + event.end_time"></span></span>
                                    <span x-show="event.location"><i class="fa-solid fa-location-dot text-amber-600"></i> <span x-text="event.location"></span></span>
                                </div>
                            </div>
                            <button @click="deleteEvent(event.id)"
                                    class="text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg px-3 py-2 transition-all">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Date Converters Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- BS to AD Conversion -->
            <div class="bg-white border border-black/5 rounded-[2.5rem] p-10 shadow-xl hover:shadow-2xl transition-all">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-14 w-14 bg-amber-50 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-calendar-days text-amber-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold font-serif italic text-amber-900">BS → AD</h3>
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest font-serif">Convert Bikram Sambat</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Year</label>
                            <input type="number" x-model.number="bsYear" placeholder="2081"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Month</label>
                            <input type="number" x-model.number="bsMonth" placeholder="1-12" min="1" max="12"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Day</label>
                            <input type="number" x-model.number="bsDay" placeholder="1-32" min="1" max="32"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                    </div>

                    <button @click="convertBsToAd()"
                            class="w-full bg-amber-900 hover:bg-amber-600 text-white py-4 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg hover:shadow-xl font-serif">
                        Convert to AD
                    </button>

                    <div x-show="bsToAdResult"
                         class="bg-amber-50 border border-amber-100 rounded-2xl p-6 text-center animate-fadeIn">
                        <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-2 font-serif">Result</p>
                        <p class="text-2xl font-bold text-amber-900 font-serif" x-text="bsToAdResult"></p>
                    </div>
                </div>
            </div>

            <!-- AD to BS Conversion -->
            <div class="bg-white border border-black/5 rounded-[2.5rem] p-10 shadow-xl hover:shadow-2xl transition-all">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-14 w-14 bg-amber-50 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-calendar text-amber-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold font-serif italic text-amber-900">AD → BS</h3>
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest font-serif">Convert Gregorian</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Year</label>
                            <input type="number" x-model.number="adYear" placeholder="2024"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Month</label>
                            <input type="number" x-model.number="adMonth" placeholder="1-12" min="1" max="12"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Day</label>
                            <input type="number" x-model.number="adDay" placeholder="1-31" min="1" max="31"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                    </div>

                    <button @click="convertAdToBs()"
                            class="w-full bg-amber-900 hover:bg-amber-600 text-white py-4 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg hover:shadow-xl font-serif">
                        Convert to BS
                    </button>

                    <div x-show="adToBsResult"
                         class="bg-amber-50 border border-amber-100 rounded-2xl p-6 text-center animate-fadeIn">
                        <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-2 font-serif">Result</p>
                        <p class="text-2xl font-bold text-amber-900 font-serif" x-text="adToBsResult"></p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Age Calculators Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Age from BS -->
            <div class="bg-white border border-black/5 rounded-[2.5rem] p-10 shadow-xl hover:shadow-2xl transition-all">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-14 w-14 bg-amber-50 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-cake-candles text-amber-900 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold font-serif italic text-amber-900">Age Calculator</h3>
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest font-serif">From BS Date</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Year</label>
                            <input type="number" x-model.number="ageBsYear" placeholder="2050"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Month</label>
                            <input type="number" x-model.number="ageBsMonth" placeholder="1-12" min="1" max="12"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Day</label>
                            <input type="number" x-model.number="ageBsDay" placeholder="1-32" min="1" max="32"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                    </div>

                    <button @click="calculateAgeBs()"
                            class="w-full bg-amber-900 hover:bg-amber-600 text-white py-4 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg hover:shadow-xl font-serif">
                        Calculate Age
                    </button>

                    <div x-show="ageBsResult"
                         class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center animate-fadeIn">
                        <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-2 font-serif">Your Age</p>
                        <p class="text-xl font-bold text-amber-900 font-serif" x-text="ageBsResult"></p>
                    </div>
                </div>
            </div>

            <!-- Age from AD -->
            <div class="bg-white border border-black/5 rounded-[2.5rem] p-10 shadow-xl hover:shadow-2xl transition-all">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-14 w-14 bg-amber-50 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-cake-candles text-amber-900 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold font-serif italic text-amber-900">Age Calculator</h3>
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest font-serif">From AD Date</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Year</label>
                            <input type="number" x-model.number="ageAdYear" placeholder="1990"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Month</label>
                            <input type="number" x-model.number="ageAdMonth" placeholder="1-12" min="1" max="12"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Day</label>
                            <input type="number" x-model.number="ageAdDay" placeholder="1-31" min="1" max="31"
                                   class="w-full bg-amber-50 border border-amber-100 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold text-center font-serif">
                        </div>
                    </div>

                    <button @click="calculateAgeAd()"
                            class="w-full bg-amber-900 hover:bg-amber-600 text-white py-4 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg hover:shadow-xl font-serif">
                        Calculate Age
                    </button>

                    <div x-show="ageAdResult"
                         class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center animate-fadeIn">
                        <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-2 font-serif">Your Age</p>
                        <p class="text-xl font-bold text-amber-900 font-serif" x-text="ageAdResult"></p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Event Creation/View Modal -->
        <div x-show="showEventModal"
             x-cloak
             @click.self="closeEventModal()"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div @click.stop
                 class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-amber-600 to-amber-500 px-8 py-6 rounded-t-3xl">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-white font-serif italic">Events</h3>
                            <p class="text-white/80 text-sm font-serif" x-text="selectedDateText"></p>
                        </div>
                        <button @click="closeEventModal()"
                                class="text-white hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center transition-all">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-8 space-y-6">

                    <!-- Existing Events List -->
                    <div x-show="selectedDayEvents.length > 0" class="space-y-3">
                        <h4 class="text-sm font-black uppercase tracking-widest text-gray-900">Scheduled Events</h4>
                        <template x-for="event in selectedDayEvents" :key="event.id">
                            <div class="border-l-4 rounded-lg p-4 space-y-2 relative shadow-sm hover:shadow-md transition-all"
                                 :class="{
                                     'bg-indigo-50 border-indigo-500': event.event_type === 'hotel',
                                     'bg-green-50 border-green-500': event.event_type === 'car',
                                     'bg-amber-50 border-amber-500': event.event_type === 'manual'
                                 }">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg font-bold font-serif"
                                                  :class="{
                                                      'text-indigo-900': event.event_type === 'hotel',
                                                      'text-green-900': event.event_type === 'car',
                                                      'text-amber-900': event.event_type === 'manual'
                                                  }"
                                                  x-text="event.title"></span>
                                            <span class="text-[8px] font-black uppercase px-2 py-0.5 rounded-full"
                                                  :class="{
                                                      'bg-indigo-200 text-indigo-800': event.event_type === 'hotel',
                                                      'bg-green-200 text-green-800': event.event_type === 'car',
                                                      'bg-amber-200 text-amber-800': event.event_type === 'manual'
                                                  }"
                                                  x-text="event.event_type"></span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1" x-text="event.description || 'No description'"></p>
                                    </div>
                                    <button @click="deleteEvent(event.id)"
                                            class="text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg px-2 py-1 transition-all">
                                        <i class="fa-solid fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Add New Event Form -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-black uppercase tracking-widest text-gray-900">Add New Event</h4>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2 space-y-2">
                                <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Event Title *</label>
                                <input type="text"
                                       x-model="newEventTitle"
                                       placeholder="Enter event title"
                                       class="w-full bg-amber-50 border-2 border-amber-200 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold font-serif">
                            </div>

                            <div class="col-span-2 space-y-2">
                                <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Description</label>
                                <textarea
                                    x-model="newEventDescription"
                                    placeholder="Enter event description"
                                    rows="2"
                                    class="w-full bg-amber-50 border-2 border-amber-200 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-serif"></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Start Time</label>
                                <input type="time"
                                       x-model="newEventStartTime"
                                       class="w-full bg-amber-50 border-2 border-amber-200 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold font-serif">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">End Time</label>
                                <input type="time"
                                       x-model="newEventEndTime"
                                       class="w-full bg-amber-50 border-2 border-amber-200 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold font-serif">
                            </div>

                            <div class="col-span-2 space-y-2">
                                <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Location</label>
                                <input type="text"
                                       x-model="newEventLocation"
                                       placeholder="Enter location"
                                       class="w-full bg-amber-50 border-2 border-amber-200 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-serif">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Category</label>
                                <select x-model="newEventCategory"
                                        class="w-full bg-amber-50 border-2 border-amber-200 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold font-serif">
                                    <option value="personal">Personal</option>
                                    <option value="work">Work</option>
                                    <option value="health">Health</option>
                                    <option value="travel">Travel</option>
                                    <option value="education">Education</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-2 font-serif">Priority</label>
                                <select x-model="newEventPriority"
                                        class="w-full bg-amber-50 border-2 border-amber-200 text-amber-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-600 focus:border-transparent font-bold font-serif">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>

                        <button @click="createEvent()"
                                :disabled="!newEventTitle"
                                class="w-full bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-700 hover:to-amber-600 disabled:from-gray-400 disabled:to-gray-500 text-white py-4 rounded-xl font-black text-[11px] uppercase tracking-widest transition-all shadow-lg hover:shadow-xl disabled:cursor-not-allowed">
                            <i class="fa-solid fa-plus mr-2"></i>Add Event
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calendarApp() {
            return {
                calendar: @json($calendar),
                currentYear: {{ $year }},
                currentMonthIndex: 0,
                todayDateText: 'Loading...',
                daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

                // Events
                events: [],
                filteredEvents: [],
                eventDateMap: {}, // Map to store BS date -> events
                showEventModal: false,
                selectedDay: null,
                selectedDate: null,
                selectedDateText: '',
                selectedDayEvents: [],
                newEventTitle: '',
                newEventDescription: '',
                newEventLocation: '',
                newEventStartTime: '',
                newEventEndTime: '',
                newEventCategory: 'personal',
                newEventPriority: 'medium',

                // Stats
                stats: {},

                // View and Filters
                viewMode: 'month', // 'month' or 'list'
                searchQuery: '',
                filterCategory: '',
                filterPriority: '',

                // Today's BS date
                todayBsYear: null,
                todayBsMonth: null,
                todayBsDay: null,

                // Form data
                bsYear: '',
                bsMonth: '',
                bsDay: '',
                adYear: '',
                adMonth: '',
                adDay: '',
                ageBsYear: '',
                ageBsMonth: '',
                ageBsDay: '',
                ageAdYear: '',
                ageAdMonth: '',
                ageAdDay: '',

                // Results
                bsToAdResult: '',
                adToBsResult: '',
                ageBsResult: '',
                ageAdResult: '',

                get currentMonthName() {
                    return Object.keys(this.calendar)[this.currentMonthIndex] || '';
                },

                get currentMonthDays() {
                    const monthName = this.currentMonthName;
                    return this.calendar[monthName] || [];
                },

                getCalendarGrid() {
                    const days = this.currentMonthDays;
                    if (!days.length) return [];

                    // Map day names to indices (Sun=0, Mon=1, ..., Sat=6)
                    const dayMap = {
                        'Sunday': 0, 'Sun': 0,
                        'Monday': 1, 'Mon': 1,
                        'Tuesday': 2, 'Tue': 2,
                        'Wednesday': 3, 'Wed': 3,
                        'Thursday': 4, 'Thu': 4,
                        'Friday': 5, 'Fri': 5,
                        'Saturday': 6, 'Sat': 6
                    };

                    // Get the first day of the month
                    const firstDayName = days[0].day;
                    const firstDayIndex = dayMap[firstDayName] || 0;

                    // Create empty cells for days before the first day
                    const grid = [];
                    for (let i = 0; i < firstDayIndex; i++) {
                        grid.push({ day: null });
                    }

                    // Add all the days of the month
                    days.forEach(day => {
                        grid.push({ day: day });
                    });

                    return grid;
                },

                prevMonth() {
                    if (this.currentMonthIndex > 0) {
                        this.currentMonthIndex--;
                    }
                },

                nextMonth() {
                    if (this.currentMonthIndex < Object.keys(this.calendar).length - 1) {
                        this.currentMonthIndex++;
                    }
                },

                isToday(day) {
                    if (!day || !this.todayBsYear || !this.todayBsMonth || !this.todayBsDay) {
                        return false;
                    }

                    // Check if this day matches today's BS date
                    const monthIndex = this.currentMonthIndex + 1;
                    return this.currentYear === this.todayBsYear &&
                           monthIndex === this.todayBsMonth &&
                           day.date === this.todayBsDay;
                },

                async getTodayDate() {
                    this.todayDateText = 'Loading...';
                    try {
                        const res = await fetch('{{ url('/api/todaydate') }}');
                        const data = await res.json();
                        console.log('API Raw Response:', data);

                        if (data.success) {
                            this.todayDateText = `AD: ${data.ad.formatted} | BS: ${data.bs.formatted}`;

                            // Store today's BS date for highlighting
                            this.todayBsYear = data.bs.year;
                            this.todayBsMonth = data.bs.month;
                            this.todayBsDay = data.bs.day;

                            console.log('Stored BS Date:', {
                                year: this.todayBsYear,
                                month: this.todayBsMonth,
                                day: this.todayBsDay
                            });

                            console.log('Calendar data keys:', Object.keys(this.calendar));
                            const monthNames = Object.keys(this.calendar);
                            console.log('Month at index 10:', monthNames[10]);
                            console.log('All months:', monthNames);

                            // Auto-navigate to current month if we're viewing the current year
                            if (this.currentYear === this.todayBsYear) {
                                this.currentMonthIndex = this.todayBsMonth - 1;
                                console.log('Set currentMonthIndex to:', this.currentMonthIndex);
                                console.log('Current month name:', this.currentMonthName);
                            }
                        } else {
                            this.todayDateText = 'Failed to fetch date';
                        }
                    } catch (e) {
                        this.todayDateText = 'Error fetching date';
                        console.error('Error fetching today date:', e);
                    }
                },

                async convertBsToAd() {
                    if (!this.bsYear || !this.bsMonth || !this.bsDay) {
                        this.bsToAdResult = 'Please fill all fields';
                        return;
                    }
                    this.bsToAdResult = 'Converting...';
                    try {
                        const res = await fetch(`{{ url('/api/bs-to-ad') }}/${this.bsYear}/${this.bsMonth}/${this.bsDay}`);
                        const data = await res.json();
                        if (data.success && data.result) {
                            this.bsToAdResult = `${data.result.year}-${String(data.result.month).padStart(2, '0')}-${String(data.result.day).padStart(2, '0')}`;
                        } else {
                            this.bsToAdResult = '❌ Conversion Error - Using Fallback';
                        }
                    } catch (e) {
                        console.error(e);
                        this.bsToAdResult = '⚠️ Network Error - Using Fallback';
                    }
                },

                async convertAdToBs() {
                    if (!this.adYear || !this.adMonth || !this.adDay) {
                        this.adToBsResult = 'Please fill all fields';
                        return;
                    }
                    this.adToBsResult = 'Converting...';
                    try {
                        const res = await fetch(`{{ url('/api/ad-to-bs') }}/${this.adYear}/${this.adMonth}/${this.adDay}`);
                        const data = await res.json();
                        if (data.success && data.result) {
                            this.adToBsResult = `${data.result.year}-${String(data.result.month).padStart(2, '0')}-${String(data.result.day).padStart(2, '0')}`;
                        } else {
                            this.adToBsResult = '❌ Conversion Error - Using Fallback';
                        }
                    } catch (e) {
                        console.error(e);
                        this.adToBsResult = '⚠️ Network Error - Using Fallback';
                    }
                },

                async calculateAgeBs() {
                    if (!this.ageBsYear || !this.ageBsMonth || !this.ageBsDay) {
                        this.ageBsResult = 'Please fill all birthdate fields';
                        return;
                    }
                    this.ageBsResult = 'Calculating...';
                    try {
                        const res = await fetch(`{{ url('/api/calculateage/bs') }}/${this.ageBsYear}/${this.ageBsMonth}/${this.ageBsDay}`);
                        const data = await res.json();
                        if (data.success && data.age) {
                            this.ageBsResult = data.age.formatted || `${data.age.years} years old`;
                        } else {
                            this.ageBsResult = '❌ Calculation Error';
                        }
                    } catch (e) {
                        console.error(e);
                        this.ageBsResult = '⚠️ Network Error';
                    }
                },

                async calculateAgeAd() {
                    if (!this.ageAdYear || !this.ageAdMonth || !this.ageAdDay) {
                        this.ageAdResult = 'Please fill all birthdate fields';
                        return;
                    }
                    this.ageAdResult = 'Calculating...';
                    try {
                        const res = await fetch(`{{ url('/api/calculateage/ad') }}/${this.ageAdYear}/${this.ageAdMonth}/${this.ageAdDay}`);
                        const data = await res.json();
                        if (data.success && data.age) {
                            this.ageAdResult = data.age.formatted || `${data.age.years} years old`;
                        } else {
                            this.ageAdResult = '❌ Calculation Error';
                        }
                    } catch (e) {
                        console.error(e);
                        this.ageAdResult = '⚠️ Network Error';
                    }
                },

                // Event Management Methods
                async loadEvents() {
                    try {
                        const res = await fetch('{{ url('/api/events') }}', {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.events = data.events || [];
                            this.filteredEvents = this.events;
                            await this.buildEventDateMap();
                        } else {
                            console.error('Failed to load events:', data.error);
                            this.events = [];
                            this.filteredEvents = [];
                        }
                    } catch (e) {
                        console.error('Failed to load events:', e);
                        this.events = [];
                        this.filteredEvents = [];
                    }
                },

                // Build a map of BS dates to events for fast lookup
                async buildEventDateMap() {
                    this.eventDateMap = {};

                    for (const event of this.events) {
                        try {
                            // Convert AD event date to BS
                            const adDate = new Date(event.event_date);
                            const year = adDate.getFullYear();
                            const month = adDate.getMonth() + 1;
                            const day = adDate.getDate();

                            try {
                                const res = await fetch(`{{ url('/api/ad-to-bs') }}/${year}/${month}/${day}`);
                                const data = await res.json();

                                if (data.success && data.result) {
                                    const bsDateKey = `${data.result.year}-${String(data.result.month).padStart(2, '0')}-${String(data.result.day).padStart(2, '0')}`;
                                    if (!this.eventDateMap[bsDateKey]) {
                                        this.eventDateMap[bsDateKey] = [];
                                    }
                                    this.eventDateMap[bsDateKey].push(event);
                                } else {
                                    // Fallback: use approximate conversion
                                    const bsYear = year + 56;
                                    const bsDateKey = `${bsYear}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                                    if (!this.eventDateMap[bsDateKey]) {
                                        this.eventDateMap[bsDateKey] = [];
                                    }
                                    this.eventDateMap[bsDateKey].push(event);
                                }
                            } catch (e) {
                                // Fallback: use approximate conversion
                                const bsYear = year + 56;
                                const bsDateKey = `${bsYear}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                                if (!this.eventDateMap[bsDateKey]) {
                                    this.eventDateMap[bsDateKey] = [];
                                }
                                this.eventDateMap[bsDateKey].push(event);
                            }
                        } catch (e) {
                            console.error('Error processing event date:', e);
                        }
                    }
                },

                async openEventModal(day) {
                    if (!day) return;

                    this.selectedDay = day;
                    const monthIndex = this.currentMonthIndex + 1;
                    const dateStr = `${this.currentYear}-${String(monthIndex).padStart(2, '0')}-${String(day.date).padStart(2, '0')}`;

                    // Convert BS to AD for storage
                    try {
                        const res = await fetch(`{{ url('/api/bs-to-ad') }}/${this.currentYear}/${monthIndex}/${day.date}`);
                        const data = await res.json();
                        if (data.success && data.result) {
                            this.selectedDate = `${data.result.year}-${String(data.result.month).padStart(2, '0')}-${String(data.result.day).padStart(2, '0')}`;
                            this.selectedDateText = `BS ${dateStr} (AD ${this.selectedDate})`;
                        } else {
                            // Fallback
                            const adYear = this.currentYear - 56;
                            this.selectedDate = `${adYear}-${String(monthIndex).padStart(2, '0')}-${String(day.date).padStart(2, '0')}`;
                            this.selectedDateText = `BS ${dateStr}`;
                        }
                    } catch(e) {
                        // Fallback
                        const adYear = this.currentYear - 56;
                        this.selectedDate = `${adYear}-${String(monthIndex).padStart(2, '0')}-${String(day.date).padStart(2, '0')}`;
                        this.selectedDateText = `BS ${dateStr}`;
                    }

                    this.selectedDayEvents = this.getDayEvents(day);
                    this.showEventModal = true;
                    this.newEventTitle = '';
                    this.newEventDescription = '';
                },

                closeEventModal() {
                    this.showEventModal = false;
                    this.selectedDay = null;
                    this.selectedDate = null;
                    this.selectedDayEvents = [];
                },

                async createEvent() {
                    if (!this.newEventTitle || !this.selectedDate) {
                        return alert('Please enter event title');
                    }

                    try {
                        const res = await fetch('{{ url('/api/events') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                title: this.newEventTitle,
                                description: this.newEventDescription,
                                location: this.newEventLocation,
                                event_date: this.selectedDate,
                                start_time: this.newEventStartTime,
                                end_time: this.newEventEndTime,
                                category: this.newEventCategory,
                                priority: this.newEventPriority,
                                event_type: 'manual'
                            })
                        });

                        const data = await res.json();
                        if (data.success) {
                            await this.loadEvents();
                            await this.loadStats();
                            this.selectedDayEvents = this.getDayEvents(this.selectedDay);
                            this.newEventTitle = '';
                            this.newEventDescription = '';
                            this.newEventLocation = '';
                            this.newEventStartTime = '';
                            this.newEventEndTime = '';
                            this.newEventCategory = 'personal';
                            this.newEventPriority = 'medium';
                            alert('Event created successfully!');
                        } else {
                            alert('Failed to create event: ' + (data.error || 'Unknown error'));
                        }
                    } catch (e) {
                        console.error('Error creating event:', e);
                        alert('Error creating event');
                    }
                },

                async deleteEvent(eventId) {
                    if (!confirm('Are you sure you want to delete this event?')) {
                        return;
                    }

                    try {
                        const res = await fetch(`{{ url('/api/events') }}/${eventId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await res.json();
                        if (data.success) {
                            await this.loadEvents();
                            await this.loadStats();
                            this.selectedDayEvents = this.getDayEvents(this.selectedDay);
                            alert('Event deleted successfully!');
                        } else {
                            alert('Failed to delete event');
                        }
                    } catch (e) {
                        console.error('Error deleting event:', e);
                        alert('Error deleting event');
                    }
                },

                hasEvents(day) {
                    if (!day) return false;
                    const monthIndex = this.currentMonthIndex + 1;
                    const bsDateKey = `${this.currentYear}-${String(monthIndex).padStart(2, '0')}-${String(day.date).padStart(2, '0')}`;
                    return this.eventDateMap[bsDateKey] && this.eventDateMap[bsDateKey].length > 0;
                },

                getDayEvents(day) {
                    if (!day) return [];
                    const monthIndex = this.currentMonthIndex + 1;
                    const bsDateKey = `${this.currentYear}-${String(monthIndex).padStart(2, '0')}-${String(day.date).padStart(2, '0')}`;
                    return this.eventDateMap[bsDateKey] || [];
                },

                getEventColorClass(event) {
                    if (event.event_type === 'hotel') {
                        return 'bg-amber-600 text-white';
                    } else if (event.event_type === 'car') {
                        return 'bg-yellow-600 text-white';
                    } else {
                        return 'bg-amber-500 text-white';
                    }
                },

                async loadStats() {
                    try {
                        const res = await fetch('{{ url('/api/events/stats') }}', {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.stats = data.stats;
                        }
                    } catch (e) {
                        console.error('Failed to load stats:', e);
                    }
                },

                async searchEvents() {
                    this.filterEvents();
                },

                async filterEvents() {
                    try {
                        const params = new URLSearchParams();
                        if (this.searchQuery) params.append('search', this.searchQuery);
                        if (this.filterCategory) params.append('category', this.filterCategory);
                        if (this.filterPriority) params.append('priority', this.filterPriority);

                        const res = await fetch('{{ url('/api/events/search') }}?' + params.toString(), {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.filteredEvents = data.events || [];
                            // Also update the calendar view
                            this.events = this.filteredEvents;
                            await this.buildEventDateMap();
                        }
                    } catch (e) {
                        console.error('Failed to filter events:', e);
                    }
                },

                async init() {
                    await this.getTodayDate();
                    this.loadEvents();
                    this.loadStats();
                    this.filteredEvents = this.events;
                }
            }
        }
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
@endsection
