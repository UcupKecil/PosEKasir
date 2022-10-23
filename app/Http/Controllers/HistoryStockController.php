<?php

namespace App\Http\Controllers;

use App\Models\HistoryStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class HistoryStockController extends Controller
{
    public function index(Request $request) {
    $detail = HistoryStok::Join('products', 'history_stoks.product_id', '=', 'products.id')

        ->Join('users', 'history_stoks.user_id', '=', 'users.id')
        ->select([
            'history_stoks.*', 'products.id as product_id', 'users.id as user_id',
        ])
        ->get();

        if(request()->ajax()) {
            return datatables()->of(HistoryStok::select('*'))
            ->addColumn('action', 'companies.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
            }

                return view('history_stoks.index');


        }

        public function getHistories(Request $request)
        {
            if ($request->ajax()) {
                $data = HistoryStok::latest()->get();
                return Datatables::of($data)
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
