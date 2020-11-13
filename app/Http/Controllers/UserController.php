<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DistritoFederal;
use App\Models\DistritoLocal;
use App\Models\AccesoFederal;
use App\Models\AccesoLocal;
use App\Models\AccesoModulo;
use App\Models\Modulo;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	public function __construct() {

		$this->middleware('auth');
	}

    public function index(Request $request) {

        if((sizeof(AccesoModulo::where("id_usuario", Auth::user()->id)->where("id_modulo", 1)->get())) || Auth::user()->id == 1) {

            if ($request->session()->has('lockscreen')) return redirect('lockscreen');
            else {
    
                $page_title = 'Usuarios';
                $content_header = 'Usuarios';
    
                $data = User::all();
                $distritos_federales = DistritoFederal::where("status", 1)->get();
                $distritos_locales = DistritoLocal::where("status", 1)->get();
                $modulos = Modulo::where("status", 1)->orderBy("descripcion")->get();
    
                $rick = new Request();
                
                $rick->replace([
                    'id_usuario' => Auth::user()->id
                ]);
                
                if(Auth::user()->id == 1) $accesos_modulos = Modulo::where("status", 1)->orderBy("descripcion")->get();
                else $accesos_modulos = app(AccesoModuloController::class)->getData($rick);
    
                // dd($distritos_federales);
    
                return view('usuarios.inicio', compact(
                        'page_title',
                        'content_header',
                        'data','distritos_federales','distritos_locales',
                        'modulos','accesos_modulos'
                    )
                );
            }
        }
        else return redirect('/');
    }

    public function dashboard(Request $request) {

    	// dd($request);

		if ($request->session()->has('lockscreen')) return redirect('lockscreen');
		else {

            $titulo = 'Dashboard';

            $rick = new Request();

            $rick->replace([
                'id_usuario' => Auth::user()->id
            ]);
            
            // $accesos_modulos = app(AccesoModuloController::class)->getData($rick);
            if(Auth::user()->id == 1) $accesos_modulos = Modulo::where("status", 1)->orderBy("descripcion")->get();
            else $accesos_modulos = app(AccesoModuloController::class)->getData($rick);

	    	return view('inicio.dashboard',compact('accesos_modulos'));
		}
    }

    public function store(Request $request) {

        $request['password'] = Hash::make($request['password']);

        // dd($request);

        /*
        $validateData = $request->validate([
            'descripcion' => 'required|min:3|max:255|unique:distritos_federales,descripcion,'.$request['id'].',id',
            'no_distrito' => 'required|unique:distritos_federales,id,'.$request['id'],
        ]);
        */

        $validateData = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);

        $data = new User();

        $data->name = $validateData['name'];
        $data->email = $validateData['email'];
        $data->password = $validateData['password'];

        $data->save();

        $rick = new Request();

        $rick->replace([
            'id_usuario' => $data->id,
            'distritos_federales' => $request['distritos_federales'],
            'distritos_locales' => $request['distritos_locales'],
            'modulos' => $request['modulos'],
        ]);

        $this->accesos($rick);

        if($request->ajax()) {
            
            return response()->json([
                'id' => $data->id,
                'message' => 'El registro ha sido creado satisfactoriamente.',
            ]);
        }
    }
    
    public function update(Request $request) {

        $validateData = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|min:3|max:255|unique:users,email,'.$request['id'].',id',
        ]);

        $this->row = User::find($request['id']);

        // $this->row->fill($request->all());
        // $data->name = $validateData['name'];
        // $data->email = $validateData['email'];
        $this->row->name = $validateData['name'];
        $this->row->email = $validateData['email'];
        $this->row->user_id_update = Auth::user()->id;
        
        $this->row->save();

        $rick = new Request();

        $rick->replace([
            'id_usuario' => $request['id'],
            'distritos_federales' => $request['distritos_federales'],
            'distritos_locales' => $request['distritos_locales'],
            'modulos' => $request['modulos'],
        ]);

        $this->accesos($rick);

        if($request->ajax()) {
            
            return response()->json([
                'id' => $request['id'],
                'message' => 'El registro ha sido actualizado satisfactoriamente.',
            ]);
        }
    }

    public function getData(Request $request) {

    	// $data = User::all();

    	$query = DB::table("users")
    				->select("id","name","email")
    				->orderBy("name");

        if($request['id']) $query->where("id", $request['id']);

        $data = $query->get();

        if($request['id']) {

            $data[0]->distritos_federales = AccesoFederal::where("id_usuario", $request['id'])
                    ->select("id_distrito_federal","status")        
                    ->where("status", 1)->get();

            $data[0]->distritos_locales = AccesoLocal::where("id_usuario", $request['id'])
                    ->select("id_distrito_local","status")        
                    ->where("status", 1)->get();

            $data[0]->modulos = AccesoModulo::where("id_usuario", $request['id'])
                    ->select("id_modulo","status")        
                    ->where("status", 1)->get();
        }

    	if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }

    private function accesos(Request $request) {

        AccesoFederal::where('id_usuario', $request['id_usuario'])
                ->update(['status' => 0]);

        AccesoLocal::where('id_usuario', $request['id_usuario'])
                ->update(['status' => 0]);
        
        AccesoModulo::where('id_usuario', $request['id_usuario'])
                ->update(['status' => 0]);

        // dd(9);

        if($request['distritos_federales']) {

            $tmp = explode(';', $request['distritos_federales']);

            foreach ($tmp as $k => $v) {

                $tmp_ = explode('|', $v);

                $rick = new Request();

                $param = [
                    'id_usuario' => $request['id_usuario']
                ];

                foreach ($tmp_ as $k_ => $v_) {

                    list($field, $value) = explode(',', $v_);

                    $param[$field] = $value;
                }

                
                $ron = AccesoFederal::where('id_usuario', $request['id_usuario'])
                ->where('id_distrito_federal', $param['id_distrito_federal'])
                ->get();
                
                if(sizeof($ron)) {
                    
                    $param['id'] = $ron[0]->id;
                    $param['status'] = 1;
                }
                
                // print_r($param);

                $rick->replace($param);

                if($param['id']) app(AccesoFederalController::class)->update($rick);
                else app(AccesoFederalController::class)->store($rick);
            }
        }

        if($request['distritos_locales']) {

            $tmp = explode(';', $request['distritos_locales']);

            foreach ($tmp as $k => $v) {

                $tmp_ = explode('|', $v);

                $rick = new Request();

                $param = [
                    'id_usuario' => $request['id_usuario']
                ];

                foreach ($tmp_ as $k_ => $v_) {

                    list($field, $value) = explode(',', $v_);

                    $param[$field] = $value;
                }

                // print_r($param);
                
                $ron = AccesoLocal::where('id_usuario', $request['id_usuario'])
                            ->where('id_distrito_local', $param['id_distrito_local'])
                            ->get();

                if(sizeof($ron)) {

                    $param['id'] = $ron[0]->id;
                    $param['status'] = 1;
                }

                $rick->replace($param);

                if($param['id']) app(AccesoLocalController::class)->update($rick);
                else app(AccesoLocalController::class)->store($rick);
            }
        }

        if($request['modulos']) {

            $tmp = explode(';', $request['modulos']);

            foreach ($tmp as $k => $v) {

                $tmp_ = explode('|', $v);

                $rick = new Request();

                $param = [
                    'id_usuario' => $request['id_usuario']
                ];

                foreach ($tmp_ as $k_ => $v_) {

                    list($field, $value) = explode(',', $v_);

                    $param[$field] = $value;
                }

                // print_r($param);
                
                $ron = AccesoModulo::where('id_usuario', $request['id_usuario'])
                            ->where('id_modulo', $param['id_modulo'])
                            ->get();

                if(sizeof($ron)) {

                    $param['id'] = $ron[0]->id;
                    $param['status'] = 1;
                }

                $rick->replace($param);

                if($param['id']) app(AccesoModuloController::class)->update($rick);
                else app(AccesoModuloController::class)->store($rick);
            }
        }
    }

    private function accesosModulos(Request $request) {

        // return AccesoModulo::
    }
}
