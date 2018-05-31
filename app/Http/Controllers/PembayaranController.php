<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pembayaran;
use App\Pelanggan;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pembayarans = Pembayaran::all();
        $page = "Pembayaran";
        return view('pembayaran.view', compact('pembayarans','page'));
    }

    public function getYear($pelanggan_id){
        $tahun = Pembayaran::where('pelanggan_id',$pelanggan_id)
                ->where('status_bayar',0)
                ->groupBy('tahun')
                ->pluck('tahun');
        echo $tahun->toJSON();
    }

    public function getMonth($pelanggan_id, $tahun){
        $bulans = Pembayaran::where('pelanggan_id',$pelanggan_id)
                ->where('tahun',$tahun)
                ->where('status_bayar',0)
                ->groupBy('bulan')->pluck('bulan');
        echo $bulans->toJSON();
    }

    public function getTotalInvoice($pelanggan_id,$tahun,$bulan){
        $total = Pembayaran::where('pelanggan_id',$pelanggan_id)
                ->where('tahun',$tahun)
                ->where('bulan',$bulan)
                ->where('status_bayar',0)
                ->select('id', 'jumlah_tagihan')->get();
        echo $total->toJSON();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pelanggans = Pelanggan::all();
        $page = "Pembayaran";
        return view('pembayaran.form_add', compact('pelanggans','page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::find($id);
        $pembayaran->status_bayar = 1;
        $pembayaran->save();
        return redirect()->route('pembayaran.index')->with('success','Sukses melakukan pembayaran!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
