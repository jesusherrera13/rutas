<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\RutaCasilla;

class RutaCasillaController extends Controller
{
	public function store(Request $request) {

        // dd($request);

        $validateData = $this->validate($request, [
            'id_casilla'  => [
                'required', 

                Rule::unique('rutas_casillas')->where(function ($query) use ($request) {

                    return $query
		                        ->whereid_ruta($request->id_ruta)
		                        ->whereid_casilla($request->id_casilla)
		                        ->wherestatus(1)
                                ->whereNull('deleted_at');
                }),
            ],
            'id_ruta' => 'required',
        ]);


        $data = new RutaCasilla();

        $data->id_casilla = $validateData['id_casilla'];
        $data->id_ruta = $request['id_ruta'];
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
            'id_casilla' => 'required|unique:rutas_casillas,id_casilla,'.$request['id'].',id',
            'id_ruta' => 'required',
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

        $this->row = RutaCasilla::find($request['id']);

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

    public function borrar(Request $request) {

        // dd($request);
        $data = [];

        if($request['items']) {

            if($request['item_delete']) {

               $tmp = explode('|', $request['item_delete']);

                foreach ($tmp as $k => $v) {

                    list($field, $value) = explode(',', $v);

                    $item_delete[$field] = $value ? $value : null;
                }
            }

            // dd($item_delete);

            $tmp = explode(';', $request['items']);

            foreach ($tmp as $k => $v) {

                $param = [
                    'no_seccion' => $request['no_seccion'],
                    'no_distrito_federal' => $request['no_distrito_federal'],
                    'no_distrito_local' => $request['no_distrito_local'],
                    'id' => null
                ];

                $tmp_ = explode('|', $v);

                foreach ($tmp_ as $k_ => $v_) {

                    list($field, $value) = explode(',', $v_);

                    $param[$field] = $value ? $value : null;
                }

                if($param['id_casilla'] == $item_delete['id_casilla']) {

                    $param['status'] = 0;

                    $rick = new Request();

                    $rick->replace($param);


                    if($param['id']) $this->update($rick);
                    // else $this->store($rick);
                }
                else $data_[] = $param;
            }

            if(sizeof($data_)) {

                foreach ($data_ as $row) {

                    $rick = new Request();

                    $rick->replace([
                        'id' => $row['id_casilla'],
                    ]);

                    $row_ = app(CasillaController::class)->getData($rick);

                    // print_r($row_);

                    $data[] = [
                        'id' => $row['id'],
                        'id_casilla' => $row_[0]->id,
                        'id_seccion' => $row_[0]->id_seccion,
                        'casilla' => $row_[0]->casilla,
                        'status' => $row_[0]->status,
                    ];
                }
            }

            if($request->ajax()) {
            
                return response()->json([
                    // 'id' => $data->id,
                    'message' => 'La ruta ha sido actualizada satisfactoriamente.',
                    'data' => $data,
                ]);
            }
        }
    }

	public function getData(Request $request) {

		$query = DB::table("rutas_casillas as rutcas")
	                    ->leftJoin("rutas as ruta", "ruta.id", "rutcas.id_ruta")
	                    ->leftJoin("casillas as casilla", "casilla.id", "rutcas.id_casilla")
	                    ->leftJoin("secciones as seccion", "seccion.id", "casilla.id_seccion")
	                    ->leftJoin("distritos_federales as federal", "federal.id", "seccion.id_distrito_federal")
	                    ->leftJoin("distritos_locales as local", "local.id", "seccion.id_distrito_local")
	                    ->leftJoin("contactos as contacto", "contacto.id", "ruta.id_rg")
                        ->leftJoin("asentamientos as asenta", "asenta.id", "casilla.id_asentamiento")
                        ->leftJoin("municipios as mun", function($join) {
                            $join->on("mun.id_pais", "asenta.id_pais");
                            $join->on("mun.id_estado", "asenta.id_estado");
                            $join->on("mun.id_municipio", "asenta.id_municipio");
                        })
                        ->leftJoin("estados as est", function($join) {
                            $join->on("est.id_pais", "asenta.id_pais");
                            $join->on("est.id_estado", "asenta.id_estado");
                        })
                        ->leftJoin("paises as pais", "pais.id_pais", "asenta.id_pais")
	    				->select(
	                        "rutcas.id","rutcas.id_casilla","rutcas.id_ruta",
	                        DB::raw("
	                        	if(
		                        	casilla.id_tipo_casilla='C',
		                        	concat(LPAD(seccion.no_seccion,4,'0'),'-',casilla.id_tipo_casilla,casilla.no_casilla),
		                        	concat(LPAD(seccion.no_seccion,4,'0'),'-',casilla.id_tipo_casilla)
		                        ) as casilla
	                        "),
	                        "seccion.id_distrito_federal","federal.descripcion as distrito_federal",
	                        "seccion.id_distrito_local","local.descripcion as distrito_local",
	                        DB::raw("concat(contacto.nombre,ifnull(concat(' ',contacto.apellido1),''),ifnull(concat(' ',contacto.apellido2),'')) as representante_general"),
	                        /*
	                        DB::raw("
	                        	if(
		                        	rutcas.id_tipo_casilla='C',
		                        	concat(LPAD(rutcas.no_seccion,4,'0'),'-',rutcas.id_tipo_casilla,rutcas.no_casilla),
		                        	concat(LPAD(rutcas.no_seccion,4,'0'),'-',rutcas.id_tipo_casilla)
		                        ) as casilla
	                        "),
	                        DB::raw("LPAD(rutcas.no_seccion,4,'0') as no_seccion"),"rutcas.id_tipo_casilla",
	                        */
	                        "rutcas.status",
                            "casilla.id_asentamiento",
                            DB::raw("concat(asenta.descripcion,', ',mun.descripcion,', ',est.descripcion,', ',pais.descripcion) as asentamiento"),
                            DB::raw("concat(asenta.descripcion,', ',mun.descripcion) as asentamiento_corto")
                            /*
                            DB::raw("
                                if(
                                    (
                                        select count(cr.id_casilla)
                                        from casillas_representantes as cr
                                        where cr.id_casilla=rutcas.id and cr.status=1
                                    ) > 0,
                                    (select count(cr.id_casilla)
                                    from casillas_representantes as cr
                                    where cr.id_casilla=rutcas.id and cr.status=1),
                                    null
                                ) as no_rcs
                            ")
                            */
	                    )
	    				->where("rutcas.status", 1)
                        ->orderBy("seccion.no_seccion")
	    				->orderBy("casilla.id_tipo_casilla")
	    				->orderBy("casilla.no_casilla");


        if($request['id']) $query->where("rutcas.id", $request['id']);

        if($request['id_ruta']) $query->where("rutcas.id_ruta", $request['id_ruta']);

        if($request['no_seccion']) $query->where("rutcas.no_seccion", $request['no_seccion']);

        if($request['no_distrito_federal']) $query->where("rutcas.no_distrito_federal", $request['no_distrito_federal']);

        if($request['no_distrito_local']) $query->where("rutcas.no_distrito_local", $request['no_distrito_local']);

        $data = $query->get();

        if($request['id']) {

            $rick = new Request();

            $rick->replace([
                'id_casilla' => $data[0]->id
            ]);

            $data[0]->representantes = app(RutaCasillaController::class)->getData($rick);
        }

    	if($request['dataType'] == "json") return response()->json($data);
        else return $data;
	}
}
