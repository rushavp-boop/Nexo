@extends('layouts.app')

@section('content')
<div class="space-y-6 md:space-y-8 pb-10 md:pb-20">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl sm:text-4xl font-bold italic text-stone-900 tracking-tighter uppercase">
                Medical Records
            </h2>
            <p class="text-amber-700 mt-2 font-medium italic">View all your uploaded prescriptions in one place</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('user.health') }}"
               class="inline-flex items-center justify-center px-5 py-3 border-2 border-stone-300 text-stone-900 font-bold italic rounded-xl hover:bg-stone-50 transition-all gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Health
            </a>
            <a href="{{ route('user.medical-records.create') }}"
               class="inline-flex items-center justify-center px-5 py-3 bg-stone-900 hover:bg-amber-700 text-white font-bold italic rounded-xl transition-all gap-2">
                <i class="fa-solid fa-plus"></i>
                Add Record
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border-2 border-green-200 bg-green-50 p-4">
            <p class="text-green-700 font-bold italic">{{ session('success') }}</p>
        </div>
    @endif

    @if ($records->isEmpty())
        <div class="bg-gradient-to-br from-white to-amber-50/30 border-2 border-amber-300 rounded-xl sm:rounded-2xl md:rounded-3xl lg:rounded-[3rem] shadow-md sm:shadow-lg p-10 text-center">
            <i class="fa-solid fa-file-medical text-5xl text-amber-700"></i>
            <p class="mt-4 text-lg font-bold italic text-stone-900">No medical records yet</p>
            <p class="mt-2 text-sm text-stone-600 font-medium">Start by uploading your first prescription.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($records as $record)
                <div class="bg-gradient-to-br from-white to-amber-50/30 border-2 border-amber-200 rounded-2xl p-5 sm:p-6 shadow-sm hover:shadow-lg hover:shadow-amber-700/10 transition-all">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="space-y-2">
                            <h3 class="text-xl font-bold italic text-stone-900">{{ $record->title }}</h3>
                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 font-bold italic uppercase tracking-widest">
                                    {{ $record->record_date ? $record->record_date->format('M d, Y') : 'Date not set' }}
                                </span>
                                <span class="px-3 py-1 rounded-full bg-stone-100 text-stone-700 font-bold italic uppercase tracking-widest">
                                    {{ strtoupper(pathinfo($record->original_file_name, PATHINFO_EXTENSION)) }}
                                </span>
                                <span class="px-3 py-1 rounded-full bg-stone-100 text-stone-700 font-bold italic uppercase tracking-widest">
                                    {{ number_format(($record->file_size ?? 0) / 1024, 1) }} KB
                                </span>
                            </div>
                            @if ($record->notes)
                                <p class="text-sm text-stone-700 font-medium italic">{{ $record->notes }}</p>
                            @endif
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ asset('storage/' . $record->prescription_file_path) }}"
                               target="_blank"
                               class="inline-flex items-center justify-center px-4 py-2 border-2 border-stone-300 text-stone-900 font-bold italic rounded-lg hover:bg-stone-50 transition-all gap-2">
                                <i class="fa-solid fa-eye"></i>
                                View
                            </a>
                            <a href="{{ asset('storage/' . $record->prescription_file_path) }}"
                               download="{{ $record->original_file_name }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-stone-900 hover:bg-amber-700 text-white font-bold italic rounded-lg transition-all gap-2">
                                <i class="fa-solid fa-download"></i>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
