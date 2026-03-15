<h2>Konfirmasi Pembayaran</h2>

<p>Nama: {{ $iuran->user->name }}</p>
<p>Rumah: {{ $iuran->user->nomor_rumah }}</p>
<p>Bulan: {{ \Carbon\Carbon::create()->month($iuran->periode_bulan)->translatedFormat('F') }}</p>
<p>Nominal: Rp {{ number_format($iuran->nominal,0,',','.') }}</p>

<button id="pay-button">Bayar Sekarang</button>
<script>let metode = "{{$metode}}";</script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function(){

if(metode == "online"){

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

onSuccess: function(result){
alert("Pembayaran berhasil");
location.href="/warga/{{ $iuran->user->id }}/iuran";
},

onPending: function(result){
alert("Menunggu pembayaran");
location.href="/warga/{{ $iuran->user->id }}/iuran";
},

onError: function(result){
alert("Pembayaran gagal");
},

});

});

}else{

fetch("/bayar-tunai/{{ $iuran->id }}", {
method: "POST",
headers: {
"X-CSRF-TOKEN": "{{ csrf_token() }}"
}
})
.then(() => {

alert("Pembayaran tunai berhasil");
location.href="/warga/{{ $iuran->user->id }}/iuran";

});

}

};
</script>
{{-- 

// document.getElementById('pay-button').onclick = function(){

// fetch("/get-snap-token/{{ $iuran->id }}", {
// method: "POST",
// headers: {
// "X-CSRF-TOKEN": "{{ csrf_token() }}",
// "Content-Type": "application/json"
// }
// })
// .then(response => response.json())
// .then(data => {

// snap.pay(data.token, {

// onSuccess: function(result){
// alert("Pembayaran berhasil");
// location.href="/warga/{{ $iuran->user->id }}/iuran";
// },

// onPending: function(result){
// alert("Menunggu pembayaran");
// location.href="/warga/{{ $iuran->user->id }}/iuran";
// },

// onError: function(result){
// alert("Pembayaran gagal");
// },

// });

// });

// };

// </sc>ript> --}}