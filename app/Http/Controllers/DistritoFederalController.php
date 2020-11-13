<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\DistritoFederal;
use App\Models\AccesoModulo;
use App\Models\Modulo;

class DistritoFederalController extends Controller
{
    public function __construct() {

		$this->middleware('auth');
	}

	public function index(Request $request) {
        
        if((sizeof(AccesoModulo::where("id_usuario", Auth::user()->id)->where("id_modulo", 3)->get())) || Auth::user()->id == 1) {
            
            if ($request->session()->has('lockscreen')) return redirect('lockscreen');
            else {
    
                $page_title = 'Distritos Federales';
                $content_header = 'Distritos Federales';
    
                $data = $this->getData($request);
                
                $rick = new Request();
                
                $rick->replace([
                    'id_usuario' => Auth::user()->id
                ]);
                
                if(Auth::user()->id == 1) $accesos_modulos = Modulo::where("status", 1)->orderBy("descripcion")->get();
                else $accesos_modulos = app(AccesoModuloController::class)->getData($rick);
    
                return view('federales.inicio', compact('page_title','content_header','data','accesos_modulos'));
            }
        }
        else return redirect('/');
    }

    public function store(Request $request) {

        $validateData = $request->validate([
            'descripcion' => 'required|min:3|max:255|unique:distritos_federales',
            'no_distrito' => 'required|unique:distritos_federales',
        ]);

        $data = new DistritoFederal();

        $data->descripcion = $validateData['descripcion'];
        $data->no_distrito = $validateData['no_distrito'];
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
            'descripcion' => 'required|min:3|max:255|unique:distritos_federales,descripcion,'.$request['id'].',id',
            'no_distrito' => 'required|unique:distritos_federales,id,'.$request['id'],
        ]);

        $this->row = DistritoFederal::find($request['id']);

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

        $query = DB::table("distritos_federales")
                    ->select("id","descripcion","no_distrito")
                    ->whereIn("id", app(AccesoFederalController::class)->accesos($request))
                    ->orderBy("no_distrito");


        if($request['id']) $query->where("id", $request['id']);

        // dd($query->toSql());

        $data = $query->get();

        if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }
}
