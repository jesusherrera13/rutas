<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\DistritoLocal;
use App\Models\DistritoLigueFederal;
use App\Models\AccesoModulo;
use App\Models\Modulo;

class DistritoLocalController extends Controller
{
	public function __construct() {

		$this->middleware('auth');
	}

	public function index(Request $request) {
        
        if((sizeof(AccesoModulo::where("id_usuario", Auth::user()->id)->where("id_modulo", 4)->get())) || Auth::user()->id == 1) {

            if ($request->session()->has('lockscreen')) return redirect('lockscreen');
            else {
                
                $page_title = 'Distritos Locales';
                $content_header = 'Distritos Locales';
    
                $data = $this->getData($request);
    
                $distritos_federales = app(DistritoFederalController::class)->getData($request);
    
                $rick = new Request();
                
                $rick->replace([
                    'id_usuario' => Auth::user()->id
                ]);
                
                if(Auth::user()->id == 1) $accesos_modulos = Modulo::where("status", 1)->orderBy("descripcion")->get();
                else $accesos_modulos = app(AccesoModuloController::class)->getData($rick);
    
                return view('locales.inicio', compact('page_title','content_header','data','distritos_federales','accesos_modulos'));
            }
        }
        else return redirect('/');
    }

    public function store(Request $request) {

        // dd($request);

        $validateData = $request->validate([
            'descripcion' => 'required|min:3|max:255|unique:distritos_locales',
            'no_distrito' => 'required|unique:distritos_locales',
            // 'id_distrito_federal' => 'required',
        ]);

        $data = new DistritoLocal();

        $data->descripcion = $validateData['descripcion'];
        $data->no_distrito = $validateData['no_distrito'];
        $data->user_id_create = Auth::user()->id;

        $data->save();
        
        if($request['distrito_federal']) {

            $tmp = explode(';', $request['distrito_federal']);

            foreach ($tmp as $k => $v) {

                $tmp_ = explode('|', $v);

                $rick = new Request();

                $param = [];

                foreach ($tmp_ as $k_ => $v_) {

                    list($field, $value) = explode(',', $v_);

                    $param[$field] = $value;
                }
                
                $param['id_distrito_local'] = $data->id;

                $rick->replace($param);

                app(DistritoLigueFederalController::class)->store($rick);
            }
        }

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
            'descripcion' => 'required|min:3|max:255|unique:distritos_locales,descripcion,'.$request['id'].',id',
            'no_distrito' => 'required|unique:distritos_locales,id,'.$request['id'],
        ]);

        $this->row = DistritoLocal::find($request['id']);

        $this->row->fill($request->all());
        $this->row->user_id_update = Auth::user()->id;
        
        $this->row->save();

        // dd($request['id']);

        // DB::table("distritos_ligue_federal")->where("id_distrito_local", $request['id'])->delete();
        $query = DB::table("distritos_ligue_federal")->where("id_distrito_local", $request['id'])->whereNull("deleted_at");

        // dd($query->toSql());

        $tmp = $query->get();

        if(sizeof($tmp)) {

            foreach ($tmp as $k => $row) {

                // print_r($row);
                $rick = new Request();

                $rick->replace([
                    'id' => $row->id
                ]);

                app(DistritoLigueFederalController::class)->delete($rick);             
            }
        }
        
        if($request['distrito_federal']) {


            $tmp = explode(';', $request['distrito_federal']);

            foreach ($tmp as $v) {

                $param = [];

                $tmp_ = explode('|', $v);
                
                foreach ($tmp_ as $v_) {

                    list($field, $value) = explode(',', $v_);

                    $param[$field] = $value;

                    // $request['id_distrito_local'] = $this->row->id;
                    // $request['id_distrito_federal'] = $v;

                    // dd($request);

                }


                // print_r($param);

                $rick = new Request();

                $rick->replace($param);
                
                app(DistritoLigueFederalController::class)->store($rick);
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

        
        if($request['ligue_federal']) {

            $query = DB::table("distritos_locales as disloc")
                        ->leftJoin("distritos_ligue_federal as ligfed", "ligfed.id_distrito_local", "disloc.id")
                        ->leftJoin("distritos_federales as disfed", "disfed.id", "ligfed.id_distrito_federal")
                        ->select(
                            "disloc.id","disloc.descripcion","disloc.no_distrito","ligfed.id_distrito_local","ligfed.id_distrito_federal",
                            "disfed.descripcion as distrito_federal"
                        )
                        ->orderBy("disloc.no_distrito");
        }
        else {
            
            $query = DB::table("distritos_locales as disloc")
                        // ->leftJoin("distritos_ligue_federal as ligfed", "ligfed.id_distrito_local", "disloc.id")
                        ->leftJoin("distritos_ligue_federal as ligfed", function($join) {

                            $join->on("ligfed.id_distrito_local", "disloc.id");
                            $join->whereNull("ligfed.deleted_at");
                        })
                        ->leftJoin("distritos_federales as disfed", "disfed.id", "ligfed.id_distrito_federal")
                        ->select(
                            "disloc.id","disloc.descripcion","disloc.no_distrito","ligfed.id_distrito_local","ligfed.id_distrito_federal",
                            // "disfed.descripcion as distrito_federal"
                            DB::raw("group_concat(distinct disfed.descripcion separator ',') as distrito_federal")
                        )
                        ->groupBy("disloc.id")
                        ->whereIn("disloc.id", app(AccesoLocalController::class)->accesos($request))
                        ->orderBy("disloc.no_distrito");
        }




        if($request['id']) $query->where("disloc.id", $request['id']);

        $data = $query->get();

        if($request['id']) {

            unset($data[0]->id_distrito_federal);

            // dd($request['id']);

            // dd($data);

            $tmp = DistritoLigueFederal::where("id_distrito_local", $request['id'])->whereNull("deleted_at")->get();

            // dd($tmp);
            foreach ($tmp as $flight) {

                $data[0]->distritos_federales[] = $flight->id_distrito_federal;

                // echo $flight->id_distrito_local."\n<br>";
            }
        }
        

        if($request['dataType'] == "json") return response()->json($data);
        else return $data;
        
    }
}
