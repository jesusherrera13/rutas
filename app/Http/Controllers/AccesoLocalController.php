<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\AccesoLocal;

class AccesoLocalController extends Controller
{
    public function store(Request $request) {

        $validateData = $request->validate([
            'id_usuario' => 'required',
            'id_distrito_local' => 'required',
        ]);

        $data = new AccesoLocal();

        $data->id_usuario = $validateData['id_usuario'];
        $data->id_distrito_local = $validateData['id_distrito_local'];
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
            'id_distrito_local' => 'required',
        ]);

        $this->row = AccesoLocal::find($request['id']);

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

    public function accesos(Request $request) {

        $tmp = AccesoLocal::where("id_usuario", Auth::user()->id)
                ->select("id_distrito_local","status")        
                ->where("status", 1)->get();
        
        $data = [];
       
        if(sizeof($tmp)) {

            foreach($tmp as $row) {
                
                $data[] = $row->id_distrito_local;
            }
        }

        return $data;
    }
}
