<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Keuangan RT
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-3 gap-4">

                <div class="bg-white p-4 shadow rounded">
                    <h4>Total Pemasukan</h4>
                    <p>Rp {{ number_format($totalPemasukan) }}</p>
                </div>

                <div class="bg-white p-4 shadow rounded">
                    <h4>Total Pengeluaran</h4>
                    <p>Rp {{ number_format($totalPengeluaran) }}</p>
                </div>

                <div class="bg-white p-4 shadow rounded">
                    <h4>Saldo</h4>
                    <p>Rp {{ number_format($saldo) }}</p>
                </div>

                <div class="bg-white p-4 shadow rounded">
                    <h4>Total Tunggakan</h4>
                    <p>Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>