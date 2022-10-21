<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class KategoriBeritaController extends Controller
{
    public function index()
    {
        $kategoriberita = DB::table('kategori_berita')->get();

        $data = [
            'kategoriberita' => $kategoriberita,
            'script'   => 'components.scripts.kategoriberita'
        ];

        return view('pages.kategoriberita', $data);
    }

    public function show($id) {
        if(is_numeric($id)) {
            $data = DB::table('kategoriberita_berita')->where('id', $id)->first();

            //$data->status = number_format($data->status);

            return Response::json($data);
        }

        $data = DB::table('kategoriberita_berita')

            ->select([
                'kategoriberita_berita.*'
            ])
            ->orderBy('kategoriberita_berita.id', 'desc');

        return DataTables::of($data)
            ->editColumn(
                'status',
                function($row) {
                    return $row->status;
                }
            )
            ->addColumn(
                'action',
                function($row) {
                    $data = [
                        'id' => $row->id
                    ];

                    return view('components.buttons.kategoriberita', $data);
                }
            )
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        if($request->title == NULL) {
            $json = [
                'msg'       => 'Mohon masukan judul berita',
                'status'    => false
            ];

        } else {


            try{
                DB::transaction(function() use($request) {
                    DB::table('kategoriberita_berita')->insert([
                        'created_at' => date('Y-m-d H:i:s'),
                        'title' => $request->title,
                        'slug' => Str::slug($request->title),
                        'status' => 'active',
                    ]);
                });

                $json = [
                    'msg' => 'KategoriBerita Berita berhasil ditambahkan',
                    'status' => true
                ];
            } catch(Exception $e) {
                $json = [
                    'msg'       => 'error',
                    'status'    => false,
                    'e'         => $e
                ];
            }
        }

        return Response::json($json);
    }

    public function edit(Request $request, $id)
    {
        if($request->title == NULL) {
            $json = [
                'msg'       => 'Mohon masukan judul kategoriberita berita',
                'status'    => false
            ];
        }  else {
            try{
              DB::transaction(function() use($request, $id) {
                  DB::table('kategoriberita_berita')->where('id', $id)->update([
                      'updated_at' => date('Y-m-d H:i:s'),
                      'role' => $request->title,
                      'slug' => Str::slug($request->title),
                      'status' => 'active',
                  ]);
              });

                $json = [
                    'msg' => 'Produk berhasil dirubah',
                    'status' => true
                ];
            } catch(Exception $e) {
                $json = [
                    'msg'       => 'error',
                    'status'    => false,
                    'e'         => $e
                ];
            }
        }

        return Response::json($json);
    }

    public function destroy($id)
    {

            try{

              DB::transaction(function() use($id) {
                  DB::table('kategoriberita_berita')->where('id', $id)->delete();
              });

                $json = [
                    'msg' => 'KategoriBerita Berita berhasil dihapus',
                    'status' => true
                ];
            } catch(Exception $e) {
                $json = [
                    'msg'       => 'error',
                    'status'    => false,
                    'e'         => $e
                ];
            }


        return Response::json($json);
    }
}
