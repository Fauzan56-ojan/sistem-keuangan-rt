<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Iuran Tahun {{ $tahun }}
        </h2>
    </x-slot>
<div class="p-6">
@if(session('error'))
<script>
alert("{{ session('error') }}");
</script>
@endif
<form method="GET">
    <select name="tahun" onchange="this.form.submit()">
        @foreach ($tahunList as $th)
            <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>
                {{ $th }}
            </option>
        @endforeach
    </select>
</form>
<table border="1" cellpadding="5">

<tr>
<th>Bulan</th>
<th>Tahun</th>
<th>Status</th>
<th>Nominal</th>
<th>Aksi</th>
</tr>

@foreach($iuran as $row)

<tr>

<td>
{{ \Carbon\Carbon::create()->month($row->periode_bulan)->translatedFormat('F') }}
</td>
<td>{{ $row->periode_tahun }}</td>
<td>
@if($row->status == 'paid')
<span style="color:green">Lunas</span>
@else
<span style="color:red">Belum Lunas</span>
@endif
</td>

<td>
Rp {{ number_format($row->nominal,0,',','.') }}
</td>

<td>

@if($row->status == 'pending')

<a href="/checkout/{{ $row->id }}/tunai">
<button>Tunai</button>
</a>
<a href="/checkout/{{ $row->id }}/online">
<button>Online</button>
</a>

@else
Lunas
@endif

</td>

</tr>

@endforeach

</table>
    </div>
</x-app-layout>