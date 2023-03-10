<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Data Pengaduan</title>
</head>
<body>
    <h2 style="text-align: center; margin-bottom: 20px">Data Keseluruhan Pengaduan</h2>
    <table style="100%">
        <tr>
            <th>No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>No Telp</th>
            <th>Tanggal</th>
            <th>Pengaduan</th>
            <th>Gambar</th>
            <th>Status</th>
            <th>Pesan</th>
        </tr>
        @php $no = 1; @endphp
        @foreach ($reports as $report)
        <tr>
            <td>{{$no++}}</td>
            <td>{{$report['nik']}}</td>
            <td>{{$report['nama']}}</td>
            <td>{{$report['no_telp']}}</td>
            <td>{{\Carbon\Carbon::parse($report['created_at'])->format('j F, Y') }}</td>
            <td>{{$report['pengaduan']}}</td>
            <td>
                <img src="assets/image/{{$report['foto']}}" width="80">
            </td>
            <td>
                @if ($report['response'])
                {{ $report['response']['status']}}
                @else
                -
                @endif
            </td>
            <td>
                @if ($report['response'])
                {{ $report['response']['pesan']}}
                @else
                -
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>