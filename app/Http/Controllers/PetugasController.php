<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            // $petugas = Petugas::all();
            $petugas = Petugas::with('user')->get();
            // $petugas = Petugas::OrderBy("id", "DESC")->paginate(10);
            // $petugas = Petugas::OrderBy("id", "DESC")->paginate(2)->toArray();

            if ($acceptHeader === 'application/json') {

                $outPut = [
                    "message" => "petugas",
                    "results" => $petugas
                ];

                // response json

                // $response = [
                //     "total_count" => $petugas["total"],
                //     "limit" => $petugas["per_page"],
                //     "pagination" => [
                //         "next_page" => $petugas["next_page_url"],
                //         "current_page" => $petugas["current_page"]
                //     ],
                //     "data" => $petugas["data"],
                // ];

                return response()->json($petugas, 200);
                // return response()->json($response, 200);
            } else {
                // create xml petugas element
                $xml = new \SimpleXMLElement('<petugas/>');
                foreach ($petugas->items('data') as $item) {
                    // create xml petugas element
                    $xmlItem = $xml->addChild('petugas');

                    // mengubah setiap field petugas menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('nama', $item->nama);
                    $xmlItem->addChild('alamat', $item->alamat);
                    $xmlItem->addChild('jenis_kelamin', $item->jenis_kelamin);
                    $xmlItem->addChild('tgl_lahir', $item->tgl_lahir);
                    $xmlItem->addChild('tempat_lahir', $item->tempat_lahir);
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

            // $petugas = Petugas::find($id);
            $petugas = Petugas::with('user')->find($id);

            if ($acceptHeader === 'application/json') {

                if(!$petugas) {
                    abort(404);
                }

                return response()->json($petugas, 200);

            } else {
                // create xml petugas element
                $xml = new \SimpleXMLElement('<petugas/>');

                // create xml petugas element
                $xmlItem = $xml->addChild('petugas');

                // mengubah setiap field petugas menjadi bentuk xml
                $xmlItem->addChild('id', $petugas->id);
                $xmlItem->addChild('nama', $petugas->nama);
                $xmlItem->addChild('alamat', $petugas->alamat);
                $xmlItem->addChild('jenis_kelamin', $petugas->jenis_kelamin);
                $xmlItem->addChild('tgl_lahir', $petugas->tgl_lahir);
                $xmlItem->addChild('tempat_lahir', $petugas->tempat_lahir);
                $xmlItem->addChild('user_id', $petugas->user_id);
                $xmlItem->addChild('created_at', $petugas->created_at);
                $xmlItem->addChild('updated_at', $petugas->updated_at);
                
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
                    'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
                    'tgl_lahir' => 'required',
                    'tempat_lahir' => 'required',
                    'user_id' => 'required|exists:users,id'
                ];

                $validator = Validator::make($input, $validationRules);

                if ($validator->fails()){
                    return response()->json($validator->errors(), 400);
                }

                $anggota = Petugas::create($input);
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
            
            $petugas = Petugas::find($id);

            if(!$petugas) {
                abort(404);
            }

            // validation
            $validationRules = [
                'nama' => 'required|min:5',
                'alamat' => 'required|min:5', 
                'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
                'tgl_lahir' => 'required',
                'tempat_lahir' => 'required',
                'user_id' => 'required|exists:users,id'
            ];

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            // validation end

            $petugas->fill($input);
            $petugas->save();

            return response()->json($petugas, 200);
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $petugas = Petugas::find($id);

            if ($acceptHeader === 'application/json') {
                if(!$petugas) {
                    abort(404);
                }

                $petugas->delete();
                $message = ['message' => 'deleted successfully', 'petugas_id' => $id];

                return response()->json($message, 200);
            } 
            // else {
            //     // create xml petugas element
            //     $xml = new \SimpleXMLElement('<petugas/>');
                
            //     // create xml petugas element
            //     $xmlItem = $xml->addChild('petugas');`

            //     // mengubah setiap field petugas menjadi bentuk xml
            //     $xmlItem->addChild('id', $petugas->id);
                // $xmlItem->addChild('nama', $petugas->nama);
                // $xmlItem->addChild('alamat', $petugas->alamat);
                // $xmlItem->addChild('jenis_kelamin', $petugas->jenis_kelamin);
                // $xmlItem->addChild('tgl_lahir', $petugas->tgl_lahir);
                // $xmlItem->addChild('tempat_lahir', $petugas->tempat_lahir);
                // $xmlItem->addChild('user_id', $petugas->user_id);
                // $xmlItem->addChild('created_at', $petugas->created_at);
                // $xmlItem->addChild('updated_at', $petugas->updated_at);
                
            //     return $xml->asXML();
            // }

        } else {
            return response('Not Acceptable!', 406);
        }
    }
}
