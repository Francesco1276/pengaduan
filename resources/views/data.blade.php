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
    <button type="submit" class="btn-login" style="margin-top: -0.2px; margin-left:10px">Cari</button>
</form>
<div>
<form action="{{route('data')}}" method="GET" style="margin-top: -33px; margin-right:30px;margin-left:5px">
    @csrf
    <button class="submit">Refresh</button>
</form>
</div>
<div>
    <form action="{{route('export.pdf')}}" method="GET" style="margin-top: -33px; margin-right:20px;margin-left:-25px">
        @csrf
        <button class="submit">Cetak PDF ALL</button>
    </form>
    </div>
    <div>
        <form action="{{route('export.excel')}}" method="GET" style="margin-top: -33px; margin-right:20px;margin-left:-15px">
            @csrf
            <button class="submit">Export to Excel</button>
        </form>
        </div>
</div>

<div style="padding: 0 30px">
    <table>
        <thead>
        <tr>
            <th width="5%">No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Pengaduan</th>
            <th>Gambar</th>
            <th>Status</th>
            <th>Pesan</th>
            <th>Aksi</th>
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

                @php
                if ($report->response){
                $pesanWA = 'halo' . $report->nama . '| pengaduan anda di' . $report->response['status'] . '. Berikut pesan untuk Anda : ' . $report->response['pesan'];
            }else{
                $pesanWA = 'Belum ada response';
            }
                @endphp

                <td><a href="https://wa.me/{{$telp}}?text={{$pesanWA}}" target="_blank">{{$telp}}</a></td>
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
                <td>
                    <div>
                    <form action="{{route('delete', $report->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="submit" style="background-color: red">Hapus</button>
                    </form>
                    </div>
                    <div>
                        <form action="{{route('created.pdf', $report->id)}}" method="GET" style="margin-top: -33px; margin-right:20px;margin-left:-25px">
                            @csrf
                            <button class="submit">Print</button>
                        </form>
                        </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>