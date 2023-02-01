<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AngsuranController extends Controller
{
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            // $angsuran = Angsuran::all();
            $angsuran = Angsuran::with('anggota')->get();
            // $angsuran = Angsuran::OrderBy("id", "DESC")->paginate(10);
            // $angsuran = Angsuran::OrderBy("id", "DESC")->paginate(2)->toArray();

            if ($acceptHeader === 'application/json') {

                $outPut = [
                    "message" => "angsuran",
                    "results" => $angsuran
                ];

                // response json

                // $response = [
                //     "total_count" => $angsuran["total"],
                //     "limit" => $angsuran["per_page"],
                //     "pagination" => [
                //         "next_page" => $angsuran["next_page_url"],
                //         "current_page" => $angsuran["current_page"]
                //     ],
                //     "data" => $angsuran["data"],
                // ];

                return response()->json($angsuran, 200);
                // return response()->json($response, 200);
            } else {
                // create xml ansguran element
                $xml = new \SimpleXMLElement('<angsuran/>');
                foreach ($angsuran->items('data') as $item) {
                    // create xml angsuran element
                    $xmlItem = $xml->addChild('angsuran');

                    // mengubah setiap field angsuran menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('tgl_pembayaran', $item->tgl_pembayaran);
                    $xmlItem->addChild('angsuran_ke', $item->angsuran_ke);
                    $xmlItem->addChild('besar_angsuran', $item->besar_angsuran);
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

            // $angsuran = Angsuran::find($id);
            $angsuran = Angsuran::with('anggota')->find($id);

            if ($acceptHeader === 'application/json') {

                if(!$angsuran) {
                    abort(404);
                }

                return response()->json($angsuran, 200);

            } else {
                // create xml angsuran element
                $xml = new \SimpleXMLElement('<angsuran/>');

                // create xml angsuran element
                $xmlItem = $xml->addChild('angsuran');

                // mengubah setiap field angsuran menjadi bentuk xml
                $xmlItem->addChild('id', $angsuran->id);
                $xmlItem->addChild('tgl_pembayaran', $angsuran->tgl_pembayaran);
                $xmlItem->addChild('angsuran_ke', $angsuran->angsuran_ke);
                $xmlItem->addChild('besar_angsuran', $angsuran->besar_angsuran);
                $xmlItem->addChild('anggota_id', $angsuran->anggota_id);
                $xmlItem->addChild('created_at', $angsuran->created_at);
                $xmlItem->addChild('updated_at', $angsuran->updated_at);
                
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
                    'tgl_pembayaran' => 'required',
                    'angsuran_ke' => 'required', 
                    'besar_angsuran' => 'required',
                    'anggota_id' => 'required|exists:anggota,id'
                ];

                $validator = Validator::make($input, $validationRules);

                if ($validator->fails()){
                    return response()->json($validator->errors(), 400);
                }

                $angsuran = Angsuran::create($input);
                return response()->json($angsuran, 200);
        } else {
            return response('Not Acceptable', 406);
        }
    }

    public function update(Request $request, $id)
    {

        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            $input = $request->all();
            
            $angsuran = Angsuran::find($id);

            if(!$angsuran) {
                abort(404);
            }

            // validation
            $validationRules = [
                'tgl_pembayaran' => 'required',
                'angsuran_ke' => 'required', 
                'besar_angsuran' => 'required',
                'anggota_id' => 'required|exists:anggota,id'
            ];

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            // validation end

            $angsuran->fill($input);
            $angsuran->save();

            return response()->json($angsuran, 200);
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $angsuran = Angsuran::find($id);

            if ($acceptHeader === 'application/json') {
                if(!$angsuran) {
                    abort(404);
                }

                $angsuran->delete();
                $message = ['message' => 'deleted successfully', 'angsuran_id' => $id];

                return response()->json($message, 200);
            } 
            // else {
            //     // create xml angsuran element
            //     $xml = new \SimpleXMLElement('<angsuran/>');
                
            //     // create xml angsuran element
            //     $xmlItem = $xml->addChild('angsuran');

            //     // mengubah setiap field angsuran menjadi bentuk xml
            //     $xmlItem->addChild('id', $angsuran->id);
                // $xmlItem->addChild('tgl_pembayaran', $angsuran->tgl_pembayaran);
                // $xmlItem->addChild('angsuran_ke', $angsuran->angsuran_ke);
                // $xmlItem->addChild('besar_angsuran', $angsuran->besar_angsuran);
                // $xmlItem->addChild('anggota_id', $angsuran->anggota_id);
                // $xmlItem->addChild('created_at', $angsuran->created_at);
                // $xmlItem->addChild('updated_at', $angsuran->updated_at);
                
            //     return $xml->asXML();
            // }

        } else {
            return response('Not Acceptable!', 406);
        }
    }
}
