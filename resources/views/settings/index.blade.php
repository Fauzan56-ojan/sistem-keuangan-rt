<x-app-layout>
    <x-slot name="header">
        <h2>Setting</h2>
    </x-slot>

    <div class="p-4 space-y-2">

        <a href="{{ route('settings.profile') }}">
            Profile
        </a>
        <br>

        <a href="{{ route('settings.migrasi') }}">
            Migrasi
        </a>
        <br>

        <a href="{{ route('settings.nominal') }}">
            Kelola Iuran
        </a>
        <br>

        <a href="{{ route('settings.generate') }}">
            Generate Iuran
        </a>

    </div>
</x-app-layout>