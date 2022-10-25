<?php

namespace App\Http\Controllers;

use App\Http\Requests\PembelianStoreRequest;
use App\Http\Requests\PembelianUpdateRequest;
use App\Http\Resources\PembelianResource;
use App\Models\Pembelian;
use App\Models\PembelianItem;
use App\Models\HistoryStok;
use App\Models\Stok;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Str;
use PDF;



class PembelianController extends Controller
{
    public function index(Request $request) {
        $pembelians = new Pembelian();
        if($request->start_date) {
            $pembelians = $pembelians->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $pembelians = $pembelians->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $pembelians = $pembelians->with(['items', 'pengeluarans', 'suplier'])->latest()->paginate(10);

        $total = $pembelians->map(function($i) {
            return $i->total();
        })->sum();
        $receivedAmount = $pembelians->map(function($i) {
            return $i->receivedAmount();
        })->sum();

        return view('pembelians.index', compact('pembelians', 'total', 'receivedAmount'));
    }

    public function cetak(Request $request) {

    }

    public function cetaknotasuplier($id)
    {
    $pembelian = Pembelian::select('*')
                ->where('id', '=', $id)
                ->get();
    $detail = PembelianItem::leftJoin('products', 'products.id', '=', 'pembelian_items.product_id')
    ->select([
        'pembelian_items.quantity as pcs','pembelian_items.price as harga','products.*'
    ])
    ->where('pembelian_id', '=', $id)
    ->get();


    $pengeluarans = DB::table('pengeluarans')
                ->join('pembelians', 'pembelians.id', '=', 'pengeluarans.pembelian_id')
                ->select(DB::raw('SUM(pengeluarans.amount) as totalbayar'))
                ->groupBy('pengeluarans.pembelian_id')
                ->where('pengeluarans.pembelian_id', '=', $id)
                ->get();

    $totals = DB::table('pembelian_items')
                ->join('products', 'products.id', '=', 'pembelian_items.product_id')
                ->select(DB::raw('SUM(pembelian_items.price) as totalharga'))
                ->groupBy('pembelian_id')
                ->where('pembelian_items.pembelian_id', '=', $id)
                ->get();

    $pembelians = Pembelian::find($id);

    $setting = Setting::find($id);

    $no = 0;

    $pdf = PDF::loadView('pembelians.cetaknotasuplier', compact('detail', 'pembelian', 'setting', 'no','totals','pengeluarans'));
    $pdf->setPaper(array(0,0,200,600), 'potrait');
    return $pdf->stream('Laporan-Data-Santri.pdf');
    }




    public function show(Pembelian $pembelian)
    {
        $santri = Pembelian::select('*')
                ->get();

    $pdf = PDF::loadView('pembelians.cetaksantri', ['santri' => $santri]);
    return $pdf->stream('Laporan-Data-Santri.pdf');
    }



    public function getPembelian(Request $request)
    {

        if ($request->ajax()) {

            $data = DB::table('supliers')->orderBy('name');


            return Datatables::of($data)
            ->addColumn(
                'total',
                function($row) {
                    $hasPayment = DB::table('pembelian_items')
                        ->join('pembelians','pembelians.id','=','pembelian_items.pembelian_id')
                        ->select([
                            'pembelian_items.*'
                        ])
                        ->where('pembelians.id', $row->id)
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

            ->addColumn('action', 'pembelians.action')
            ->addIndexColumn()
            ->rawColumns(['action'])
                ->make(true);
        }

    }

    public function store(PembelianStoreRequest $request)
    {
        $pembelian = Pembelian::create([
            'suplier_id' => $request->suplier_id,
            'user_id' => $request->user()->id,
        ]);

        $buy = $request->user()->buy()->get();
        foreach ($buy as $item) {
            $pembelian->items()->create([
                'price' => $item->harga_beli * $item->pivot->quantity,
                'quantity' => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);
            //$item->quantity = $item->quantity - $item->pivot->quantity;
            //$item->save();
        }

        foreach ($buy as $historystoks) {
            $pembelian->historystoks()->create([

                'stok' => $historystoks->pivot->quantity,
                'product_id' => $historystoks->id,
                'user_id' => $request->user()->id,
            ]);


            $stok = Stok::where('product_id', '=', $historystoks->id)->first();

            $stok->current_stok += $historystoks->pivot->quantity;
            $stok->update();



        }

        // $detail = PembelianDetail::where('id_pembelian', '=', $request['idpembelian'])->get();
        // foreach($detail as $data){
        //     $produk = Produk::where('kode_produk', '=', $data->kode_produk)->first();
        //     $produk->stok += $data->jumlah;
        //     $produk->update();
        // }

        $request->user()->buy()->detach();
        $pembelian->pengeluarans()->create([
            'amount' => $request->amount,
            'user_id' => $request->user()->id,
        ]);
        return 'success';
    }
}
