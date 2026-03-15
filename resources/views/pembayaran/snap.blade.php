<script src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>

snap.pay('{{ $snapToken }}', {

onSuccess: function(result){
alert("Pembayaran berhasil");
location.href="/iuran";
},

onPending: function(result){
alert("Menunggu pembayaran");
},

onError: function(result){
alert("Pembayaran gagal");
}

});



</script>