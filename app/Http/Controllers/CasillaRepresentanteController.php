<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\CasillaRepresentante;

class CasillaRepresentanteController extends Controller
{
	public function store(Request $request) {

        // dd($request);

        $validateData = $this->validate($request, [
            'id_contacto'  => [
                'required', 

                Rule::unique('casillas_representantes')->where(function ($query) use ($request) {

                    return $query
		                        ->whereid_contacto($request->id_contacto)
		                        ->whereid_casilla($request->id_casilla)
		                        ->wherestatus(1)
                                ->whereNull('deleted_at');
                }),
            ],
            'id_casilla' => 'required',
        ]);


        $data = new CasillaRepresentante();

        $data->id_contacto = $validateData['id_contacto'];
        $data->id_casilla = $request['id_casilla'];
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
            'id_contacto' => 'required|unique:casillas_representantes,id_contacto,'.$request['id'].',id',
            'id_casilla' => 'required',
        ]);
        

        /*$validateData = $this->validate($request, [
            'id_contacto'  => [
                'required', 

                Rule::unique('casillas_representantes')->where(function ($query) use ($request) {

                    return $query
		                        ->whereid_contacto($request->id_contacto)
		                        ->whereid_casilla($request->id_casilla)
		                        ->wherestatus(1)
                                ->whereNull('deleted_at');
                }),
            ],
            'id_casilla' => 'required',
        ]);*/

        $this->row = CasillaRepresentante::find($request['id']);

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

    public function guardar(Request $request) {

    	if($request['items']) {

    		$tmp = explode(';', $request['items']);

    		foreach ($tmp as $k => $v) {

    			$param = [];

    			$tmp_ = explode('|', $v);

				foreach ($tmp_ as $k_ => $v_) {

					list($field, $value) = explode(',', $v_);

                    $param[$field] = $value || $value == 0 ? $value : null;
				}

                $rick = new Request();

                $rick->replace($param);

                if($param['id']) $this->update($rick);		
                else $this->store($rick);      
    		}

            if($request->ajax()) {
            
	            return response()->json([
	                // 'id' => $data->id,
	                'message' => 'El registro ha sido creado satisfactoriamente.',
	            ]);
	        }
    	}
    }

    public function getData(Request $request) {

	    $query = DB::table("casillas_representantes as rc")
	    					->leftJoin("casillas as casilla", "casilla.id", "rc.id_casilla")
		                    ->leftJoin("secciones as seccion", "seccion.id", "casilla.id_seccion")
	    					->leftJoin("contactos as contacto", "contacto.id", "rc.id_contacto")
		                    ->leftJoin("distritos_federales as federal", "federal.id", "seccion.id_distrito_federal")
		                    ->leftJoin("distritos_locales as local", "local.id", "seccion.id_distrito_local")
		                    ->leftJoin("representantes_tipos as rep", "rep.id", "rc.id_representante_tipo")
		    				->select(
		                        "rc.id","rc.id_contacto",
		                        DB::raw("concat(contacto.nombre,ifnull(concat(' ',contacto.apellido1),''),ifnull(concat(' ',contacto.apellido2),'')) as contacto"),
		                        "rc.id_casilla",
		                        DB::raw("
		                        	if(
			                        	casilla.id_tipo_casilla='C',
			                        	concat(LPAD(seccion.no_seccion,4,'0'),'-',casilla.id_tipo_casilla,casilla.no_casilla),
			                        	concat(LPAD(seccion.no_seccion,4,'0'),'-',casilla.id_tipo_casilla)
			                        ) as casilla
		                        "),
		                        DB::raw("LPAD(seccion.no_seccion,4,'0') as no_seccion"),"casilla.id_tipo_casilla",
		                        "casilla.status","casilla.no_casilla",
		                        "seccion.id_distrito_federal","federal.descripcion as distrito_federal",
                                "seccion.id_distrito_local","local.descripcion as distrito_local",
                                "contacto.direccion","rc.id_representante_tipo","rep.descripcion as representante_tipo","rep.id_codigo",
                                DB::raw("
                                    (
                                        select ctel.no_telefono
                                        from contactos_telefonos as ctel
                                        where ctel.id_contacto=rc.id_contacto
                                        limit 1
                                    ) as no_telefono
                                "),
		                    )
		                    ->where("rc.status", 1)
		    				->orderBy("seccion.no_seccion")
		    				->orderBy("casilla.id_tipo_casilla")
		    				->orderBy("casilla.no_casilla");

			if($request['mod_op'] == 'existe_registro') {

	            // $query->where("base.id_base", $request['id_base']);
	        }
	        else $query->whereNull("rc.deleted_at");

	        if($request['id']) $query->where("casilla.id", $request['id']);

	        if($request['id_casilla']) $query->where("rc.id_casilla", $request['id_casilla']);
	        
            if($request['no_seccion']) $query->where("casilla.no_seccion", $request['no_seccion']);

	        if($request['no_distrito_federal']) $query->where("casilla.no_distrito_federal", $request['no_distrito_federal']);

            if($request['no_distrito_local']) $query->where("casilla.no_distrito_local", $request['no_distrito_local']);
            
	        // if($request['mod_op'] == 'get_representantes') $query->where("rc.id_representante_tipo", "!=", 0);

	        $data = $query->get();

	        /*
	        if($request['id']) {

	            $rick = new Request();

	            $rick->replace([
	                'no_seccion' => $data[0]->no_seccion
	            ]);

	            $data[0]->casillas = app(CasillaController::class)->getData($rick);
	        }
	        */

	    	if($request['dataType'] == "json") return response()->json($data);
	        else return $data;
	}
}
