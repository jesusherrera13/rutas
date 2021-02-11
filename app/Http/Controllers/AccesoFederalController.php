<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\AccesoFederal;

class AccesoFederalController extends Controller
{
    public function store(Request $request) {

        $validateData = $request->validate([
            'id_usuario' => 'required',
            'id_distrito_federal' => 'required',
        ]);

        $data = new AccesoFederal();

        $data->id_usuario = $validateData['id_usuario'];
        $data->id_distrito_federal = $validateData['id_distrito_federal'];
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
            'id_distrito_federal' => 'required',
        ]);

        $this->row = AccesoFederal::find($request['id']);

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

    public function accesos(Request $request) {

        // dd($request);

        $tmp = AccesoFederal::where("id_usuario", Auth::user()->id)
                ->select("id_distrito_federal","status")        
                ->where("status", 1)->get();
        
        $data = [];
        // dd($tmp);
        if(sizeof($tmp)) {

            foreach($tmp as $row) {
                
                $data[] = $row->id_distrito_federal;
            }
        }

        return $data;
    }
}
