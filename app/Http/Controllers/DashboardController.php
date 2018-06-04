<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pembayaran;
use App\Pelanggan;

class DashboardController extends Controller
{
    public function index()
    {
        $total_tagihan = Pembayaran::where('bulan', date('m'))->sum("jumlah_tagihan");
        $total_bayar = Pembayaran::where('bulan', date('m'))
        ->where('flag', 'completed')->sum("jumlah_tagihan");
        $total_pelanggan = Pelanggan::count();
        $total_bot = Pelanggan::where('chat_id',"!=","")->count();

        $pembayarans = Pembayaran::where('bulan', date('m'))
        ->orWhere('flag','just_arrived')
        ->orWhere('flag','gagal')
        ->orWhere('flag','processed')
        ->get();
        $page = "Pembayaran";
        return view('dashboard', compact('page','pembayarans','total_tagihan','total_bayar','total_pelanggan','total_bot'));
    }

    public function getAll(){
        $pembayarans = Pembayaran::where('bulan', date('m'))
        ->orWhere('flag','just_arrived')
        ->orWhere('flag','gagal')
        ->orWhere('flag','processed')
        ->get();
    }
}
