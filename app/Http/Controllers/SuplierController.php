<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuplierStoreRequest;
use App\Models\Suplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->wantsJson()) {
            return response(
                Suplier::all()
            );
        }
        $supliers = Suplier::latest()->paginate(10);
        return view('supliers.index')->with('supliers', $supliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SuplierStoreRequest $request)
    {
        $avatar_path = '';

        if ($request->hasFile('avatar')) {
            $avatar_path = $request->file('avatar')->store('supliers', 'public');
        }

        $suplier = Suplier::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'avatar' => $avatar_path,
            'user_id' => $request->user()->id,
        ]);

        if (!$suplier) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while creating suplier.');
        }
        return redirect()->route('supliers.index')->with('success', 'Success, your suplier have been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Suplier  $suplier
     * @return \Illuminate\Http\Response
     */
    public function show(Suplier $suplier)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Suplier  $suplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Suplier $suplier)
    {
        return view('supliers.edit', compact('suplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Suplier  $suplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Suplier $suplier)
    {
        $suplier->first_name = $request->first_name;
        $suplier->last_name = $request->last_name;
        $suplier->email = $request->email;
        $suplier->phone = $request->phone;
        $suplier->address = $request->address;

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($suplier->avatar) {
                Storage::delete($suplier->avatar);
            }
            // Store avatar
            $avatar_path = $request->file('avatar')->store('supliers', 'public');
            // Save to Database
            $suplier->avatar = $avatar_path;
        }

        if (!$suplier->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating suplier.');
        }
        return redirect()->route('supliers.index')->with('success', 'Success, your suplier have been updated.');
    }

    public function destroy(Suplier $suplier)
    {
        if ($suplier->avatar) {
            Storage::delete($suplier->avatar);
        }

        $suplier->delete();

       return response()->json([
           'success' => true
       ]);
    }
}
