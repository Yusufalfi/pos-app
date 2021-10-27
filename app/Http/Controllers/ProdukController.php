<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // kategori select
        $kategori = kategori::all()->pluck('nama_kategori', 'id_kategori');
        return view('produk.index', compact('kategori'));
    }

    public function data()
    {
        // join table kategori dan produk (dengan id_kategori) dan (produk.id_kategori)
        $produk = Produk::leftJoin('kategori','kategori.id_kategori', 'produk.id_kategori')
            ->select('produk.*', 'nama_kategori')
            ->orderBy('kode_produk', 'asc')->get();
            return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('kode_produk', function($produk) {
                return '<span class="label label-success">' . $produk->kode_produk .  '</span> ';
            })
            ->addColumn('harga_beli', function($produk) {
                return format_uang($produk->harga_beli);
            })
            ->addColumn('harga_jual', function($produk) {
                return format_uang($produk->harga_jual);
            })
            ->addColumn('stok', function($produk) {
                return format_uang($produk->stok);
            })
            ->addColumn('aksi', function ($produk ) {
            return '
            <div class="btn-group">
                <button onclick="editForm(`'. route('produk.update', $produk->id_produk) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                <button onclick="deleteData(`'. route('produk.destroy', $produk->id_produk) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
            ';
        })
        ->rawColumns(['aksi', 'kode_produk'])
        ->make(true);
            // ->addColumn('action', 'users.action');
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
        //
        $produk = Produk::latest()->first() ?? new Produk();
        // tambah_nol_di_depan lihat di helper php
        $request['kode_produk'] = 'P'. tambah_nol_di_depan((int)$produk->id_produk +1, 6);
        $produk = Produk::create($request->all());
        // $produk = new Produk();
        // $produk->nama_produk = $request->nama_produk;
        // $produk->save();

        return response()->json('data berhasil di simpan', 200);
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
        $produk = Produk::find($id);

        return response()->json($produk);
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
        //
        $produk = Produk::find($id);
        $produk->update($request->all());

        return response()->json('data berhasil diupdate', 200);
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
        $produk = Produk::find($id);
        $produk->delete();

        return response()->json('null', 204);
    }
}
