<?php

namespace App\Http\Controllers;

use App\Models\Suplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\SuplierStoreRequest;
use App\Http\Requests\SuplierUpdateRequest;

class SuplierController extends Controller
{

    public function index() {
        if(request()->ajax()) {
            return datatables()->of(Suplier::select('*'))
            ->addColumn('action', 'companies.action')
            ->rawColumns(['action'])
            ->addIndexColumn()->make(true);
        } return view('supliers.index');
    }

    public function getSupliers(Request $request)
    {
        if ($request->ajax()) {
            $data = Suplier::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'supliers.action')
                ->rawColumns(['action'])
                ->make(true);
            }
    }

    public function create()
    {
        return view('supliers.create');
    }

    public function store(SuplierStoreRequest $request)
    {

        $suplier = Suplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone

        ]);

        if (!$suplier) {
            return redirect()->back()->with('error', 'Sorry, there a problem while creating suplier.');
        }
        return redirect()->route('supliers.index')->with('success', 'Success, you suplier have been created.');
    }

    public function show(Suplier $suplier)
    {

    }

    public function edit(Suplier $suplier)
    {
        return view('supliers.edit')->with('suplier', $suplier);
    }

    public function update(SuplierUpdateRequest $request, Suplier $suplier)
    {
        $suplier->name = $request->name;
        $suplier->address = $request->address;
        $suplier->phone = $request->phone;

        if (!$suplier->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating supplier.');
        }
        return redirect()->route('supliers.index')->with('success', 'Success, your supplier have been updated.');
    }

    public function destroy($id)
    {


        Suplier::where('id', $id)->delete();

       return response()->json([
           'status' => true,
           'msg' => 'berhasil'
       ]);
    }
}
