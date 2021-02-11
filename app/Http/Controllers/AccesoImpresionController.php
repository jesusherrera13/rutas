<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\AccesoImpresion;

class AccesoImpresionController extends Controller
{
    public function store(Request $request) {

        $validateData = $request->validate([
            'id_usuario' => 'required',
            'id_formato' => 'required',
        ]);

        $data = new AccesoImpresion();

        $data->id_usuario = $validateData['id_usuario'];
        $data->id_formato = $validateData['id_formato'];
        $data->user_id_create = Auth::user()->id;

        $data->save();

        if($request->ajax()) {
            
            return response()->json([
                'id' => $data->id,
                'message' => 'El registro ha sido creado satisfactoriamente.',
            ]);
        }
    }

    public function update(Request $request) {

        // dd($request);

        $validateData = $request->validate([
            'id_usuario' => 'required',
            'id_formato' => 'required',
        ]);

        $this->row = AccesoImpresion::find($request['id']);

        // dd($request->all());

        $this->row->fill($request->all());
        $this->row->user_id_update = Auth::user()->id;
        
        $this->row->save();
        
        if($request->ajax()) {
            echo "x";
            return response()->json([
                'id' => $request['id'],
                'message' => 'El registro ha sido actualizado satisfactoriamente.',
            ]);
        }
    }

    public function getData(Request $request) {

    	$query = DB::table("accesos_impresion as acc")
    				->leftJoin("impresion_formatos as for","for.id","acc.id_formato")
    				->leftJoin("users as user","user.id","acc.id_usuario")
    				->select(
                        "acc.id","acc.id_formato","for.descripcion","for.abreviacion","acc.id_usuario","user.name as usuario"
                    )
                    ->orderBy("for.descripcion");

        if($request['id_usuario']) $query->where("acc.id_usuario", $request['id_usuario']);
        
        if($request['status']) $query->where("acc.status", $request['status']);

        if($request['id']) $query->where("id", $request['id']);

        $data = $query->get();

        if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }

    public function accesos(Request $request) {

        // dd($request);

        $tmp = AccesoImpresion::where("id_usuario", Auth::user()->id)
                ->select("id_formato","status")        
                ->where("status", 1)->get();
        
        $data = [];
        // dd($tmp);
        if(sizeof($tmp)) {

            foreach($tmp as $row) {
                
                $data[] = $row->id_formato;
            }
        }

        return $data;
    }
}
