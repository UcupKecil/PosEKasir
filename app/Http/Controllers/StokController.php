<?php

namespace App\Http\Controllers;

use App\Http\Requests\StokStoreRequest;
use App\Http\Requests\StokUpdateRequest;
use App\Http\Resources\StokResource;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stoks = new Stok();
        if ($request->search) {
            $stoks = $stoks->where('name', 'LIKE', "%{$request->search}%" )
            ->orWhere('kategori_id','like',"%{$request->search}%");

        }
        if ($request->kategori) {
            $stoks = $stoks->where('kategori_id', '=', "{$request->kategori}");
        }
        $stoks = $stoks->latest()->paginate(10);
        if (request()->wantsJson()) {
            return StokResource::collection($stoks);
        }
        return view('stoks.index')->with('stoks', $stoks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stoks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StokStoreRequest $request)
    {
        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('stoks', 'public');
        }

        $stok = Stok::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_path,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'status' => $request->status
        ]);

        if (!$stok) {
            return redirect()->back()->with('error', 'Sorry, there a problem while creating stok.');
        }
        return redirect()->route('stoks.index')->with('success', 'Success, you stok have been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function show(Stok $stok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function edit(Stok $stok)
    {
        return view('stoks.edit')->with('stok', $stok);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function update(StokUpdateRequest $request, Stok $stok)
    {
        $stok->name = $request->name;
        $stok->description = $request->description;
        $stok->barcode = $request->barcode;
        $stok->price = $request->price;
        $stok->quantity = $request->quantity;
        $stok->status = $request->status;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($stok->image) {
                Storage::delete($stok->image);
            }
            // Store image
            $image_path = $request->file('image')->store('stoks', 'public');
            // Save to Database
            $stok->image = $image_path;
        }

        if (!$stok->savestok()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating stok.');
        }
        return redirect()->route('stoks.index')->with('success', 'Success, your stok have been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stok $stok)
    {
        if ($stok->image) {
            Storage::delete($stok->image);
        }
        $stok->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
