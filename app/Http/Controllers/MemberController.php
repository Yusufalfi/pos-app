<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('member.index');
    }

    public function data()
    {
        $member = Member::orderBy('kode_member', 'asc')->get();
        return datatables()
        ->of($member)
        ->addIndexColumn()
        ->addColumn('kode_member', function($member) {
            return '<span class="label label-success">' . $member->kode_member .  '</span> ';
        })
        ->addColumn('aksi', function ($member ) {
            return '
            <div class="btn-group">
                <button onclick="editForm(`'. route('member.update', $member->id_member) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                <button onclick="deleteData(`'. route('member.destroy', $member->id_member) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
            ';
        })
        ->rawColumns(['aksi', 'kode_member'])
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
        //generet kode member // (00001)
        // ambil data terakhir
        $member = Member::latest()->first();
        // cek kode membernya ada tidak? kalo ada tambah 1  ??kalo null set 1
        // kode_member+1 agar unique
        $kode_member = (int) $member->kode_member+1  ?? 1;

        // dd($kode_member);


        // $member = Member::create($request->all());
        $member = new Member();
        // nol nya 5, kode_member tambah 1 agar unique
        $member->kode_member = tambah_nol_di_depan($kode_member, 5) ;
        $member->nama = $request->nama;
        $member->telepon = $request->telepon;
        $member->alamat = $request->alamat;
        $member->save();

        return response()->json('data berhasil di simpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $member = Member::find($id);

        return response()->json($member);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id 
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
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $member = Member::find($id)->update($request->all());
        // $member->nama = $request->nama;
        // $member->telepon = $request->telepon;
        // $member->alamat = $request->alamat;
        // $member->update();

        return response()->json('data berhasil diupdate', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $member = Member::find($id);
        $member->delete();

        return response()->json('null', 204);
    }
}
