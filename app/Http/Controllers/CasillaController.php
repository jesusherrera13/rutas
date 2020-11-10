<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\Casilla;
use App\Models\CasillaRepresentante;

class CasillaController extends Controller
{
	public function __construct() {

		$this->middleware('auth');
	}

	public function index(Request $request) {

        if ($request->session()->has('lockscreen')) return redirect('lockscreen');
        else {

        	$page_title = 'Casillas';
        	$content_header = 'Casillas';

        	$data = $this->getData($request);

        	return view('casillas.inicio', compact('page_title','content_header','data'));
        }
    }

    public function store(Request $request) {

        // dd($request);

        $validateData = $this->validate($request, [
            'id_seccion'  => [
                'required', 

                Rule::unique('casillas')->where(function ($query) use ($request) {

                    return $query
		                        ->whereid_seccion($request->id_seccion)
		                        ->whereid_tipo_casilla($request->id_tipo_casilla)
		                        ->whereno_casilla($request->no_casilla)
		                        // ->whereno_distrito_federal($request->no_distrito_federal)
		                        // ->whereno_distrito_local($request->no_distrito_local)
                                ->whereNull('deleted_at');
                }),
            ],
            'id_tipo_casilla' => 'required',
            // 'no_casilla' => 'required',
            // 'no_distrito_federal' => 'required',
            // 'no_distrito_local' => 'required',
        ]);


        $data = new Casilla();

        $data->id_seccion = $validateData['id_seccion'];
        $data->id_tipo_casilla = $request['id_tipo_casilla'];
        $data->no_casilla = $request['no_casilla'];
        $data->id_asentamiento = $request['id_asentamiento'];
        // $data->no_distrito_local = $request['no_distrito_local'];
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
            // 'no_seccion' => 'required|unique:casillas,no_seccion,'.$request['id'].',id',
            'id_seccion' => 'required',
            'id_tipo_casilla' => 'required',
            'no_distrito_federal' => 'required',
            'no_distrito_local' => 'required',
        ]);

        $this->row = Casilla::find($request['id']);

        $this->row->fill($request->all());

        $this->row->user_id_update = Auth::user()->id;
        
        $this->row->save();

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

                if(!$param['id']) {

                    $row = CasillaRepresentante::where('id_contacto', $param['id_contacto'])->get();

                    if(sizeof($row)) {

                        $param['id'] = $row[0]->id;
                        $param['status'] = 1;
                    }
                }

                $rick->replace($param);

                if($param['id']) app(CasillaRepresentanteController::class)->update($rick);
                else app(CasillaRepresentanteController::class)->store($rick);
            }
        }

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

    public function guardar(Request $request) {

    	if($request['lista']) {

    		$tmp = explode(';', $request['lista']);

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

                // print_r($param);
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

                // if($param['id_casilla'] == $item_delete['id_casilla']) continue;

                $rick = new Request();

                $rick->replace($param);

                if($param['id']) $this->update($rick);
                // else $this->store($rick);

                // print_r($param);
                $data[] = $param;
            }

            if($request->ajax()) {
            
                return response()->json([
                    // 'id' => $data->id,
                    'message' => 'El registro ha sido creado satisfactoriamente.',
                    'data' => $data,
                ]);
            }
        }
    }

	public function getData(Request $request) {

		$query = DB::table("casillas as casilla")
	                    ->leftJoin("secciones as seccion", "seccion.id", "casilla.id_seccion")
	                    ->leftJoin("distritos_federales as federal", "federal.id", "seccion.id_distrito_federal")
	                    ->leftJoin("distritos_locales as local", "local.id", "seccion.id_distrito_local")
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
                        ->leftJoin("rutas_casillas as rutac", "rutac.id_casilla", "casilla.id")
                        ->leftJoin("rutas as ruta", "ruta.id", "rutac.id_ruta")
	    				->select(
	                        "casilla.id",
	                        DB::raw("
	                        	if(
		                        	casilla.id_tipo_casilla='C',
		                        	concat(LPAD(seccion.no_seccion,4,'0'),'-',casilla.id_tipo_casilla,casilla.no_casilla),
		                        	concat(LPAD(seccion.no_seccion,4,'0'),'-',casilla.id_tipo_casilla)
		                        ) as casilla
	                        "),
                            "casilla.id_seccion",
	                        DB::raw("LPAD(seccion.no_seccion,4,'0') as no_seccion"),"casilla.id_tipo_casilla",
	                        "casilla.status","casilla.no_casilla",
	                        "federal.no_distrito as no_distrito_federal","federal.descripcion as distrito_federal",
                            "local.no_distrito as no_distrito_local","local.descripcion as distrito_local",
                            DB::raw("
                                if(
                                    (
                                        select count(cr.id_casilla)
                                        from casillas_representantes as cr
                                        where cr.id_casilla=casilla.id and cr.status=1
                                    ) > 0,
                                    (select count(cr.id_casilla)
                                    from casillas_representantes as cr
                                    where cr.id_casilla=casilla.id and cr.status=1),
                                    null
                                ) as no_rcs
                            "),
                            "casilla.id_asentamiento",
                            DB::raw("concat(asenta.descripcion,', ',mun.descripcion,', ',est.descripcion,', ',pais.descripcion) as asentamiento"),
                            "ruta.descripcion as ruta"
	                    )
	    				->orderBy("seccion.no_seccion")
	    				->orderBy("casilla.id_tipo_casilla")
	    				->orderBy("casilla.no_casilla");

        $query->whereIn("seccion.id_distrito_federal", app(AccesoFederalController::class)->accesos($request));
        $query->whereIn("seccion.id_distrito_local", app(AccesoLocalController::class)->accesos($request));

        if($request['id']) $query->where("casilla.id", $request['id']);

        if($request['id_seccion']) $query->where("casilla.id_seccion", $request['id_seccion']);

        if($request['no_distrito_federal']) $query->where("casilla.no_distrito_federal", $request['no_distrito_federal']);

        if($request['no_distrito_local']) $query->where("casilla.no_distrito_local", $request['no_distrito_local']);

        if($request['id_modulo'] == 'rutas') {

            $seleccionados = [];

            $rick = new Request();

            $tmp = app(RutaCasillaController::class)->getData($rick);

            // dd($tmp);

            if(sizeof($tmp)) {

                foreach ($tmp as $row) {

                    // print_r($row);
                    $seleccionados[] = $row->id_casilla;
                }
            }

            if($request['seleccionados']) {

                $tmp = explode(';', $request['seleccionados']);

                $seleccionados = array_merge($seleccionados, $tmp);
            }

            // dd($seleccionados);
            
            if(sizeof($seleccionados)) $query->whereNotIn("casilla.id", $seleccionados);

        }

        $data = $query->get();

        if($request['id']) {

            $rick = new Request();

            $rick->replace([
                'id_casilla' => $data[0]->id
            ]);

            $data[0]->representantes = app(CasillaRepresentanteController::class)->getData($rick);
        }

    	if($request['dataType'] == "json") return response()->json($data);
        else return $data;
	}
}
