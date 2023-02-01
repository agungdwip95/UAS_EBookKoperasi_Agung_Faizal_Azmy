<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SimpananController extends Controller
{
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            // $simpanan = Simpanan::all();
            $simpanan = Simpanan::with('anggota')->get();
            // $simpanan = Simpanan::OrderBy("id", "DESC")->paginate(10);
            // $simpanan = Simpanan::OrderBy("id", "DESC")->paginate(2)->toArray();

            if ($acceptHeader === 'application/json') {

                $outPut = [
                    "message" => "simpanan",
                    "results" => $simpanan
                ];

                // response json

                // $response = [
                //     "total_count" => $simpanan["total"],
                //     "limit" => $simpanan["per_page"],
                //     "pagination" => [
                //         "next_page" => $simpanan["next_page_url"],
                //         "current_page" => $simpanan["current_page"]
                //     ],
                //     "data" => $simpanan["data"],
                // ];

                return response()->json($simpanan, 200);
                // return response()->json($response, 200);
            } else {
                // create xml simpanan element
                $xml = new \SimpleXMLElement('<simpanan/>');
                foreach ($simpanan->items('data') as $item) {
                    // create xml simpanan element
                    $xmlItem = $xml->addChild('simpanan');

                    // mengubah setiap field simpanan menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('nama_simpanan', $item->nama_simpanan);
                    $xmlItem->addChild('tgl_simpanan', $item->tgl_simpanan);
                    $xmlItem->addChild('besar_simpanan', $item->besar_simpanan);
                    $xmlItem->addChild('anggota_id', $item->anggota_id);
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

            // $simpanan = Simpanan::find($id);
            $simpanan = Simpanan::with('anggota')->find($id);

            if ($acceptHeader === 'application/json') {

                if(!$simpanan) {
                    abort(404);
                }

                return response()->json($simpanan, 200);

            } else {
                // create xml simpanan element
                $xml = new \SimpleXMLElement('<simpanan/>');

                // create xml simpanan element
                $xmlItem = $xml->addChild('simpanan');

                // mengubah setiap field simpanan menjadi bentuk xml
                $xmlItem->addChild('id', $simpanan->id);
                $xmlItem->addChild('nama_simpanan', $simpanan->nama_simpanan);
                $xmlItem->addChild('tgl_simpanan', $simpanan->tgl_simpanan);
                $xmlItem->addChild('besar_simpanan', $simpanan->besar_simpanan);
                $xmlItem->addChild('anggota_id', $simpanan->anggota_id);
                $xmlItem->addChild('created_at', $simpanan->created_at);
                $xmlItem->addChild('updated_at', $simpanan->updated_at);
                
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
                    'nama_simpanan' => 'required|min:5',
                    'tgl_simpanan' => 'required', 
                    'besar_simpanan' => 'required',
                    'anggota_id' => 'required|exists:anggota,id'
                ];

                $validator = Validator::make($input, $validationRules);

                if ($validator->fails()){
                    return response()->json($validator->errors(), 400);
                }

                $simpanan= Simpanan::create($input);
                return response()->json($simpanan, 200);
        } else {
            return response('Not Acceptable', 406);
        }
    }

    public function update(Request $request, $id)
    {

        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            $input = $request->all();
            
            $simpanan = Simpanan::find($id);

            if(!$simpanan) {
                abort(404);
            }

            // validation
            $validationRules = [
                'nama_simpanan' => 'required|min:5',
                'tgl_simpanan' => 'required', 
                'besar_simpanan' => 'required',
                'anggota_id' => 'required|exists:anggota,id'
            ];

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            // validation end

            $simpanan->fill($input);
            $simpanan->save();

            return response()->json($simpanan, 200);
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $simpanan = Simpanan::find($id);

            if ($acceptHeader === 'application/json') {
                if(!$simpanan) {
                    abort(404);
                }

                $simpanan->delete();
                $message = ['message' => 'deleted successfully', 'simpanan_id' => $id];

                return response()->json($message, 200);
            } 
            // else {
            //     // create xml simpanan element
            //     $xml = new \SimpleXMLElement('<simpanan/>');
                
            //     // create xml simpanan element
            //     $xmlItem = $xml->addChild('simpanan');

            //     // mengubah setiap field simpanan menjadi bentuk xml
            //     $xmlItem->addChild('id', $simpanan->id);
            //     $xmlItem->addChild('nama_simpanan', $simpanan->nama_simpanan);
            //     $xmlItem->addChild('tgl_simpanan', $simpanan->tgl_simpanan);
            //     $xmlItem->addChild('besar_simpanan', $simpanan->besar_simpanan);
            //     $xmlItem->addChild('anggota_id', $simpanan->anggota_id);
            //     $xmlItem->addChild('created_at', $simpanan->created_at);
            //     $xmlItem->addChild('updated_at', $simpanan->updated_at);
                
            //     return $xml->asXML();
            // }

        } else {
            return response('Not Acceptable!', 406);
        }
    }
}
