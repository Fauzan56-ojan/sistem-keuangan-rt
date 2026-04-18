<h1>Daftar Iuran tidak fungsi</h1>

<table border="1">
<tr>
<th>ID</th>
<th>User</th>
<th>Bulan</th>
<th>Tahun</th>
<th>Nominaaaal</th>
<th>Status</th>
</tr>

@foreach($iuran as $row)
<tr>
<td>{{ $row->id }}</td>
<td>{{ $row->user->name }}</td>
<td>{{ $row->periode_bulan }}</td>
<td>{{ $row->periode_tahun }}</td>
<td>{{ $row->nominal }}</td>
<td>{{ $row->status }}</td>
</tr>
@endforeach

</table>