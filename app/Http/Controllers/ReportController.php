<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;
use App\Exports\ReportExport;
use App\Exports\ResponseExport;
use App\Models\Response;



class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function exportPDF() { 
        // ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jangan gunakan pagination
        $data = Report::with('response')->get()->toArray();
        // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial 
        view()->share('reports',$data); 
        // panggil view blade yg akan dicetak pdf serta data yg akan digunakan
        $pdf = PDF::loadView('print', $data)->setPaper('a4', 'Landscape'); 
        // download PDF file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf'); 
        }
        
        public function createdPDF($id) { 
            // ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jangan gunakan pagination
            $data = Report::with('response')->where('id', $id)->firstOrFail()->toArray();
            // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial 
            view()->share('reports',$data); 
            // panggil view blade yg akan dicetak pdf serta data yg akan digunakan
            $pdf = PDF::loadView('print', $data)->setPaper('a4', 'Landscape'); 
            // download PDF file dengan nama tertentu
            $nameFile = 'pengaduan-' . $id . '.pdf';
            return $pdf->download($nameFile); 
            }

        public function createExcel()
            { 
            $file_name = 'nama_file_yang_diinginkan'.'.xlsx'; 
            return Excel::download(new ReportExport, $file_name); 
            }
            
        
            

    public function index()
    {
        //ASC : ascending -> terkecil ke terbesar 1-100/a-z
        //DESC : descending -> terbesar ke terkecil 100-1/z-a
        //orderBy untuk mengurutkan data
        //created_at nama kolom di database

        $reports = Report::orderBy('created_at', 'DESC')->simplePaginate(2);
        return view('index', compact('reports'));
    }

    public function data(Request $request)
    {
        $search = $request->search;
        $reports = Report::with('response')->where('nama', 'LIKE', '%' . $search .'%')->orderBy('created_at', 'DESC')->get();
        return view('data', compact('reports'));
    }
    
    public function dataPetugas(Request $request)
    {
        $search = $request->search;
        $reports = Report::with('response')->where('nama', 'LIKE', '%' . $search .'%')->orderBy('created_at', 'DESC')->get();
        return view('data_petugas', compact('reports'));
    }

    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $user = $request->only('email', 'password');

        if(Auth::attempt($user)){
        if(Auth::user()->role == 'admin'){
            return redirect()->route('data');
        }elseif(Auth::user()->role == 'petugas'){
            return redirect()->route('data.petugas');
        }
        }
        else{
            return redirect()->back()->with('gagal', 'Gagal Login, coba lagi!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'nama' => 'required',
            'no_telp' => 'required|numeric',
            'pengaduan' => 'required|min:5',
            'foto' => 'required|image|mimes:jpeg,jpg,png,svg',
        ]);

        //panggil folder tempat simpen gambar
        $path = public_path('assets/image/');
         //ambil file yg diupload di input yg name nya foto
         $image = $request->file('foto');
         //ubah nama file jadi random extensi
         $imgName = rand() . '.' . $image->extension();
         //pindahin gambar yg di upload dan udah di rename ke folder tadi
         $image->move($path, $imgName);

         Report::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'pengaduan' => $request->pengaduan,
            'foto' => $imgName,
        ]);

        return redirect()->route('home')->with('success', 'Berhasil menambahkan data baru');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Report::where('id', $id)->firstOrFail();
        $image = public_path('assets/image/'.$data['foto']);
        unlink($image);
        $data->delete();
        Response::where('report_id', $id)->delete();
        return redirect()->back();
    }
}
