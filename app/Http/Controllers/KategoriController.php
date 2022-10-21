<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriStoreRequest;

use App\Http\Requests\KategoriUpdateRequest;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Str;


class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(request()->ajax()) {
            return datatables()->of(Kategori::select('*'))
            ->addColumn('action', 'companies.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
            }
            return view('kategoris.index');





    }

    public function getKategoris(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'kategoris.action')

                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kategoris.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KategoriStoreRequest $request)
    {
        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('kategoris', 'public');
        }

        $kategori = Kategori::create([
            'name' => $request->name

        ]);

        if (!$kategori) {
            return redirect()->back()->with('error', 'Sorry, there a problem while creating kategori.');
        }
        return redirect()->route('kategoris.index')->with('success', 'Success, you kategori have been created.');
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit(Kategori $kategori)
    {
        return view('kategoris.edit')->with('kategori', $kategori);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(KategoriUpdateRequest $request, Kategori $kategori)
    {
        $kategori->name = $request->name;

        if (!$kategori->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating kategori.');
        }
        return redirect()->route('kategoris.index')->with('success', 'Success, your kategori have been updated.');
    }

    public function destroy($id)
    {


        Kategori::where('id', $id)->delete();

       return response()->json([
           'status' => true,
           'msg' => 'berhasil'
       ]);
    }



}
