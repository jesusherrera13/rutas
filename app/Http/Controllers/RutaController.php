<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\Ruta;
use App\Models\RutaCasilla;
use App\Models\AccesoModulo;
use App\Models\Modulo;

use Barryvdh\DomPDF\Facade as PDF;

class RutaController extends Controller
{
    public function __construct() {

		$this->middleware('auth');
	}

	public function index(Request $request) {

        if((sizeof(AccesoModulo::where("id_usuario", Auth::user()->id)->where("id_modulo", 2)->get())) || Auth::user()->id == 1) {

            if ($request->session()->has('lockscreen')) return redirect('lockscreen');
            else {
    
                $page_title = 'Rutas';
                $content_header = 'Rutas';
    
                $data = $this->getData($request);
    
                $distritos_federales = app(DistritoFederalController::class)->getData($request);

                $rick = new Request();
    
                $rick->replace([
                    'id_usuario' => Auth::user()->id
                ]);

                if(Auth::user()->id == 1) $accesos_modulos = Modulo::where("status", 1)->orderBy("descripcion")->get();
                else $accesos_modulos = app(AccesoModuloController::class)->getData($rick);
    
                return view('rutas.inicio', compact('page_title','content_header','data','distritos_federales','accesos_modulos'));
            }
        }
        else return redirect('/');
    }

    public function store(Request $request) {

        // dd($request);

        /*
        $validateData = $request->validate([
            'id_distrito_local' => 'required',
            'id_distrito_federal' => 'required',
        ]);
        */

        $validateData = $this->validate($request, [
            'descripcion'  => [
                'required', 

                Rule::unique('rutas')->where(function ($query) use ($request) {

                    return $query
		                        ->whereid_distrito_federal($request->id_distrito_federal)
		                        ->whereid_distrito_local($request->id_distrito_local)
                                ->whereNull('deleted_at');
                }),
            ],
            'id_distrito_local' => 'required',
            'id_distrito_federal' => 'required',
        ]);

        $data = new Ruta();

        $data->descripcion = $validateData['descripcion'];
        $data->id_distrito_federal = $validateData['id_distrito_federal'];
        $data->id_distrito_local = $validateData['id_distrito_local'];
        $data->user_id_create = Auth::user()->id;

        $data->save();

        $request['id_ruta'] = $data->id;
        $request['store'] = true;

        $this->setCasillas($request);

        if($request->ajax()) {
            
            return response()->json([
                'id' => $data->id,
                'message' => 'El registro ha sido creado satisfactoriamente.',
            ]);
        }
    }

    public function update(Request $request) {

        $validateData = $request->validate([
            'descripcion' => 'required|min:3|max:255|unique:rutas,descripcion,'.$request['id'].',id',
            'id_distrito_federal' => 'required',
            'id_distrito_local' => 'required',
        ]);

        $this->row = Ruta::find($request['id']);

        $this->row->fill($request->all());
        $this->row->user_id_update = Auth::user()->id;
        
        $this->row->save();

        $this->setCasillas($request);

        if($request->ajax()) {
            
            return response()->json([
                'id' => $request['id'],
                'message' => 'El registro ha sido actualizado satisfactoriamente.',
            ]);
        }
    }

    public function impresion(Request $request, $id) {

        $rick = new Request();

        $rick->replace([
            'id' => $id
        ]);

        // dd($rick);

        $tmp = $this->getData($rick);

        // dd($tmp);

        $data = [
            'title' => $tmp[0]->descripcion,
            'fecha' => date('d/m/Y'),
            'hora' => date('h:i:s'),
            'data' => $tmp
        ];

        // dd($data);
          
        // $dompdf = PDF::loadView('rutas.impresion', $data)->setPaper('a4', 'landscape');
        $dompdf = PDF::loadView('rutas.impresion', $data);

        return $dompdf->stream('ruta_'.$tmp[0]->no_distrito_federal.'_'.$tmp[0]->no_distrito_local.'.pdf');
    }

    public function getData(Request $request) {

        // dd($request);

		$query = DB::table("rutas as ruta")
	                    ->leftJoin("distritos_federales as federal", "federal.id", "ruta.id_distrito_federal")
	                    ->leftJoin("distritos_locales as local", "local.id", "ruta.id_distrito_local")
	                    ->leftJoin("contactos as contacto", "contacto.id", "ruta.id_rg")
	    				->select(
	                        "ruta.id","ruta.descripcion",
	                        "ruta.id_distrito_federal","federal.descripcion as distrito_federal","federal.no_distrito as no_distrito_federal",
	                        "ruta.id_distrito_local","local.descripcion as distrito_local","local.no_distrito as no_distrito_local",
	                        DB::raw("concat(contacto.nombre,ifnull(concat(' ',contacto.apellido1),''),ifnull(concat(' ',contacto.apellido2),'')) as representante_general"),
	                        /*
	                        DB::raw("
	                        	if(
		                        	ruta.id_tipo_casilla='C',
		                        	concat(LPAD(ruta.no_seccion,4,'0'),'-',ruta.id_tipo_casilla,ruta.no_casilla),
		                        	concat(LPAD(ruta.no_seccion,4,'0'),'-',ruta.id_tipo_casilla)
		                        ) as casilla
	                        "),
	                        DB::raw("LPAD(ruta.no_seccion,4,'0') as no_seccion"),"ruta.id_tipo_casilla",
	                        */
	                        "ruta.status",
                            /*
                            DB::raw("
                                if(
                                    (
                                        select count(cr.id_casilla)
                                        from casillas_representantes as cr
                                        where cr.id_casilla=ruta.id and cr.status=1
                                    ) > 0,
                                    (select count(cr.id_casilla)
                                    from casillas_representantes as cr
                                    where cr.id_casilla=ruta.id and cr.status=1),
                                    null
                                ) as no_rcs
                            ")
                            */
	                    )
	    				->orderBy("ruta.descripcion");

            $query->whereIn("ruta.id_distrito_federal", app(AccesoFederalController::class)->accesos($request));
            $query->whereIn("ruta.id_distrito_local", app(AccesoLocalController::class)->accesos($request));

        if($request['id']) $query->where("ruta.id", $request['id']);

        if($request['no_seccion']) $query->where("ruta.no_seccion", $request['no_seccion']);

        if($request['no_distrito_federal']) $query->where("ruta.no_distrito_federal", $request['no_distrito_federal']);

        if($request['no_distrito_local']) $query->where("ruta.no_distrito_local", $request['no_distrito_local']);

        // dd($query->toSql());

        $data = $query->get();

        if($request['id']) {

            // dd($request);

            $rick = new Request();

            $rick->replace([
                'id_ruta' => $request['id']
            ]);

            $data[0]->ruta_casillas = app(RutaCasillaController::class)->getData($rick);

            if(sizeof($data[0]->ruta_casillas)) {

                // dd($data[0]->ruta_casillas);

                foreach ($data[0]->ruta_casillas as $k => $row) {

                    $rick = new Request();

                    $rick->replace([
                        'id_casilla' => $row->id_casilla,
                        'mod_op' => 'get_representantes'
                    ]);

                    // dd($rick);

                    $data[0]->ruta_casillas[$k]->rcs = app(CasillaRepresentanteController::class)->getData($rick);
                }
            }
        }

    	if($request['dataType'] == "json") return response()->json($data);
        else return $data;
	}

    public function setCasillas(Request $request) {

        // dd($request);

        if($request['items_delete']) {

            $tmp = explode(';', $request['items_delete']);

            foreach ($tmp as $id_casilla) {

                // echo $row."\n<br>";
                $row = RutaCasilla::where('id_casilla', $id_casilla)->get();

                if(sizeof($row)) {

                    $rick = new Request();

                    $rick->replace([
                        'id' => $row[0]->id,
                        'id_casilla' => $id_casilla,
                        'id_ruta' => $request['id'],
                        'status' => 0,
                    ]);

                    app(RutaCasillaController::class)->update($rick);
                }
            }
        }

        // dd();

        if($request['items']) {

            $tmp = explode(';', $request['items']);

            foreach ($tmp as $k => $v) {

                $param = [];

                $tmp_ = explode('|', $v);

                foreach ($tmp_ as $k_ => $v_) {

                    list($field, $value) = explode(',', $v_);

                    $param[$field] = $value || $value == 0 ? $value : null;
                }

                if($request['store']) $param['id_ruta'] = $request['id_ruta'];

                // print_r($param);

                if(!$param['id']) {

                    $row = RutaCasilla::where('id_casilla', $param['id_casilla'])->get();

                    if(sizeof($row)) {

                        $param['id'] = $row[0]->id;
                        $param['status'] = 1;
                    }
                }

                $rick = new Request();

                $rick->replace($param);

                if($param['id']) app(RutaCasillaController::class)->update($rick);      
                else app(RutaCasillaController::class)->store($rick);
            }

            if($request->ajax()) {
            
                return response()->json([
                    // 'id' => $data->id,
                    'message' => 'El registro ha sido creado satisfactoriamente.',
                ]);
            }
        }

        /*$tmp = RutaCasilla::where('id_contacto', $request['id'])->get();

        $rick = new Request();
        
        $rick->replace([
            'id_casilla' => $request['id_casilla'],
            'id_contacto' => $request['id'],
        ]);

        if(sizeof($tmp)) {

            if($request['id_casilla']) $rick['status'] = 1;
            else {

                $rick['id_casilla'] = $tmp[0]->id_casilla;

                $rick['status'] = 0;
            }

            $rick['id'] = $tmp[0]->id;

            // dd($rick);
            
            app(CasillaRepresentanteController::class)->update($rick);
        }
        else app(CasillaRepresentanteController::class)->store($rick);*/
    }
}
