<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\RepresentanteTipo;

class RepresentanteTipoController extends Controller
{
    public function getData(Request $request) {

        $query = DB::table("representantes_tipos")
                    ->select("id","descripcion","id_codigo","status")
                    ->orderBy("descripcion");


        if($request['id']) $query->where("id", $request['id']);

        // dd($query->toSql());

        $data = $query->get();

        if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }
}
