<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Migrasi Data Iuran
        </h2>
    </x-slot>

    <div class="p-6 max-w-xl">

        {{-- Warning --}}
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            Migrasi data lama
        </div>

        {{-- Error --}}
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-200 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Success --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('settings.migrasi.proses') }}" method="POST">
            @csrf

            {{-- Tahun --}}
            <div class="mb-4">
                <label class="block mb-1">Tahun</label>
                <input 
                    type="number" 
                    name="tahun" 
                    class="w-full border rounded p-2"
                    placeholder="Contoh: 2026"
                    required
                >
            </div>

            {{-- Sampai Bulan --}}
            <div class="mb-4">
                <label class="block mb-1">Sampai Bulan</label>
                <select name="bulan" class="w-full border rounded p-2" required>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">
                            Bulan {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Button --}}
            <button 
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Generate Migrasi
            </button>

        </form>

    </div>
</x-app-layout>