<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Contacto;
use App\Models\ContactoTelefono;
use App\Models\ContactoEmail;
use App\Models\CasillaRepresentante;
use App\Models\Casilla;

class ContactoController extends Controller
{
    public function __construct() {

		$this->middleware('auth');
	}

	public function index(Request $request) {

        if ($request->session()->has('lockscreen')) return redirect('lockscreen');
        else {
            
        	$page_title = 'Contactos';
        	$content_header = 'Contactos';

        	// $data = $this->getData($request);

        	$distritos_federales = app(DistritoFederalController::class)->getData($request);
            $distritos_locales = app(DistritoLocalController::class)->getData($request);
            $municipios = app(MunicipioController::class)->getData($request);
            $asentamientos = app(AsentamientoController::class)->getData($request);
            $coordinadores = app(CoordinadorController::class)->getData($request);
            $referentes = app(ReferenteController::class)->getData($request);

            // $referentes = $this->referentes($request);

            // dd($referentes);

            return view('contactos.inicio', compact(
                    'page_title',
                    'content_header',
                    'distritos_federales',
                    'distritos_locales',
                    'municipios',
                    'asentamientos',
                    'coordinadores',
                    'referentes'
                )
            );
        }
    }

    public function store(Request $request) {

        // dd($request);

        $validateData = $request->validate([
            'nombre' => 'required',
            'apellido1' => 'required',
            'id_seccion' => 'required',
            // 'apellido2' => 'required',
        ]);

        $data = new Contacto();

        $data->nombre = $validateData['nombre'];
        $data->apellido1 = $validateData['apellido1'];
        $data->apellido2 = $request['apellido2'];
        $data->id_seccion = $validateData['id_seccion'];
        $data->id_asentamiento = $request['id_asentamiento'];
        $data->direccion = $request['direccion'];
        $data->id_referente = $request['id_referente'];
        $data->id_coordinador = $request['id_coordinador'];
        $data->status = $request['status'];
        $data->user_id_create = Auth::user()->id;

        $data->save();
        
        $rick = new Request();

        $rick->replace([
            'id_contacto' => $data->id,
            'telefonos' => $request['telefonos'],
            'emails' => $request['emails'],
        ]);

        $this->generales($rick);

        $request['id'] = $data->id;

        // dd($request);

        $this->setCasilla($request);

        if($request->ajax()) {
            
            return response()->json([
                'id' => $data->id,
                'message' => 'El registro ha sido creado satisfactoriamente.',
            ]);
        }
    }

    public function update(Request $request) {

        $validateData = $request->validate([
            // 'no_seccion' => 'required|unique:secciones,no_seccion,'.$request['id'].',id',
            'nombre' => 'required',
            'apellido1' => 'required',
            'id_seccion' => 'required',
        ]);

        $this->row = Contacto::find($request['id']);

        $this->row->fill($request->all());
        $this->row->user_id_update = Auth::user()->id;
        
        $this->row->save();

        $rick = new Request();

        $rick->replace([
            'id_contacto' => $request['id'],
            'telefonos' => $request['telefonos'],
            'emails' => $request['emails'],
        ]);

        $this->generales($rick);
        
        $this->setCasilla($request);

        if($request->ajax()) {
            
            return response()->json([
                'id' => $request['id'],
                'message' => 'El registro ha sido actualizado satisfactoriamente.',
            ]);
        }
    }

    public function getData(Request $request) {

        $query = DB::table("contactos as contacto")
                    ->leftJoin("secciones as seccion", "seccion.id", "contacto.id_seccion")
                    ->leftJoin("distritos_federales as federal", "federal.id", "seccion.id_distrito_federal")
                    ->leftJoin("distritos_locales as local", "local.id", "seccion.id_distrito_local")
                    ->leftJoin("asentamientos as asenta", "asenta.id", "contacto.id_asentamiento")
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
                    ->leftJoin("contactos as referente", "referente.id", "contacto.id_referente")
                    ->leftJoin("contactos as coordinador", "coordinador.id", "contacto.id_coordinador")
                    // ->leftJoin("casillas_representantes as casrep", "casrep.id_contacto", "contacto.id")
                    ->leftJoin("casillas_representantes as casrep", function($join) {

                        $join->on("casrep.id_contacto", "contacto.id");
                        $join->where("casrep.status", 1);
                    })
                    ->leftJoin("casillas as casilla", "casilla.id", "casrep.id_casilla")
    				->select(
                        "contacto.id","contacto.nombre",
                        DB::raw("ifnull(contacto.apellido1,'') as apellido1"),
                        DB::raw("ifnull(contacto.apellido2,'') as apellido2"),
                        DB::raw("concat(ifnull(concat(contacto.apellido1),''),ifnull(concat(' ',contacto.apellido2),' '),concat(' ',contacto.nombre)) as contacto"),
                        DB::raw("concat(SUBSTRING_INDEX(contacto.nombre, ' ', 1),' ',ifnull(concat(contacto.apellido1),''),ifnull(concat(' ',contacto.apellido2),' ')) as contacto_corto"),
                        "contacto.id_seccion","casrep.id_casilla",
                        DB::raw("
                            if(
                                casilla.id_tipo_casilla='C',
                                concat(LPAD(seccion.no_seccion,4,'0'),'-',casilla.id_tipo_casilla,casilla.no_casilla),
                                concat(LPAD(seccion.no_seccion,4,'0'),'-',casilla.id_tipo_casilla)
                            ) as casilla
                        "),
                        DB::raw("LPAD(seccion.no_seccion,4,'0') as no_seccion"),"contacto.email",
                        // DB::raw("group_concat(distinct posicion.id_campo_posicion separator ',') juegos_por_posicion")
                        DB::raw("
                            (
                                select ctel.no_telefono
                                from contactos_telefonos as ctel
                                where ctel.id_contacto=contacto.id
                                limit 1
                            ) as no_telefono
                        "),
                        DB::raw("
                            (
                                select cmail.email
                                from contactos_emails as cmail
                                where cmail.id_contacto=contacto.id
                                limit 1
                            ) as email
                        "),
                        "contacto.direccion",
                        "federal.no_distrito as no_distrito_federal","federal.descripcion as distrito_federal",
                        "local.no_distrito as no_distrito_local","local.descripcion as distrito_local","contacto.referente",
                        "contacto.id_asentamiento","asenta.descripcion as asentamiento",
                        DB::raw("concat(asenta.descripcion,', ',mun.descripcion,', ',est.descripcion,', ',pais.descripcion) as asentamiento_"),
                        "contacto.id_referente",
                        DB::raw("concat(referente.nombre,ifnull(concat(' ',referente.apellido1),''),ifnull(concat(' ',referente.apellido2),'')) as referente"),
                        DB::raw("concat(SUBSTRING_INDEX(referente.nombre, ' ', 1),ifnull(concat(' ',referente.apellido1),'')) as referente_corto"),
                        // DB::raw("concat(SUBSTRING_INDEX(referente.nombre, ' ', 1),ifnull(concat(' ',referente.apellido1)) as referente_corto"),
                        "contacto.id_coordinador",
                        DB::raw("concat(coordinador.nombre,ifnull(concat(' ',coordinador.apellido1),''),ifnull(concat(' ',coordinador.apellido2),'')) as coordinador"),
                        DB::raw("concat(SUBSTRING_INDEX(coordinador.nombre, ' ', 1),ifnull(concat(' ',coordinador.apellido1),'')) as coordinador_corto"),
                    );

        if($request['id']) $query->where("contacto.id", $request['id']);

        if($request['id_distrito_federal']) $query->where("seccion.id_distrito_federal", $request['id_distrito_federal']);
        if($request['id_distrito_local']) $query->where("seccion.id_distrito_local", $request['id_distrito_local']);
        if($request['id_municipio']) $query->where("asenta.id_municipio", $request['id_municipio']);
        if($request['id_asentamiento']) $query->where("contacto.id_asentamiento", $request['id_asentamiento']);
        if($request['id_coordinador']) $query->where("contacto.id_coordinador", $request['id_coordinador']);
        if($request['id_referente']) $query->where("contacto.id_referente", $request['id_referente']);

        if($request['mod_op'] == 'get_referente') {

            $query->groupBy("contacto.id_referente");
            $query->whereNotNull("contacto.id_referente");
            $query->orderBy(DB::raw("concat(referente.nombre,ifnull(concat(' ',referente.apellido1),''),ifnull(concat(' ',referente.apellido2),''))"));

            // dd($query->toSql());
        }
        else $query->orderBy("seccion.no_seccion");

        if($request['term']) {

            // dd($request['term']);

            // $query->addSelect(DB::raw("concat(loc.descripcion,', ',est.descripcion,', ',pais.descripcion) as localidad_"));
            $query->where(DB::raw("concat(SUBSTRING_INDEX(contacto.nombre, ' ', 1),ifnull(concat(' ',contacto.apellido1),''),ifnull(concat(' ',contacto.apellido2),''))"),'like', '%'.$request['term'].'%');
            $query->limit(20);
        }

        if($request['id_asentamiento']) {

            $query->where("asenta.id", $request['id_asentamiento']);
        }

        if($request['status']) {

            $query->where("contacto.status", $request['status']);
        }

        $seleccionados = [];

        if($request['id_modulo'] == 'casillas') {

            $seleccionados = [];

            $rick = new Request();

            $tmp = app(CasillaRepresentanteController::class)->getData($rick);

            if(sizeof($tmp)) {

                foreach ($tmp as $row) {

                    // print_r($row);
                    $seleccionados[] = $row->id_contacto;
                }
            }


            if($request['seleccionados']) {

                $tmp = explode(';', $request['seleccionados']);

                $seleccionados = array_merge($seleccionados, $tmp);
            }
            
            if(sizeof($seleccionados)) $query->whereNotIn("contacto.id", $seleccionados);
        }
        else if($request['id_modulo'] == 'coordinadores') {

            // dd($request);

            $rick = new Request();

            $tmp = app(CoordinadorController::class)->getData($rick);

            if(sizeof($tmp)) {

                foreach ($tmp as $row) {

                    // print_r($row);
                    $seleccionados[] = $row->id_contacto;
                }
            }

            if($request['seleccionados']) {

                $tmp = explode(';', $request['seleccionados']);

                $seleccionados = array_merge($seleccionados, $tmp);
            }

            // dd($seleccionados);
            
        }
        else if($request['id_modulo'] == 'referentes') {

            $rick = new Request();

            $tmp = app(ReferenteController::class)->getData($rick);

            if(sizeof($tmp)) {

                foreach ($tmp as $row) {

                    $seleccionados[] = $row->id_contacto;
                }
            }

            if($request['seleccionados']) {

                $tmp = explode(';', $request['seleccionados']);

                $seleccionados = array_merge($seleccionados, $tmp);
            }
        }
        
        if(sizeof($seleccionados)) $query->whereNotIn("contacto.id", $seleccionados);


        // dd($query->toSql());

        $data = $query->get();

        if($request['id']) {

            $query->where("contacto.id", $request['id']);
        
            $tmp = ContactoTelefono::where('id_contacto', $request['id'])->get();

            $data[0]->telefonos = $tmp;

            $tmp = ContactoEmail::where('id_contacto', $request['id'])->get();

            $data[0]->emails = $tmp;
        }

        foreach ($data as $row) {

            /*
            $tmp = Casilla::where('id_seccion', $row->id_seccion)
                        ->where('id_tipo_casilla', 'B')
                        ->get();
            
            if(sizeof($tmp)) {

                $rick = new Request();

                $rick->replace([
                    'id_casilla' => $tmp[0]->id,
                    'id' => $row->id,
                ]);

                $this->setCasilla($rick);
            }
            */
        }

        if($request['term']) $data = $this->acortador($data);

    	if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }

    private function generales(Request $request) {

        if($request['telefonos']) {

            $tmp = explode(';', $request['telefonos']);

            foreach ($tmp as $k => $v) {

                $tmp_ = explode('|', $v);

                $rick = new Request();

                $param = [
                    'id_contacto' => $request['id_contacto']
                ];

                foreach ($tmp_ as $k_ => $v_) {

                    list($field, $value) = explode(',', $v_);

                    $param[$field] = $value;
                }
                
                $rick->replace($param);

                if($param['id']) app(ContactoTelefonoController::class)->update($rick);
                else app(ContactoTelefonoController::class)->store($rick);
            }
        }

        if($request['emails']) {

            $tmp = explode(';', $request['emails']);

            foreach ($tmp as $k => $v) {

                $tmp_ = explode('|', $v);

                $rick = new Request();

                $param = [
                    'id_contacto' => $request['id_contacto']
                ];

                foreach ($tmp_ as $k_ => $v_) {

                    list($field, $value) = explode(',', $v_);

                    $param[$field] = $value;
                }
                
                $rick->replace($param);

                if($param['id']) app(ContactoEmailController::class)->update($rick);
                else app(ContactoEmailController::class)->store($rick);
            }
        }
    }

    public function setCasilla(Request $request) {

        // dd($tmp);
        $tmp = CasillaRepresentante::where('id_contacto', $request['id'])->get();

        $rick = new Request();
        
        $rick->replace([
            'id_casilla' => $request['id_casilla'],
            'id_contacto' => $request['id'],
        ]);

        if($request['id_casilla']) {

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
            else app(CasillaRepresentanteController::class)->store($rick);
        }
    }

    public function referentes(Request $request) {

        $data = [];

        $query = DB::table("contactos as contacto")
                    ->leftJoin("contactos as referente", "referente.id", "contacto.id_referente")
                    ->select(
                        "contacto.id","contacto.id_referente","referente.nombre",
                        DB::raw("ifnull(referente.apellido1,'') as apellido1"),
                        DB::raw("ifnull(referente.apellido2,'') as apellido2"),
                        DB::raw("concat(ifnull(concat(referente.apellido1),''),ifnull(concat(' ',referente.apellido2),' '),concat(' ',referente.nombre)) as referente"),
                        DB::raw("concat(SUBSTRING_INDEX(referente.nombre, ' ', 1),' ',ifnull(concat(referente.apellido1),''),ifnull(concat(' ',referente.apellido2),' ')) as referente_corto"),
                    )
                    ->groupBy("contacto.id_referente")
                    ->whereNotNull("contacto.id_referente")
                    ->orderBy(DB::raw("concat(referente.nombre,ifnull(concat(' ',referente.apellido1),''),ifnull(concat(' ',referente.apellido2),''))"));;

        $data = $query->get();

        /*if(sizeof($tmp)) {

            foreach ($tmp as $key => $row) {

                $tmp_ = explode(' ', $row->nombre);

                if(sizeof($tmp_) > 1) {

                    $row->referente_corto = $tmp_[0];

                }
                else  $row->referente_corto = $row->nombre;
                
                $row->referente_corto.= " ".$row->apellido1;

                $data[] = $row;
            }
        }*/

        if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }

    public function acortador($data = []) {

        // dd($data);
        $tmp = $data;

        $data = [];

        if(sizeof($tmp)) {

            foreach ($tmp as $key => $row) {

                $tmp_ = explode(' ', $row->nombre);

                if(sizeof($tmp_) > 1) {

                    $row->referente_corto = $tmp_[0];

                }
                else  $row->referente_corto = $row->nombre;
                
                $row->referente_corto.= " ".$row->apellido1;

                $data[] = $row;
            }
        }

        // dd($data);

        return $data;
    }
}
