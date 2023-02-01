<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            // $pinjaman = Pinjaman::all();
            $pinjaman = Pinjaman::with('anggota')->with('angsuran')->get();
            // $pinjaman = Simpanan::OrderBy("id", "DESC")->paginate(10);
            // $pinjaman = Simpanan::OrderBy("id", "DESC")->paginate(2)->toArray();

            if ($acceptHeader === 'application/json') {

                $outPut = [
                    "message" => "pinjaman",
                    "results" => $pinjaman
                ];

                // response json

                // $response = [
                //     "total_count" => $pinjaman["total"],
                //     "limit" => $pinjaman["per_page"],
                //     "pagination" => [
                //         "next_page" => $pinjaman["next_page_url"],
                //         "current_page" => $pinjaman["current_page"]
                //     ],
                //     "data" => $pinjaman["data"],
                // ];

                return response()->json($pinjaman, 200);
                // return response()->json($response, 200);
            } else {
                // create xml simpanan element
                $xml = new \SimpleXMLElement('<pinjaman/>');
                foreach ($pinjaman->items('data') as $item) {
                    // create xml pinjaman element
                    $xmlItem = $xml->addChild('pinjaman');

                    // mengubah setiap field pinjaman menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('nama_pinjaman', $item->nama_pinjaman);
                    $xmlItem->addChild('besar_pinjaman', $item->besar_pinjaman);
                    $xmlItem->addChild('tgl_pengajuan_pinjaman', $item->tgl_pengajuan_pinjaman);
                    $xmlItem->addChild('tgl_acc_pinjaman', $item->tgl_acc_pinjaman);
                    $xmlItem->addChild('tgl_pinjaman', $item->tgl_pinjaman);
                    $xmlItem->addChild('tgl_pelunasan', $item->tgl_pelunasan);
                    $xmlItem->addChild('anggota_id', $item->anggota_id);
                    $xmlItem->addChild('angsuran_id', $item->angsuran_id);
                    $xmlItem->addChild('created_at', $item->created_at);
                    $xmlItem->addChild('updated_at', $item->updated_at);
                }
                return $xml->asXML();
            }
        } else {
            return response('Not Acceptable', 406);
        }
    }

    public function show(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            // $pinjaman = Pinjaman::find($id);
            $pinjaman = Pinjaman::with('anggota')->with('angsuran')->find($id);

            if ($acceptHeader === 'application/json') {

                if(!$pinjaman) {
                    abort(404);
                }

                return response()->json($pinjaman, 200);

            } else {
                // create xml pinjaman element
                $xml = new \SimpleXMLElement('<pinjaman/>');

                // create xml pinjaman element
                $xmlItem = $xml->addChild('pinjaman');

                // mengubah setiap field pinjaman menjadi bentuk xml
                $xmlItem->addChild('id', $pinjaman->id);
                $xmlItem->addChild('nama_pinjaman', $pinjaman->nama_pinjaman);
                $xmlItem->addChild('besar_pinjaman', $pinjaman->besar_pinjaman);
                $xmlItem->addChild('tgl_pengajuan_pinjaman', $pinjaman->tgl_pengajuan_pinjaman);
                $xmlItem->addChild('tgl_acc_pinjaman', $pinjaman->tgl_acc_pinjaman);
                $xmlItem->addChild('tgl_pinjaman', $pinjaman->tgl_pinjaman);
                $xmlItem->addChild('tgl_pelunasan', $pinjaman->tgl_pelunasan);
                $xmlItem->addChild('anggota_id', $pinjaman->anggota_id);
                $xmlItem->addChild('angsuran_id', $pinjaman->angsuran_id);
                $xmlItem->addChild('created_at', $pinjaman->created_at);
                $xmlItem->addChild('updated_at', $pinjaman->updated_at);
                
                return $xml->asXML();
            }

        } else {
            return response('Not Acceptable', 406);
        }
    }

    public function store(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

                $input = $request->all();

                $validationRules = [
                    'nama_pinjaman' => 'required|min:5',
                    'besar_pinjaman' => 'required', 
                    'tgl_pengajuan_pinjaman' => 'required',
                    'tgl_acc_pinjaman' => 'required',
                    'tgl_pinjaman' => 'required',
                    'tgl_pelunasan' => 'required',
                    'anggota_id' => 'required',
                    'angsuran_id' => 'required'
                ];

                $validator = Validator::make($input, $validationRules);

                if ($validator->fails()){
                    return response()->json($validator->errors(), 400);
                }

                $pinjaman= Pinjaman::create($input);
                return response()->json($pinjaman, 200);
        } else {
            return response('Not Acceptable', 406);
        }
    }

    public function update(Request $request, $id)
    {

        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            $input = $request->all();
            
            $pinjaman = Pinjaman::find($id);

            if(!$pinjaman) {
                abort(404);
            }

            // validation
            $validationRules = [
                'nama_pinjaman' => 'required|min:5',
                'besar_pinjaman' => 'required', 
                'tgl_pengajuan_pinjaman' => 'required',
                'tgl_acc_pinjaman' => 'required',
                'tgl_pinjaman' => 'required',
                'tgl_pelunasan' => 'required',
                'anggota_id' => 'required',
                'angsuran_id' => 'required'
            ];

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            // validation end

            $pinjaman->fill($input);
            $pinjaman->save();

            return response()->json($pinjaman, 200);
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $pinjaman = Pinjaman::find($id);

            if ($acceptHeader === 'application/json') {
                if(!$pinjaman) {
                    abort(404);
                }

                $pinjaman->delete();
                $message = ['message' => 'deleted successfully', 'pinjaman_id' => $id];

                return response()->json($message, 200);
            } 
            // else {
            //     // create xml pinjaman element
            //     $xml = new \SimpleXMLElement('<pinjaman/>');
                
            //     // create xml pinjaman element
            //     $xmlItem = $xml->addChild('pinjaman');

            //     // mengubah setiap field pinjaman menjadi bentuk xml
            //     $xmlItem->addChild('id', $pinjaman->id);
            //     $xmlItem->addChild('nama_pinjaman', $pinjaman->nama_pinjaman);
            //     $xmlItem->addChild('besar_pinjaman', $pinjaman->besar_pinjaman);
            //     $xmlItem->addChild('tgl_pengajuan_pinjaman', $pinjaman->tgl_pengajuan_pinjaman);
            //     $xmlItem->addChild('tgl_acc_pinjaman', $pinjaman->tgl_acc_pinjaman);
            //     $xmlItem->addChild('tgl_pinjaman', $pinjaman->tgl_pinjaman);
            //     $xmlItem->addChild('tgl_pelunasan', $pinjaman->tgl_pelunasan);
            //     $xmlItem->addChild('anggota_id', $pinjaman->anggota_id);
            //     $xmlItem->addChild('angsuran_id', $pinjaman->angsuran_id);
            //     $xmlItem->addChild('created_at', $pinjaman->created_at);
            //     $xmlItem->addChild('updated_at', $pinjaman->updated_at);
                
            //     return $xml->asXML();
            // }

        } else {
            return response('Not Acceptable!', 406);
        }
    }
}
