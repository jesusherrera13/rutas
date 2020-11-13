<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Referente;
use App\Models\AccesoModulo;
use App\Models\Modulo;

class ReferenteController extends Controller
{
	public function __construct() {

		$this->middleware('auth');
	}

	public function index(Request $request) {

        if((sizeof(AccesoModulo::where("id_usuario", Auth::user()->id)->where("id_modulo", 9)->get())) || Auth::user()->id == 1) {

            if ($request->session()->has('lockscreen')) return redirect('lockscreen');
            else {
                
                $page_title = 'Referentes';
                $content_header = 'Referentes';

                $rick = new Request();
    
                $rick->replace([
                    'id_usuario' => Auth::user()->id
                ]);
    
                if(Auth::user()->id == 1) $accesos_modulos = Modulo::where("status", 1)->orderBy("descripcion")->get();
                else $accesos_modulos = app(AccesoModuloController::class)->getData($rick);
    
                return view('referentes.inicio', compact(
                        'page_title',
                        'content_header',
                        'accesos_modulos',
                        // 'data'
                        // 'distritos_federales',
                        // 'distritos_locales',
                        // 'municipios',
                        // 'asentamientos'
                    )
                );
            }
        }
        else return redirect('/');
    }

    public function store(Request $request) {

        $validateData = $request->validate([
            'id_contacto' => 'required',
        ]);

        $data = new Referente();

        $data->id_contacto = $validateData['id_contacto'];
        // $data->status = $request['status'];
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
            'id_contacto' => 'required',
        ]);

        $this->row = Referente::find($request['id']);

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

        $query = DB::table("referentes as ref")
                    ->leftJoin("contactos as contacto", "contacto.id", "ref.id_contacto")
    				->select(
                        "ref.id","ref.id_contacto","contacto.nombre",
                        DB::raw("ifnull(contacto.apellido1,'') as apellido1"),
                        DB::raw("ifnull(contacto.apellido2,'') as apellido2"),
                        // DB::raw("concat(ifnull(concat(contacto.apellido1),''),ifnull(concat(' ',contacto.apellido2),' '),concat(' ',contacto.nombre)) as contacto"),
                        DB::raw("concat(contacto.nombre,ifnull(concat(' ',contacto.apellido1),''),ifnull(concat(' ',contacto.apellido2),'')) as contacto"),
                        DB::raw("concat(SUBSTRING_INDEX(contacto.nombre, ' ', 1),ifnull(concat(' ',contacto.apellido1),'')) as contacto_corto"),
                        DB::raw("null as no_telefono"),
                    )
                    ->where("ref.status", 1);

        if($request['id_modulo'] == "contactos") {

            $query->orderBy(DB::raw("concat(SUBSTRING_INDEX(contacto.nombre, ' ', 1),ifnull(concat(' ',contacto.apellido1),''))"));
        }
        else {

            $query->orderBy(DB::raw("concat(ifnull(concat(contacto.apellido1),''),ifnull(concat(' ',contacto.apellido2),' '),concat(' ',contacto.nombre))"));
        }

        if($request['id']) $query->where("ref.id", $request['id']);

        if($request['term']) {

            // $query->addSelect(DB::raw("concat(loc.descripcion,', ',est.descripcion,', ',pais.descripcion) as localidad_"));
            $query->where(DB::raw("concat(contacto.nombre,ifnull(concat(' ',contacto.apellido1),''),ifnull(concat(' ',contacto.apellido2),''))"),'like', '%'.$request['term'].'%');
            $query->limit(20);
        }

        $data = $query->get();

    	if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }

    public function guardar(Request $request) {

        if($request['items']) {

            $tmp = explode(';', $request['items']);

            foreach ($tmp as $k => $v) {

                $param = [
                    'id' => null
                ];

                $tmp_ = explode('|', $v);

                foreach ($tmp_ as $k_ => $v_) {

                    list($field, $value) = explode(',', $v_);

                    $param[$field] = $value ? $value : null;
                }

                // print_r($param);

                if(!$param['id']) {

                    $ron = Referente::where('id_contacto', $param['id_contacto'])->get();

                    if(sizeof($ron)) {

                        $param['id'] = $ron[0]->id;
                        $param['status'] = 1;
                    }
                }

                $rick = new Request();

                $rick->replace($param);

                if($param['id']) $this->update($rick);
                else {

                    if($request['accion'] == "add") $this->store($rick);
                }
            }

            if($request->ajax()) {
            
                return response()->json([
                    // 'id' => $data->id,
                    'message' => 'El registro ha sido actualizado satisfactoriamente.',
                ]);
            }
        }
    }

    public function referentes(Request $request) {

        // dd($request);
        $data = [
            'referentes' => null,
            'contactos' => null,
        ];

        $rick = new Request();

        $rick->replace([
            'id_modulo' => $request['id_modulo']
        ]);

        $data['referentes'] = $this->getData($rick);
        $data['contactos'] = app(ContactoController::class)->getData($rick);

        // dd($data);
        if(sizeof($data['referentes'])) {

            foreach ($data['referentes'] as $row) {

            }
        }

        if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }
}
