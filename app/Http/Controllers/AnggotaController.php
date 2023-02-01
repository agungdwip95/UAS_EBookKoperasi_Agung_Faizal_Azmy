<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            $anggota = Anggota::with('user')->get();
            // $anggota = Anggota::OrderBy("id", "DESC")->paginate(10);
            // $anggota = Anggota::OrderBy("id", "DESC")->paginate(2)->toArray();

            if ($acceptHeader === 'application/json') {

                $outPut = [
                    "message" => "anggota",
                    "results" => $anggota
                ];

                // response json

                // $response = [
                //     "total_count" => $anggota["total"],
                //     "limit" => $anggota["per_page"],
                //     "pagination" => [
                //         "next_page" => $anggota["next_page_url"],
                //         "current_page" => $anggota["current_page"]
                //     ],
                //     "data" => $anggota["data"],
                // ];

                return response()->json($anggota, 200);
                // return response()->json($response, 200);
            } else {
                // create xml anggota element
                $xml = new \SimpleXMLElement('<anggota/>');
                foreach ($anggota->items('data') as $item) {
                    // create xml anggota element
                    $xmlItem = $xml->addChild('anggota');

                    // mengubah setiap field anggota menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('nama', $item->nama);
                    $xmlItem->addChild('alamat', $item->alamat);
                    $xmlItem->addChild('tgl_lahir', $item->tgl_lahir);
                    $xmlItem->addChild('tempat_lahir', $item->tempat_lahir);
                    $xmlItem->addChild('jenis_kelamin', $item->jenis_kelamin);
                    $xmlItem->addChild('status', $item->status);
                    $xmlItem->addChild('no_tlp', $item->no_tlp);
                    $xmlItem->addChild('user_id', $item->user_id);
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

            $anggota = Anggota::with('user')->find($id);

            if ($acceptHeader === 'application/json') {

                if(!$anggota) {
                    abort(404);
                }

                return response()->json($anggota, 200);

            } else {
                // create xml anggota element
                $xml = new \SimpleXMLElement('<anggota/>');

                // create xml anggota element
                $xmlItem = $xml->addChild('anggota');

                // mengubah setiap field anggota menjadi bentuk xml
                $xmlItem->addChild('id', $anggota->id);
                $xmlItem->addChild('nama', $anggota->nama);
                $xmlItem->addChild('alamat', $anggota->alamat);
                $xmlItem->addChild('tgl_lahir', $anggota->tgl_lahir);
                $xmlItem->addChild('tempat_lahir', $anggota->tempat_lahir);
                $xmlItem->addChild('jenis_kelamin', $anggota->jenis_kelamin);
                $xmlItem->addChild('status', $anggota->status);
                $xmlItem->addChild('no_tlp', $anggota->no_tlp);
                $xmlItem->addChild('user_id', $anggota->user_id);
                $xmlItem->addChild('created_at', $anggota->created_at);
                $xmlItem->addChild('updated_at', $anggota->updated_at);
                
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
                    'nama' => 'required|min:5',
                    'alamat' => 'required|min:5', 
                    'tgl_lahir' => 'required',
                    'tempat_lahir' => 'required',
                    'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
                    'status' => 'required',
                    'no_tlp' => 'required|min:10|max:20',
                    'user_id' => 'required|exists:users,id'
                ];

                $validator = Validator::make($input, $validationRules);

                if ($validator->fails()){
                    return response()->json($validator->errors(), 400);
                }

                $anggota = Anggota::create($input);
                return response()->json($anggota, 200);
        } else {
            return response('Not Acceptable', 406);
        }
    }

    public function update(Request $request, $id)
    {

        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            $input = $request->all();
            
            $anggota = Anggota::find($id);

            if(!$anggota) {
                abort(404);
            }

            // validation
            $validationRules = [
                'nama' => 'required|min:5',
                'alamat' => 'required|min:5', 
                'tgl_lahir' => 'required',
                'tempat_lahir' => 'required',
                'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
                'status' => 'required',
                'no_tlp' => 'required|min:10|max:20',
                'user_id' => 'required|exists:users,id'
            ];

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            // validation end

            $anggota->fill($input);
            $anggota->save();

            return response()->json($anggota, 200);
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $anggota = Anggota::find($id);

            if ($acceptHeader === 'application/json') {
                if(!$anggota) {
                    abort(404);
                }

                $anggota->delete();
                $message = ['message' => 'deleted successfully', 'anggota_id' => $id];

                return response()->json($message, 200);
            } 
            // else {
            //     // create xml anggota element
            //     $xml = new \SimpleXMLElement('<anggota/>');
                
            //     // create xml anggota element
            //     $xmlItem = $xml->addChild('anggota');

            //     // mengubah setiap field anggota menjadi bentuk xml
            //     $xmlItem->addChild('id', $anggota->id);
            //     $xmlItem->addChild('nama', $anggota->nama);
            //     $xmlItem->addChild('alamat', $anggota->alamat);
            //     $xmlItem->addChild('tgl_lahir', $anggota->tgl_lahir);
            //     $xmlItem->addChild('tempat_lahir', $anggota->tempat_lahir);
            //     $xmlItem->addChild('jenis_kelamin', $anggota->jenis_kelamin);
            //     $xmlItem->addChild('status', $anggota->status);
            //     $xmlItem->addChild('no_tlp', $anggota->no_tlp);
            //     $xmlItem->addChild('user_id', $anggota->user_id);
            //     $xmlItem->addChild('created_at', $anggota->created_at);
            //     $xmlItem->addChild('updated_at', $anggota->updated_at);
                
            //     return $xml->asXML();
            // }

        } else {
            return response('Not Acceptable!', 406);
        }
    }
}
