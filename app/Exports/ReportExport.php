<?php

namespace App\Exports;

use App\Models\Report;
// mengambil dari data base
use Maatwebsite\Excel\Concerns\FromCollection;
// mengatur nama-nama colum header di excelnya
use Maatwebsite\Excel\Concerns\WithHeadings;
// mengatur data yang dimunculkan tiap colum di excelnya
use Maatwebsite\Excel\Concerns\WithMapping;


class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // didalam sini boleh menyertakan perintah eloquent lain seperti where, all, dll
        return Report::with('response')->orderBy('created_at', 'DESC')->get();
    }
// mengatur nama-nama colum headers
public function headings(): array
{
    return [
        'ID',
        'NIK Pelapor',
        'Nama Pelapor',
        'No Telp Pelapor',
        'Tanggal Pelaporan',
        'Pengaduan',
        'Status',
        'Pesan',

    ];
}
// menagtur data yang ditampilkan per column di excelnya
// fungsinya seperti foreach. $item merupakan bagian as pada foreach
public function map($item): array
{
    return [
        $item->id,
        $item->nik,
        $item->nama,
        $item->no_telp,
        \Carbon\Carbon::parse($item->created_at)->format('j F, Y'),
        $item->pengaduan,
        $item->response ? $item->response['status'] : '-',
        $item->response ? $item->response['pesan'] : '-',

    ];
}

}