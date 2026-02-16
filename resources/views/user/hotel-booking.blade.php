@extends('layouts.app')

@section('content')
    <div x-data="hotelBooking()" class="flex flex-col items-center justify-center py-6 sm:py-8 md:py-12 lg:py-20 space-y-6 sm:space-y-8 md:space-y-10 lg:space-y-12">

        <div class="animate-slide-up space-y-2 md:space-y-3 text-center">
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-stone-900 uppercase italic tracking-tighter">Hotel Booking</h2>
            <p class="text-sm md:text-base text-amber-700 font-bold italic">Reserve your perfect stay today</p>
        </div>

        <!-- Hotel Info Card -->
        <div
            class="bg-gradient-to-br from-white to-amber-50/30 border-2 border-amber-300 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3rem] shadow-md sm:shadow-lg md:shadow-xl w-full max-w-4xl p-4 sm:p-6 md:p-8 lg:p-10 flex flex-col md:flex-row gap-4 sm:gap-6 md:gap-8 lg:gap-10 animate-fade-in hover:shadow-xl hover:shadow-amber-700/10 transition-all duration-300">

            <!-- Hotel Image -->
            <div class="md:w-1/2 rounded-lg sm:rounded-xl md:rounded-2xl lg:rounded-[2rem] overflow-hidden shadow-md sm:shadow-lg">
                <img :src="hotel.image || 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=800'"
                    class="w-full h-full object-cover" :alt="hotel.name">
            </div>

            <!-- Hotel Details -->
            <div class="md:w-1/2 space-y-3 md:space-y-4">
                <div class="flex flex-col sm:flex-row items-start justify-between gap-3">
                    <div class="space-y-1.5 md:space-y-2">
                        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold italic text-stone-900" x-text="hotel.name"></h3>
                        <p class="text-xs sm:text-sm md:text-base text-stone-600 mt-2 flex items-center gap-2 font-medium italic">
                            <i class="fa-solid fa-location-dot text-amber-700"></i>
                            <span x-text="hotel.location"></span>
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-amber-700 to-amber-800 text-white px-3 md:px-4 py-1.5 md:py-2 rounded-full text-xs md:text-sm font-black uppercase tracking-wider md:tracking-widest shadow-lg shadow-amber-700/20">
                        ★ <span x-text="hotel.rating || '4.5'"></span>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="flex flex-wrap gap-1.5 md:gap-2 mt-3 md:mt-4" x-show="hotel.amenities && hotel.amenities.length > 0">
                    <template x-for="(amenity, aidx) in (hotel.amenities || [])" :key="aidx">
                        <span
                            class="text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-900 px-3 py-1.5 rounded-full hover:bg-amber-200 transition-all"
                            x-text="amenity"></span>
                    </template>
                </div>

                <div class="grid grid-cols-2 gap-3 md:gap-4 mt-3 md:mt-4 p-4 md:p-6 bg-amber-50/50 border border-amber-200 rounded-xl md:rounded-2xl">
                    <div class="space-y-1">
                        <p class="text-sm font-black uppercase tracking-widest text-amber-900">Location</p>
                        <p class="text-base font-bold italic text-stone-900" x-text="hotel.location"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-black uppercase tracking-widest text-amber-900">Rating</p>
                        <p class="text-base font-bold italic text-amber-700">★ <span x-text="hotel.rating || '4.5'"></span></p>
                    </div>
                    <div class="space-y-1 col-span-2">
                        <p class="text-sm font-black uppercase tracking-widest text-amber-900">Price Per Night</p>
                        <p class="text-lg font-bold italic text-amber-700" x-text="'Rs. ' + (hotel.pricePerNight || '5000')"></p>
                    </div>
                </div>

                <!-- Input & Pay Button -->
                <div class="mt-6 space-y-4" x-show="!receipt">
                    <div>
                        <label class="block text-amber-900 font-bold italic text-sm mb-3 uppercase tracking-widest">Check-in Date</label>
                        <input type="date" x-model="checkInDate"
                            :min="new Date().toISOString().split('T')[0]"
                            class="w-full bg-amber-50 border-2 border-amber-200 rounded-xl px-4 py-3 font-bold italic focus:border-amber-700 focus:ring-2 focus:ring-amber-700/20 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-amber-900 font-bold italic text-sm mb-3 uppercase tracking-widest">Number of Nights</label>
                        <input type="number" x-model.number="nights" min="1"
                            class="w-full bg-amber-50 border-2 border-amber-200 rounded-xl px-4 py-3 font-bold italic focus:border-amber-700 focus:ring-2 focus:ring-amber-700/20 outline-none transition-all"
                            @change="calculatePrice()">
                        <p class="text-amber-700 font-bold italic mt-2" x-show="nights > 0">Total: <span class="text-lg" x-text="'Rs. ' + (nights * (hotel.pricePerNight || 5000)).toLocaleString()"></span></p>
                    </div>

                    <button @click="confirmBooking()"
                        class="w-full bg-gradient-to-r from-stone-900 to-stone-800 text-white px-6 py-4 rounded-xl font-black text-[12px] uppercase tracking-widest hover:from-amber-700 hover:to-amber-800 hover:shadow-lg hover:shadow-amber-700/20 transition-all duration-300 active:scale-95 shadow-md italic">
                        <i class="fa-solid fa-lock mr-2"></i>Pay Now
                    </button>
                </div>

                <!-- Receipt -->
                <div x-show="receipt" class="mt-6 bg-gradient-to-br from-amber-50/80 to-white border-2 border-amber-300 p-8 rounded-2xl space-y-6 animate-scale-in shadow-lg shadow-amber-700/10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-amber-900 uppercase italic tracking-widest">✓ Booking Confirmed!</h3>
                        <div class="text-4xl animate-bounce">✓</div>
                    </div>

                    <div class="space-y-3 bg-white p-6 rounded-xl border border-amber-200">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-amber-700 font-bold italic uppercase text-xs tracking-widest">Hotel</p>
                                <p class="text-stone-900 font-bold" x-text="hotel.name"></p>
                            </div>
                            <div>
                                <p class="text-amber-700 font-bold italic uppercase text-xs tracking-widest">Location</p>
                                <p class="text-stone-900 font-bold" x-text="hotel.location"></p>
                            </div>
                            <div>
                                <p class="text-amber-700 font-bold italic uppercase text-xs tracking-widest">Rating</p>
                                <p class="text-amber-700 font-bold">★ <span x-text="hotel.rating || '4.5'"></span></p>
                            </div>
                            <div>
                                <p class="text-amber-700 font-bold italic uppercase text-xs tracking-widest">Nights</p>
                                <p class="text-stone-900 font-bold" x-text="nights"></p>
                            </div>
                        </div>
                        <template x-if="hotel.amenities && hotel.amenities.length > 0">
                            <div class="pt-4 border-t border-amber-200">
                                <p class="text-amber-700 font-bold italic uppercase text-xs tracking-widest mb-2">Amenities</p>
                                <p class="text-stone-700" x-text="(hotel.amenities || []).join(', ')"></p>
                            </div>
                        </template>
                    </div>

                    <div class="bg-gradient-to-r from-amber-100 to-amber-50 border-2 border-amber-300 p-6 rounded-xl">
                        <div class="flex justify-between items-center">
                            <span class="text-stone-900 font-black italic uppercase tracking-widest">Total Amount:</span>
                            <span class="text-3xl font-black italic text-amber-700" x-text="'Rs. ' + (nights * (hotel.pricePerNight || 5000)).toLocaleString()"></span>
                        </div>
                    </div>

                    <div class="bg-stone-900 text-white p-4 rounded-xl text-center space-y-1 text-sm italic font-medium">
                        <p>✓ Your hotel booking is confirmed!</p>
                        <p>Booking Date: <span x-text="new Date().toLocaleDateString()"></span></p>
                    </div>

                    <button @click="downloadPDF()"
                        class="w-full bg-gradient-to-r from-amber-700 to-amber-800 text-white px-6 py-4 rounded-xl font-black text-[12px] uppercase tracking-widest hover:shadow-lg hover:shadow-amber-700/30 hover:-translate-y-1 transition-all shadow-md italic">
                        <i class="fa-solid fa-download mr-2"></i>Download PDF Receipt
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jsPDF CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        window.hotelBooking = function() {
            let hotelData = sessionStorage.getItem('selectedHotel');
            if (!hotelData) {
                window.location.href = "{{ route('user.travel') }}";
                return {};
            }

            return {
                hotel: JSON.parse(hotelData),
                nights: 1,
                checkInDate: new Date().toISOString().split('T')[0],
                receipt: null,

                calculatePrice() {
                    // This updates the reactive display
                    return this.nights * (this.hotel.pricePerNight || 5000);
                },

                async payHotel() {
                    if (!this.hotel) return;
                    if (this.nights < 1) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Input',
                            text: 'Please enter a valid number of nights',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#b45309'
                        });
                        return;
                    }

                    if (!this.checkInDate) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Missing Date',
                            text: 'Please select a check-in date',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#b45309'
                        });
                        return;
                    }

                    // Show loading
                    Swal.fire({
                        title: 'Processing Payment...',
                        html: '<div class="flex items-center justify-center gap-3"><div class="h-3 w-3 bg-amber-700 rounded-full animate-bounce" style="animation-delay: 0s"></div><div class="h-3 w-3 bg-amber-700 rounded-full animate-bounce" style="animation-delay: 0.2s"></div><div class="h-3 w-3 bg-amber-700 rounded-full animate-bounce" style="animation-delay: 0.4s"></div></div>',
                        text: 'Please wait while we process your booking',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const response = await fetch("{{ route('user.travel.hotelPayment') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                hotelName: this.hotel.name,
                                location: this.hotel.location,
                                nights: this.nights,
                                checkInDate: this.checkInDate,
                                total: this.nights * (this.hotel.pricePerNight || 5000),
                                hotelData: this.hotel
                            })
                        });

                        const data = await response.json();

                        if (data.status === 'success') {
                            // Save event to calendar
                            await this.saveToCalendar(data.bookingId);

                            Swal.fire({
                                icon: 'success',
                                title: 'Booking Successful!',
                                html: 'Your hotel booking has been confirmed<br><small>Event added to calendar</small>',
                                confirmButtonText: 'Great!',
                                confirmButtonColor: '#b45309'
                            });
                            // Show receipt
                            this.receipt = true;
                        } else {
                            throw new Error(data.message || 'Payment failed');
                        }
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Failed',
                            text: err.message || 'An error occurred while processing your payment',
                            confirmButtonText: 'Try Again',
                            confirmButtonColor: '#b45309'
                        });
                    }
                },

                async saveToCalendar(bookingId) {
                    try {
                        const eventData = {
                            title: `Hotel: ${this.hotel.name}`,
                            description: `Check-in at ${this.hotel.name}, ${this.hotel.location} for ${this.nights} night(s)`,
                            event_date: this.checkInDate,
                            event_type: 'hotel',
                            related_id: bookingId
                        };

                        await fetch('{{ url('/api/events') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(eventData)
                        });
                    } catch (e) {
                        console.error('Failed to save calendar event:', e);
                    }
                },

                confirmBooking() {
                    if (!this.hotel) return;
                    if (this.nights < 1) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Input',
                            text: 'Please enter a valid number of nights',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#b45309'
                        });
                        return;
                    }

                    if (!this.checkInDate) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Missing Date',
                            text: 'Please select a check-in date',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#b45309'
                        });
                        return;
                    }

                    const total = this.nights * (this.hotel.pricePerNight || 5000);
                    Swal.fire({
                        title: 'Confirm Hotel Booking',
                        html: `
                            <div class="text-left space-y-3">
                                <div class="bg-amber-50 p-4 rounded-lg">
                                    <p class="mb-2 font-bold"><span class="text-amber-700">Hotel:</span> ${this.hotel.name}</p>
                                    <p class="mb-2 font-bold"><span class="text-amber-700">Location:</span> ${this.hotel.location}</p>
                                    <p class="mb-2 font-bold"><span class="text-amber-700">Check-in:</span> ${this.checkInDate}</p>
                                    <p class="mb-2 font-bold"><span class="text-amber-700">Nights:</span> ${this.nights}</p>
                                    <p class="mb-2 font-bold"><span class="text-amber-700">Price per Night:</span> Rs. ${(this.hotel.pricePerNight || 5000).toLocaleString()}</p>
                                </div>
                                <div class="bg-gradient-to-r from-amber-100 to-amber-50 p-4 rounded-lg border-2 border-amber-300">
                                    <p class="text-lg font-bold italic text-amber-700"><strong>Total Amount:</strong> Rs. ${total.toLocaleString()}</p>
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Book Now!',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#b45309',
                        cancelButtonColor: '#dc2626',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.payHotel();
                        }
                    });
                },

                downloadPDF() {
                    const {
                        jsPDF
                    } = window.jspdf;
                    const doc = new jsPDF();

                    const pricePerNight = this.hotel.pricePerNight || 5000;
                    const total = this.nights * pricePerNight;
                    const date = new Date().toLocaleString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const pageWidth = doc.internal.pageSize.getWidth();
                    const pageHeight = doc.internal.pageSize.getHeight();

                    // Header background
                    doc.setFillColor(180, 83, 9); // amber-700
                    doc.rect(0, 0, pageWidth, 45, 'F');

                    // Company name in header
                    doc.setTextColor(255, 255, 255);
                    doc.setFontSize(24);
                    doc.setFont(undefined, 'bold');
                    doc.text("NEXO TRAVEL", pageWidth / 2, 20, {
                        align: 'center'
                    });

                    doc.setFontSize(12);
                    doc.setFont(undefined, 'normal');
                    doc.text("Premium Hotel Booking Services", pageWidth / 2, 30, {
                        align: 'center'
                    });

                    // Receipt title
                    doc.setTextColor(180, 83, 9);
                    doc.setFontSize(18);
                    doc.setFont(undefined, 'bold');
                    doc.text("HOTEL BOOKING RECEIPT", pageWidth / 2, 60, {
                        align: 'center'
                    });

                    // Divider line
                    doc.setDrawColor(180, 83, 9);
                    doc.setLineWidth(1);
                    doc.line(20, 65, pageWidth - 20, 65);

                    // Receipt details section
                    let yPos = 80;

                    // Section: Hotel Information
                    doc.setFontSize(14);
                    doc.setFont(undefined, 'bold');
                    doc.setTextColor(180, 83, 9); // amber-700
                    doc.text("HOTEL INFORMATION", 20, yPos);
                    yPos += 10;

                    doc.setFontSize(11);
                    doc.setFont(undefined, 'normal');
                    doc.setTextColor(60, 60, 60);

                    const hotelInfo = [{
                            label: 'Hotel Name:',
                            value: this.hotel.name
                        },
                        {
                            label: 'Location:',
                            value: this.hotel.location
                        },
                        {
                            label: 'Rating:',
                            value: `${this.hotel.rating || '4.5'}`
                        }
                    ];

                    if (this.hotel.amenities && this.hotel.amenities.length > 0) {
                        hotelInfo.push({
                            label: 'Amenities:',
                            value: this.hotel.amenities.join(', ')
                        });
                    }

                    hotelInfo.forEach(item => {
                        doc.setFont(undefined, 'bold');
                        doc.text(item.label, 25, yPos);
                        doc.setFont(undefined, 'normal');
                        // Handle long values
                        const lines = doc.splitTextToSize(item.value, pageWidth - 90);
                        doc.text(lines, 75, yPos);
                        yPos += lines.length * 8;
                    });

                    yPos += 5;

                    // Section: Booking Details
                    doc.setFontSize(14);
                    doc.setFont(undefined, 'bold');
                    doc.setTextColor(180, 83, 9);
                    doc.text("BOOKING DETAILS", 20, yPos);
                    yPos += 10;

                    doc.setFontSize(11);
                    doc.setFont(undefined, 'normal');
                    doc.setTextColor(60, 60, 60);

                    const bookingInfo = [{
                            label: 'Price Per Night:',
                            value: `Rs. ${pricePerNight.toLocaleString()}`
                        },
                        {
                            label: 'Number of Nights:',
                            value: `${this.nights} ${this.nights === 1 ? 'Night' : 'Nights'}`
                        },
                        {
                            label: 'Booking Date:',
                            value: date
                        }
                    ];

                    bookingInfo.forEach(item => {
                        doc.setFont(undefined, 'bold');
                        doc.text(item.label, 25, yPos);
                        doc.setFont(undefined, 'normal');
                        doc.text(item.value, 75, yPos);
                        yPos += 8;
                    });

                    yPos += 10;

                    // Total amount box
                    doc.setFillColor(254, 243, 199); // amber-50
                    doc.roundedRect(20, yPos - 5, pageWidth - 40, 20, 3, 3, 'F');
                    doc.setDrawColor(180, 83, 9);
                    doc.setLineWidth(1);
                    doc.roundedRect(20, yPos - 5, pageWidth - 40, 20, 3, 3, 'S');

                    doc.setFontSize(14);
                    doc.setFont(undefined, 'bold');
                    doc.setTextColor(120, 53, 15); // amber-900
                    doc.text("TOTAL AMOUNT:", 25, yPos + 7);

                    doc.setFontSize(16);
                    doc.setTextColor(180, 83, 9);
                    doc.text(`Rs. ${total.toLocaleString()}`, pageWidth - 25, yPos + 7, {
                        align: 'right'
                    });

                    yPos += 35;

                    // Information box
                    doc.setFillColor(254, 243, 199); // amber-50
                    doc.setDrawColor(180, 83, 9);
                    doc.setLineWidth(1);
                    doc.roundedRect(20, yPos, pageWidth - 40, 25, 3, 3, 'FD');

                    doc.setFontSize(10);
                    doc.setFont(undefined, 'bold');
                    doc.setTextColor(180, 83, 9);
                    doc.text("CHECK-IN INFORMATION", pageWidth / 2, yPos + 8, {
                        align: 'center'
                    });

                    doc.setFont(undefined, 'normal');
                    doc.setTextColor(60, 60, 60);
                    doc.text("Your hotel booking is confirmed! Please proceed to the hotel at your check-in time.",
                        pageWidth / 2,
                        yPos + 16, {
                            align: 'center'
                        });

                    // Footer
                    doc.setFontSize(9);
                    doc.setTextColor(150, 150, 150);
                    doc.text("Thank you for choosing Nexo Travel!", pageWidth / 2, pageHeight - 20, {
                        align: 'center'
                    });
                    doc.text("For inquiries, contact us at support@nexotravel.com", pageWidth / 2, pageHeight - 15, {
                        align: 'center'
                    });

                    // Divider above footer
                    doc.setDrawColor(200, 200, 200);
                    doc.line(20, pageHeight - 25, pageWidth - 20, pageHeight - 25);

                    // Save the PDF
                    const hotelName = this.hotel.name.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
                    doc.save(`Nexo_${hotelName}_Booking_Receipt.pdf`);
                }
            }
        }
    </script>
@endsection
