<x-app-layout>
    <x-slot name="header">
        <h2>Generate Iuran</h2>
    </x-slot>

    <form action="{{ route('iuran.generate') }}" method="POST">
        @csrf
        <input type="number" name="tahun" value="{{ date('Y') + 1 }}" required>
        <button type="submit">Generate Iuran</button>
    </form>
</x-app-layout>