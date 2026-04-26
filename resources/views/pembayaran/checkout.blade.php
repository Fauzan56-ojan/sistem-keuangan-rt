<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Konfirmasi Pembayaran
        </h2>
    </x-slot>

    <div class="p-8 max-w-6xl mx-auto">
        <div class="flex flex-col mb-8">
            <h2 class="font-headline font-bold text-2xl text-emerald-900 leading-tight">Konfirmasi Pembayaran</h2>
            <p class="text-sm text-gray-500 mt-1">Periksa detail sebelum melanjutkan pembayaran</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="font-headline text-lg font-bold text-gray-800">Detail Transaksi</h3>
                        </div>
                        <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-emerald-600 text-2xl">receipt_long</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-1">
                            <span class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                <span class="material-symbols-outlined text-base">person</span>
                                Nama
                            </span>
                            <span class="font-semibold text-gray-800 text-sm text-right">{{ $iuran->user->name }}</span>
                        </div>

                        <div class="h-px bg-gray-50 w-full"></div>

                        <div class="flex items-center justify-between py-1">
                            <span class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                <span class="material-symbols-outlined text-base">home</span>
                                Nomor Rumah
                            </span>
                            <span class="font-semibold text-gray-800 text-sm text-right">{{ $iuran->user->nomor_rumah }}</span>
                        </div>

                        <div class="h-px bg-gray-50 w-full"></div>

                        <div class="flex items-center justify-between py-1">
                            <span class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                <span class="material-symbols-outlined text-base">calendar_month</span>
                                Bulan Layanan
                            </span>
                            <span class="font-semibold text-gray-800 text-sm text-right">
                                {{ \Carbon\Carbon::create()->month($iuran->periode_bulan)->translatedFormat('F') }} {{ $iuran->periode_tahun }}
                            </span>
                        </div>

                        <div class="h-px bg-gray-50 w-full"></div>

                        @if($metode == 'tunai')
                            <div class="flex items-center justify-between py-1">
                                <label for="tanggal" class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base">event_available</span>
                                    Tanggal Bayar
                                </label>
                                <div class="w-auto">
                                    <input type="date" id="tanggal" value="{{ date('Y-m-d') }}" 
                                        class="text-sm rounded-lg border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-1 px-2 bg-gray-50 border-none text-right font-semibold text-gray-800">
                                </div>
                            </div>
                            <div class="h-px bg-gray-50 w-full"></div>
                        @endif

                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                <span class="material-symbols-outlined text-base">payments</span>
                                Total Nominal
                            </span>
                            <span class="font-bold text-lg text-emerald-700 text-right">
                                Rp {{ number_format($iuran->nominal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-3">
                        <button id="pay-button" class="w-full sm:w-auto bg-emerald-600 text-white font-semibold px-8 py-2.5 rounded-lg hover:bg-emerald-700 hover:shadow-md transition-all active:scale-95 flex items-center justify-center gap-2 text-sm">
                            <span>Bayar Sekarang</span>
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            @if($metode == 'online')
            <div class="lg:col-span-5">
                <div class="p-6 bg-blue-50/50 rounded-xl border border-blue-100">
                    <h4 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">info</span> 
                        Informasi Pembayaran
                    </h4>
                    <p class="text-sm text-blue-800/80 leading-relaxed">
                        Pembayaran akan diproses secara otomatis melalui gerbang pembayaran <strong>Midtrans</strong>. 
                        Anda dapat memilih metode transfer bank, e-wallet, atau metode pembayaran lainnya setelah menekan tombol bayar.
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>


    <script>let metode = "{{$metode}}";</script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <script>
        document.getElementById('pay-button').onclick = function() {
            if (metode == "online") {
                fetch("/get-snap-token/{{ $iuran->id }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        snap.pay(data.token, {
                            onSuccess: function(result) {
                                alert("Pembayaran berhasil");
                                location.href = "/warga/{{ $iuran->user->id }}/iuran";
                            },
                            onPending: function(result) {
                                alert("Menunggu pembayaran");
                                location.href = "/warga/{{ $iuran->user->id }}/iuran";
                            },
                            onError: function(result) {
                                alert("Pembayaran gagal");
                            },
                        });
                    });
            } else {
                let tanggalInput = document.getElementById('tanggal');
                let tanggal = tanggalInput ? tanggalInput.value : null;

                fetch("/bayar-tunai/{{ $iuran->id }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            tanggal: tanggal
                        })
                    })
                    .then(() => {
                        alert("Pembayaran tunai berhasil");
                        location.href = "/warga/{{ $iuran->user->id }}/iuran";
                    });
            }
        };
    </script>
</x-app-layout>