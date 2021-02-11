<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\AccesoModulo;

class AccesoModuloController extends Controller
{
    public function store(Request $request) {

        $validateData = $request->validate([
            'id_usuario' => 'required',
            'id_modulo' => 'required',
        ]);

        $data = new AccesoModulo();

        $data->id_usuario = $validateData['id_usuario'];
        $data->id_modulo = $validateData['id_modulo'];
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

        $validateData = $request->validate([
            'id_usuario' => 'required',
            'id_modulo' => 'required',
        ]);

        $this->row = AccesoModulo::find($request['id']);

        $this->row->fill($request->all());
        $this->row->user_id_update = Auth::user()->id;
        
        $this->row->save();

        if($request->ajax()) {
            
            return response()->json([
                'id' => $request['id'],
                'message' => 'El registro ha sido actualizado satisfactoriamente.',
            ]);
        }
    }

    public function getData(Request $request) {

    	$query = DB::table("accesos_modulos as acc")
    				->leftJoin("modulos as mod","mod.id","acc.id_modulo")
    				->leftJoin("users as user","user.id","acc.id_usuario")
    				->select(
                        "acc.id","acc.id_modulo","mod.descripcion","acc.id_usuario","user.name as usuario","mod.url","mod.icon"
                    )
                    ->orderBy("mod.descripcion");

        if($request['id_usuario']) $query->where("acc.id_usuario", $request['id_usuario']);

        if($request['id']) $query->where("id", $request['id']);

        $data = $query->get();

        if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }

    public function accesos(Request $request) {

        $tmp = AccesoModulo::where("id_usuario", Auth::user()->id)
                ->select("id_modulo","status")        
                ->where("status", 1)->get();
        
        $data = [];
       
        if(sizeof($tmp)) {

            foreach($tmp as $row) {
                
                $data[] = $row->id_modulo;
            }
        }

        return $data;
    }
}
