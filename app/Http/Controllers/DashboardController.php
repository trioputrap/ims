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
        $html="";
        foreach($pembayarans as $no => $pembayaran):
            if($pembayaran->flag == 'just_arrived'):
                $badge='<span class="badge badge-danger">Belum Bayar</span>';
            elseif($pembayaran->flag == 'processed'):
                $badge='<span class="badge badge-warning">Sedang Di Proses</span>';
            elseif($pembayaran->flag == 'gagal'):
                $badge='<span class="badge badge-primary">Gagal</span>';
            else: 
                $badge='<span class="badge badge-success">Lunas</span>';
            endif;

            $html.="
            <tr>
                <td>".($no+1)."</td>
                <td>".$pembayaran->pelanggan->no_rek."</td>
                <td>".$pembayaran->pelanggan->nama."</td>
                <td>".\DateTime::createFromFormat('!m', $pembayaran->bulan)->format('F')."</td>
                <td>".$pembayaran->tahun."</td>
                <td>Rp".number_format($pembayaran->jumlah_tagihan)."</td>
                <td>".$badge."</td>
            </tr>";
        endforeach;
        echo $html;
    }
}
