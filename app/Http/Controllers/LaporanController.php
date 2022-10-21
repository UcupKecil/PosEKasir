<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF; //library pdf

class LaporanController extends Controller
{
    public function index(){
        //menampilkan halaman laporan
        return view('laporan.kwitansi');
    }

    public function export(){
        //mengambil data dan tampilan dari halaman laporan_pdf
        //data di bawah ini bisa kalian ganti nantinya dengan data dari database
        $data = PDF::loadview('laporan.laporan_pdf', ['data' => 'ini adalah contoh laporan PDF']);
        //mendownload laporan.pdf
    	return $data->download('laporan.pdf');
    }

    public function exportkwitansi(){
        //mengambil data dan tampilan dari halaman laporan_pdf
        //data di bawah ini bisa kalian ganti nantinya dengan data dari database
        $data = PDF::loadview('laporan.kwitansi', ['data' => 'ini adalah contoh laporan PDF']);
        //mendownload laporan.pdf
    	return $data->download('kwitansi.pdf');
    }
}
