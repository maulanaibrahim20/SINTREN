@foreach ($laporanPalawijaPdf as $index => $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            Kecamatan: {{ $item->kecamatan->name }}<br>
            Desa: {{ $item->desa->name }}
        </td>
        <td>{{ $item->jenis_lahan }}</td>
        <td>{{ $item->date }}</td>
    </tr>
@endforeach
