<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Seccion;
use App\Models\DistritoLigueFederal;
use App\Models\Casilla;

class SeccionController extends Controller
{
	public function __construct() {

		$this->middleware('auth');
	}

	public function index(Request $request) {

        if ($request->session()->has('lockscreen')) return redirect('lockscreen');
        else {
            
        	$page_title = 'Secciones';
        	$content_header = 'Secciones';

        	$data = $this->getData($request);

            $distritos_federales = app(DistritoFederalController::class)->getData($request);
            $distritos_locales = app(DistritoLocalController::class)->getData($request);
            $casillas_tipos = app(CasillaTipoController::class)->getData($request);

        	return view('secciones.inicio', 
                compact(
                    'page_title',
                    'content_header',
                    'data',
                    'distritos_federales',
                    'distritos_locales',
                    'casillas_tipos'
                )
            );
        }
    }

    public function store(Request $request) {

        // dd($request);

        $validateData = $request->validate([
            'no_seccion' => 'required|unique:secciones',
            'id_distrito_federal' => 'required',
            'id_distrito_local' => 'required',
        ]);

        $data = new Seccion();

        $data->no_seccion = $validateData['no_seccion'];
        $data->id_distrito_federal = $validateData['id_distrito_federal'];
        $data->id_distrito_local = $validateData['id_distrito_local'];
        $data->user_id_create = Auth::user()->id;

        $data->save();

        $rick = new Request();

        $rick->replace([
            'id' => $data->id
        ]);

        $this->generador($rick);
        
        // dd($request);

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
            'no_seccion' => 'required|unique:secciones,no_seccion,'.$request['id'].',id',
            'id_distrito_federal' => 'required',
            'id_distrito_local' => 'required',
        ]);

        $this->row = Seccion::find($request['id']);

        $this->row->fill($request->all());
        
        $this->row->user_id_update = Auth::user()->id;
        
        $this->row->save();

        if($request['distrito_federal']) {

            $tmp = explode(';', $request['distrito_federal']);

            foreach ($tmp as $k => $v) {

                $request['id_distrito_local'] = $this->row->id;
                $request['id_distrito_federal'] = $v;

                app(DistritoLigueFederalController::class)->store($request);
            }
        }

        if($request->ajax()) {
            
            return response()->json([
                'id' => $request['id'],
                'message' => 'El registro ha sido actualizado satisfactoriamente.',
            ]);
        }
    }

    public function getData(Request $request) {

        // $this->generador($request);

        /*$data = DistritoLocal::all();

        // dd($data);

        if(sizeof($data)) {

            foreach ($data as $k => $row) {

                $distritosFederales = DistritoLocal::find($row->id)->distritosFederales;

                $data[$k]->distritosFederales = $distritosFederales;
            }
        }

        dd($data);*/

        /*
        // config/database.php
        mysql' => [
            'driver' => 'mysql',
            .
            .
            .
            'strict' => false,
        */

    	
        $query = DB::table("secciones as seccion")
                    ->leftJoin("distritos_federales as federal", "federal.id", "seccion.id_distrito_federal")
                    ->leftJoin("distritos_locales as local", "local.id", "seccion.id_distrito_local")
    				->select(
                        "seccion.id",DB::raw("LPAD(seccion.no_seccion,4,'0') as no_seccion"),
                        // "federal.no_distrito as no_distrito_federal",
                        // "local.no_distrito as no_distrito_local",
                        "seccion.id_distrito_federal","federal.descripcion as distrito_federal",
                        "seccion.id_distrito_local","local.descripcion as distrito_local"
                    )
    				->orderBy("seccion.no_seccion");

        $query->whereIn("seccion.id_distrito_federal", app(AccesoFederalController::class)->accesos($request));
        $query->whereIn("seccion.id_distrito_local", app(AccesoLocalController::class)->accesos($request));

        if($request['id']) $query->where("seccion.id", $request['id']);

        // if($request['no_distrito_federal']) $query->where("seccion.no_distrito_federal", $request['no_distrito_federal']);
        if($request['id_distrito_federal']) $query->where("federal.id", $request['id_distrito_federal']);

        // if($request['no_distrito_local']) $query->where("seccion.no_distrito_local", $request['no_distrito_local']);
        if($request['id_distrito_local']) $query->where("local.id", $request['id_distrito_local']);

        if($request['term']) {

            // $query->addSelect(DB::raw("concat(loc.descripcion,', ',est.descripcion,', ',pais.descripcion) as localidad_"));
            $query->where("seccion.no_seccion",'like', '%'.$request['term'].'%');
            $query->limit(20);
        }

        // dd($query->toSql());

        $data = $query->get();

        if($request['id']) {

            $rick = new Request();

            $rick->replace([
                'id_seccion' => $data[0]->id
            ]);

            $data[0]->casillas = app(CasillaController::class)->getData($rick);
        }

    	if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }

    private function generador(Request $request) {

        // dd($request);

        if($request['id']) $data[] = (Object) Seccion::find($request['id']);
        else $data = Seccion::all();


        foreach ($data as $row) {

            $id_tipo_casilla = 'B';

            $row_ = Casilla::where('id_seccion', $row->id)->where('id_tipo_casilla', $id_tipo_casilla)->get();

            if(!sizeof($row_)) {

                $casilla = Casilla::create([
                    'id_seccion' => $row->id,
                    'id_tipo_casilla' => $id_tipo_casilla,
                    'no_casilla' => 1,
                ]);

                $casilla->save();
            }

            for($i = 1; $i <= 10; $i++) {

                $id_tipo_casilla = 'C';

                $row_ = Casilla::where('id_seccion', $row->id)
                            ->where('id_tipo_casilla', $id_tipo_casilla)
                            ->where('no_casilla', $i)
                            ->get();

                if(!sizeof($row_)) {

                    $casilla = Casilla::create([
                        'id_seccion' => $row->id,
                        'id_tipo_casilla' => $id_tipo_casilla,
                        'no_casilla' => $i,
                    ]);

                    $casilla->save();
                }
            }

            $id_tipo_casilla = 'E';

            $row_ = Casilla::where('id_seccion', $row->id)->where('id_tipo_casilla', $id_tipo_casilla)->get();

            if(!sizeof($row_)) {

                $casilla = Casilla::create([
                    'id_seccion' => $row->id,
                    'id_tipo_casilla' => $id_tipo_casilla,
                    'no_casilla' => 1,
                ]);

                $casilla->save();
            }

            // dd('row');
        }
    }
}
