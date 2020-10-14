<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CasillaTipoController extends Controller
{
	public function getData(Request $request) {

    	$query = DB::table("casillas_tipos")
    				->select("id","descripcion","id_tipo_casilla")
    				->orderBy("descripcion");

        if($request['id']) $query->where("id", $request['id']);

        $data = $query->get();

    	if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }
}
