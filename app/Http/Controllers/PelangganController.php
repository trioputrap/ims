<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pelanggan;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pelanggans = Pelanggan::all();
        $page = "Pelanggan";
        return view('pelanggan.view', compact('pelanggans','page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = "Pelanggan";
        return view('pelanggan.form_add',compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        Pelanggan::create($data);
        return redirect()->route('pelanggan.index')->with('success','Sukses menambah pelanggan!');
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
        $pelanggan = Pelanggan::find($id);
        $page = "Pelanggan";
        return view('pelanggan.form_edit',compact('page','pelanggan'));
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
        $pelanggan = Pelanggan::find($id);
        $pelanggan->nik = $request->nik;
        $pelanggan->no_rek = $request->no_rek;
        $pelanggan->nama = $request->nama;
        $pelanggan->telp = $request->telp;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->chat_id = $request->chat_id;
        $pelanggan->save();
        return redirect()->route('pelanggan.index')->with('success','Sukses mengedit pelanggan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pelanggan::destroy($id);
        return redirect()->route('pelanggan.index')->with('success','Sukses menghapus pelanggan!');
    }
}
