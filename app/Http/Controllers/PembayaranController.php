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
                ->where('flag','just_arrived')
                ->orWhere('flag','gagal')
                ->orWhere('flag','processed')
                ->groupBy('tahun')
                ->pluck('tahun');
        echo $tahun->toJSON();
    }

    public function getMonth($pelanggan_id, $tahun){
        $bulans = Pembayaran::where('pelanggan_id',$pelanggan_id)
                ->where('tahun',$tahun)
                ->where('flag','just_arrived')
                ->orWhere('flag','gagal')
                ->orWhere('flag','processed')
                ->groupBy('bulan')->pluck('bulan');
        echo $bulans->toJSON();
    }

    public function getTotalInvoice($pelanggan_id,$tahun,$bulan){
        $total = Pembayaran::where('pelanggan_id',$pelanggan_id)
                ->where('tahun',$tahun)
                ->where('bulan',$bulan)
                ->where('flag','just_arrived')
                ->orWhere('flag','gagal')
                ->orWhere('flag','processed')
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
        $data = $request->all();
        if($data['jenis'] === 'success')
        {
            $pembayaran = Pembayaran::find($id);
            $pembayaran->flag = "completed";
            $pembayaran->save();
            return response()->json(['success'=>'berhasil']);
        }else{
            $pembayaran = Pembayaran::find($id);
            $pembayaran->flag = "gagal";
            $pembayaran->save();
            return response()->json(['success'=>'berhasil']);
        }
        
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
