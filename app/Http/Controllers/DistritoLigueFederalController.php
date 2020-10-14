<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DistritoLigueFederal;

class DistritoLigueFederalController extends Controller
{
    public function store(Request $request) {

        // dd($request);

        /*
        $validateData = $request->validate([
            'id_distrito_local' => 'required',
            'id_distrito_federal' => 'required',
        ]);
        */

        $validateData = $this->validate($request, [
            'id_distrito_local'  => [
                'required', 

                Rule::unique('distritos_ligue_federal')->where(function ($query) use ($request) {

                    return $query
		                        ->whereid_distrito_federal($request->id_distrito_federal)
		                        ->whereid_distrito_local($request->id_distrito_local)
                                ->whereNull('deleted_at');
                }),
            ],
            // 'id_distrito_local' => 'required',
            'id_distrito_federal' => 'required',
            // 'no_distrito_federal' => 'required',
        ]);


        $data = new DistritoLigueFederal();

        $data->id_distrito_local = $validateData['id_distrito_local'];
        $data->id_distrito_federal = $validateData['id_distrito_federal'];
        // $data->no_distrito_local = $validateData['no_distrito_local'];
        // $data->no_distrito_federal = $validateData['no_distrito_federal'];
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
            'descripcion' => 'required|min:3|max:255|unique:distritos_ligue_federal,descripcion,'.$request['id'].',id',
            'no_distrito' => 'required|unique:distritos_ligue_federal,id,'.$request['id'],
        ]);

        $this->row = DistritoLocal::find($request['id']);

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

    public function delete(Request $request) {

        $this->row = DistritoLigueFederal::find($request['id']);

        // dd($this->row);
        
        $this->row->delete();
        // category::where('name', $name)->delete();

        if($request->ajax()) {
            
            return response()->json([
                'id' => $request['id'],
                'message' => 'Registro actualizado',
            ]);
        }    
    }

    public function getData(Request $request) {

        $query = DB::table("distritos_ligue_federal as ligue")
                    ->leftJoin("distritos_federales as federal", "federal.id", "ligue.id_distrito_federal")
                    ->leftJoin("distritos_locales as local", "local.id", "ligue.id_distrito_local")
                    ->select(
                        "ligue.id",
                        "local.no_distrito as no_distrito_local","local.descripcion as distrito_local",
                        "federal.no_distrito as no_distrito_federal","federal.descripcion as distrito_federal",
                        "ligue.id_distrito_local"
                    );


        if($request['id']) $query->where("ligue.id", $request['id']);

        // if($request['no_distrito_federal']) $query->where("ligue.no_distrito_federal", $request['no_distrito_federal']);
        if($request['id_distrito_federal']) $query->where("ligue.id_distrito_federal", $request['id_distrito_federal']);

        // dd($query->toSql());

        $data = $query->get();        

        if($request['dataType'] == "json") return response()->json($data);
        else return $data;
        
    }
}
