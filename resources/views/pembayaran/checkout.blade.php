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

    <div id="loading" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white px-6 py-5 rounded-2xl shadow-lg text-center">
            <div class="w-10 h-10 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
            <p class="text-sm font-semibold text-gray-700">Memproses pembayaran...</p>
        </div>
    </div>

    <div id="success-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-lg text-center w-72">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-100 flex items-center justify-center">
                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <h3 class="font-bold text-lg text-gray-800">Pembayaran Berhasil</h3>
            <p class="text-sm text-gray-500 mt-1 mb-4">Transaksi berhasil diproses</p>

            <button id="ok-btn"
                class="w-full bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700">
                OK
            </button>
        </div>
    </div>

    <div id="pending-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-lg text-center w-72">
            
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-yellow-100 flex items-center justify-center">
    <span class="material-symbols-outlined text-yellow-600 text-3xl">info</span>
</div>

            <h3 class="font-bold text-lg text-gray-800">Menunggu Pembayaran</h3>
            <p class="text-sm text-gray-500 mt-1 mb-4">Silakan selesaikan pembayaran Anda</p>

            <button id="pending-ok"
                class="w-full bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600">
                OK
            </button>
        </div>
    </div>

    <div id="error-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-lg text-center w-72">
            
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                <span class="text-red-600 text-3xl">✕</span>
            </div>

            <h3 class="font-bold text-lg text-gray-800">Pembayaran Gagal</h3>
            <p class="text-sm text-gray-500 mt-1 mb-4">Silakan coba lagi</p>

            <button id="error-ok"
                class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                OK
            </button>
        </div>
    </div>


    <script>let metode = "{{$metode}}";</script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <script>
        document.getElementById('pay-button').onclick = function() {
            showLoading();
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
                                hideLoading();
                                document.getElementById('success-modal').classList.remove('hidden');
                            },

                            onPending: function(result) {
                                hideLoading();
                                document.getElementById('pending-modal').classList.remove('hidden');
                            },

                            onError: function(result) {
                                hideLoading();
                                document.getElementById('error-modal').classList.remove('hidden');
                            },

                            onClose: function() {
                                hideLoading();
                            }
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
                        document.getElementById('success-modal').classList.remove('hidden');
                    });
            }
        };

        function showLoading() {
            document.getElementById('loading').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loading').classList.add('hidden');
        }

        // sukses
        document.getElementById('ok-btn').onclick = function() {
            location.href = "/warga/{{ $iuran->user->id }}/iuran";
        };

        // pending
        document.getElementById('pending-ok').onclick = function() {
            location.href = "/riwayat";
        };

        // error
        document.getElementById('error-ok').onclick = function() {
            document.getElementById('error-modal').classList.add('hidden');
        };
    </script>
</x-app-layout>