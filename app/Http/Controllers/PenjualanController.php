<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenjualanStoreRequest;
use App\Http\Requests\PenjualanUpdateRequest;
use App\Http\Resources\PenjualanResource;
use App\Models\Penjualan;
use App\Models\Stok;
use App\Models\PenjualanItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Str;
use PDF;



class PenjualanController extends Controller
{
    public function index(Request $request) {
        $penjualans = new Penjualan();
        if($request->start_date) {
            $penjualans = $penjualans->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $penjualans = $penjualans->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $penjualans = $penjualans->with(['items', 'pembayarans', 'customer'])->latest()->paginate(10);

        $total = $penjualans->map(function($i) {
            return $i->total();
        })->sum();
        $receivedAmount = $penjualans->map(function($i) {
            return $i->receivedAmount();
        })->sum();

        return view('penjualans.index', compact('penjualans', 'total', 'receivedAmount'));
    }

    public function cetak(Request $request) {

    }

    public function cetaknotacustomer($id)
    {
    $penjualan = Penjualan::select('*')
                ->where('id', '=', $id)
                ->get();
    $detail = PenjualanItem::leftJoin('products', 'products.id', '=', 'penjualan_items.product_id')
    ->select([
        'penjualan_items.quantity as pcs','penjualan_items.price as harga','products.*'
    ])
    ->where('penjualan_id', '=', $id)
    ->get();


    $pembayarans = DB::table('pembayarans')
                ->join('penjualans', 'penjualans.id', '=', 'pembayarans.penjualan_id')
                ->select(DB::raw('SUM(pembayarans.amount) as totalbayar'))
                ->groupBy('pembayarans.penjualan_id')
                ->where('pembayarans.penjualan_id', '=', $id)
                ->get();

    $totals = DB::table('penjualan_items')
                ->join('products', 'products.id', '=', 'penjualan_items.product_id')
                ->select(DB::raw('SUM(penjualan_items.price) as totalharga'))
                ->groupBy('penjualan_id')
                ->where('penjualan_items.penjualan_id', '=', $id)
                ->get();

    $penjualans = Penjualan::find($id);

    $setting = Setting::find($id);

    $no = 0;

    $pdf = PDF::loadView('penjualans.cetaknotacustomer', compact('detail', 'penjualan', 'setting', 'no','totals','pembayarans'));
    $pdf->setPaper(array(0,0,200,600), 'potrait');
    return $pdf->stream('Laporan-Data-Santri.pdf');
    }




    public function show(Penjualan $penjualan)
    {
        $santri = Penjualan::select('*')
                ->get();

    $pdf = PDF::loadView('penjualans.cetaksantri', ['santri' => $santri]);
    return $pdf->stream('Laporan-Data-Santri.pdf');
    }



    public function getPenjualan(Request $request)
    {

        if ($request->ajax()) {

            $data = DB::table('customers')->orderBy('first_name');


            return Datatables::of($data)
            ->addColumn(
                'total',
                function($row) {
                    $hasPayment = DB::table('penjualan_items')
                        ->join('penjualans','penjualans.id','=','penjualan_items.penjualan_id')
                        ->select([
                            'penjualan_items.*'
                        ])
                        ->where('penjualans.id', $row->id)
                        ->get();

                    if(count($hasPayment) > 0) {
                        $total = 0;

                        foreach($hasPayment as $row) {
                            $total += $row->price;
                        }

                        return 'Rp. '.number_format($total);
                    }

                    return 'Rp. 0';
                }
            )

            ->addColumn('action', 'penjualans.action')
            ->addIndexColumn()
            ->rawColumns(['action'])
                ->make(true);
        }

    }

    public function store(PenjualanStoreRequest $request)
    {
        $penjualan = Penjualan::create([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user()->id,
        ]);

        $cart = $request->user()->cart()->get();
        foreach ($cart as $item) {
            $penjualan->items()->create([
                'price' => $item->price * $item->pivot->quantity,
                'quantity' => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);
            $item->quantity = $item->quantity - $item->pivot->quantity;
            $item->save();
        }


        foreach ($cart as $historystoks) {
            $penjualan->historystoks()->create([
                'stok' => -($historystoks->pivot->quantity),
                'product_id' => $historystoks->id,
                'user_id' => $request->user()->id,
            ]);

            $stok = Stok::where('product_id', '=', $historystoks->id)->first();
            $stok->current_stok -= $historystoks->pivot->quantity;
            $stok->update();

        }



        $request->user()->cart()->detach();
        $penjualan->pembayarans()->create([
            'amount' => $request->amount,
            'user_id' => $request->user()->id,
        ]);
        return 'success';
    }
}
