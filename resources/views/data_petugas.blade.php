<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Masyarakat</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>
    <h2 class="title-table">Laporan Keluhan</h2>
<div style="display: flex; justify-content: center; margin-bottom: 30px">
    <form action="{{route('logout')}}" method="GET">
        @csrf
    <button class="submit" style="background-color: red">Logout</button>
</form>
    <div style="margin: 0 10px"><h3 style="font-size: 50px">|</h3></div>
    <form action="/" method="GET">
        @csrf
    <button class="submit">Home</button>
</form>
</div>
<div style="display: flex; justify-content: flex-end; align-items:center">
<form action="" method="GET">
    @csrf
    <input type="text" name="search" placeholder="cari berdasarkan nama.....">
    <button type="submit" class="btn-login" style="margin-top: -0.1px; margin-left:5px">Cari</button>
</form>
<div>
<form action="{{route('data.petugas')}}" method="GET" style="margin-top: -33px; margin-right:35px;margin-left:5px">
    @csrf
    <button class="submit">Refresh</button>
</form>
</div>
</div>

<div style="padding: 0 30px; margin-top:10px">
    <table>
        <thead>
        <tr>
            <th width="5%">No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Pengaduan</th>
            <th>Gambar</th>
            <th>Status Response</th>
            <th>Pesan Response</th>
           
        </tr>
        </thead>
        <tbody>
            @php
              $no = 1;  
            @endphp
            @foreach ($reports as $report)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$report['nik']}}</td>
                <td>{{$report['nama']}}</td>
                @php
                $telp= substr_replace($report->no_telp, "62", 0, 1)
                @endphp
                <td>{{$report['nama']}}</td>
                <td>{{$report['pengaduan']}}</td>
                <td>
                    <a href="../assets/image/{{$report->foto}}" target="_blank">
                    <img src="{{asset('assets/image/' . $report->foto)}}" width="120"></a>
                </td>
                <td>
                    @if ($report->response)
                    {{ $report->response['status']}}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if ($report->response)
                    {{ $report->response['pesan']}}
                    @else
                    -
                    @endif
                </td>
                <td style="display:flex; justify-content:center;">
                    <a href="{{route('response.edit', $report->id)}}" class="back-btn">Send Respone</a>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>