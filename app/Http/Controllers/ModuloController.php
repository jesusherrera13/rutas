<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Modulo;
use App\Models\AccesoModulo;

class ModuloController extends Controller
{
    public function __construct() {

		$this->middleware('auth');
	}

	public function index(Request $request) {

        if((sizeof(AccesoModulo::where("id_usuario", Auth::user()->id)->where("id_modulo", 5)->get())) || Auth::user()->id == 1) {

            if ($request->session()->has('lockscreen')) return redirect('lockscreen');
            else {
    
                $page_title = 'Módulos';
                $content_header = 'Módulos';
    
                // $data = $this->getData($request);
                $rick = new Request();
    
                $rick->replace([
                    'id_usuario' => Auth::user()->id
                ]);
    
                if(Auth::user()->id == 1) $accesos_modulos = Modulo::where("status", 1)->orderBy("descripcion")->get();
                else $accesos_modulos = app(AccesoModuloController::class)->getData($rick);
    
                // dd($accesos_modulos);
    
                return view('modulos.inicio', compact('page_title','content_header','accesos_modulos'));
            }
        }
        else return redirect('/');
    }

    public function store(Request $request) {

        $validateData = $request->validate([
            'descripcion' => 'required|min:3|max:255|unique:modulos',
            'url' => 'required|unique:modulos',
        ]);

        $data = new Modulo();

        $data->descripcion = $validateData['descripcion'];
        $data->url = $validateData['url'];
        $data->icon = $request['icon'];
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
            'descripcion' => 'required|min:3|max:255|unique:modulos,descripcion,'.$request['id'].',id',
            'url' => 'required|unique:modulos,id,'.$request['id'],
        ]);

        $this->row = Modulo::find($request['id']);

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

        $query = DB::table("modulos")
                    ->select(
                        "id","descripcion","url","icon",
                        DB::raw("concat('<i class=\"fas fa-edit btn-editar btn-pin\" iddb=\"',id,'\"></i>') as action")
                    )
                    // ->whereIn("id", app(AccesoFederalController::class)->accesos($request))
                    ->orderBy("descripcion");


        if($request['id']) {

            $query->addSelect(DB::raw("concat('<i class=\"fas fa-edit btn-editar btn-pin\" iddb=\"',id,'\"></i>') as action"));

            $query->where("id", $request['id']);
        }

        // dd($query->toSql());

        $data = $query->get();

        if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }
}
