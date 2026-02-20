@extends('layouts.app')

@section('content')
<div class="space-y-6 md:space-y-8 pb-10 md:pb-20">
    <div>
        <h2 class="text-3xl sm:text-4xl font-bold italic text-stone-900 tracking-tighter uppercase">
            Add Medical Record
        </h2>
        <p class="text-amber-700 mt-2 font-medium italic">Upload and securely store your prescription files</p>
    </div>

    <div class="bg-gradient-to-br from-white to-amber-50/30 border-2 border-amber-300 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3rem] shadow-md sm:shadow-lg p-6 sm:p-8 md:p-10">
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border-2 border-red-200 bg-red-50 p-4">
                <p class="font-bold italic text-red-700 mb-2">Please fix the following:</p>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.medical-records.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold italic text-stone-700 uppercase tracking-widest mb-2">Record Title</label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="e.g. Blood Pressure Follow-up Prescription"
                    class="w-full px-4 py-3 border-2 border-stone-300 rounded-xl focus:border-amber-700 focus:ring-2 focus:ring-amber-300 font-medium"
                    required>
            </div>

            <div>
                <label class="block text-xs font-bold italic text-stone-700 uppercase tracking-widest mb-2">Record Date</label>
                <input
                    type="date"
                    name="record_date"
                    value="{{ old('record_date') }}"
                    class="w-full px-4 py-3 border-2 border-stone-300 rounded-xl focus:border-amber-700 focus:ring-2 focus:ring-amber-300 font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold italic text-stone-700 uppercase tracking-widest mb-2">Prescription File</label>
                <input
                    type="file"
                    name="prescription"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="w-full px-4 py-3 border-2 border-stone-300 rounded-xl bg-white focus:border-amber-700 focus:ring-2 focus:ring-amber-300 font-medium"
                    required>
                <p class="mt-2 text-xs text-stone-600 font-medium">Allowed: PDF, JPG, JPEG, PNG • Max size: 5MB</p>
            </div>

            <div>
                <label class="block text-xs font-bold italic text-stone-700 uppercase tracking-widest mb-2">Notes (Optional)</label>
                <textarea
                    name="notes"
                    rows="4"
                    placeholder="Doctor name, dosage notes, follow-up reminders..."
                    class="w-full px-4 py-3 border-2 border-stone-300 rounded-xl focus:border-amber-700 focus:ring-2 focus:ring-amber-300 font-medium resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <a href="{{ route('user.health') }}"
                   class="inline-flex items-center justify-center px-5 py-3 border-2 border-stone-300 text-stone-900 font-bold italic rounded-xl hover:bg-stone-50 transition-all gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Health
                </a>
                <a href="{{ route('user.medical-records.index') }}"
                   class="inline-flex items-center justify-center px-5 py-3 border-2 border-stone-300 text-stone-900 font-bold italic rounded-xl hover:bg-stone-50 transition-all">
                    Back to Records
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center px-5 py-3 bg-stone-900 hover:bg-amber-700 text-white font-bold italic rounded-xl transition-all gap-2">
                    <i class="fa-solid fa-upload"></i>
                    Upload Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
