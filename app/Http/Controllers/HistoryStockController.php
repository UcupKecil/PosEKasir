<?php

namespace App\Http\Controllers;

use App\Models\HistoryStok;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class HistoryStockController extends Controller
{
    public function index(Request $request) {
    $detail = HistoryStok::Join('products', 'history_stoks.product_id', '=', 'products.id')

        ->Join('users', 'history_stoks.user_id', '=', 'users.id')
        ->select([
            'history_stoks.*', 'products.name', 'users.id as user_id',
        ])
        ->orderby('id','desc')
        ->get();

        if(request()->ajax()) {
            return datatables()->of(HistoryStok::select('*'))

            // ->addColumn(
            //     'total',
            //     function($row) {
            //         $hasPayment = DB::table('penjualan_items')
            //             ->join('penjualans','penjualans.id','=','penjualan_items.penjualan_id')
            //             ->select([
            //                 'penjualan_items.*'
            //             ])
            //             ->where('penjualans.id', $row->id)
            //             ->get();

            //         if(count($hasPayment) > 0) {
            //             $total = 0;

            //             foreach($hasPayment as $row) {
            //                 $total += $row->price;
            //             }

            //             return 'Rp. '.number_format($total);
            //         }

            //         return 'Rp. 0';
            //     }
            // )
            ->addColumn('action', 'companies.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);



            }


                return view('history_stoks.index',compact('detail'));


        }

        public function getHistories(Request $request)
        {
            if ($request->ajax()) {
                //$data = HistoryStok::latest()->get();
                $data =
                Product::Join('v_history', 'v_history.product_id', '=', 'products.id')

                ->Join('users', 'v_history.user_id', '=', 'users.id')
                ->select([
                    'v_history.*', 'products.name', 'users.first_name as user_id',
                ])
                ->orderby('id','desc')
                ->get();

                // $data= DB::table('v_history')

                // ->get();
                return Datatables::of($data)
                // ->addColumn(
                // 'total','total'

                //     )
                    ->addColumn('waktu', function($data) {
                        // return $data->created_at->diffForHumans();
                        return waktu($data->created_at);
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }

        // $data = [
        //     'history' => $kategoriberita,
        //     'script'   => 'components.scripts.kategoriberita'
        // ];

        // }


        // if ($request->search) {
        //     $products = $products->where('name', 'LIKE', "%{$request->search}%" )
        //     ->orWhere('kategori_id','like',"%{$request->search}%");
        // }
        // if ($request->kategori) {
        //     $products = $products->where('kategori_id', '=', "{$request->kategori}");
        // }
        // $products = $products->latest()->paginate(10);
        // if (request()->wantsJson()) {
        //     return ProductResource::collection($products);
        // }
        // return view('products.index', compact('products','detail'));

}
