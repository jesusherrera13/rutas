<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImpresionFormatoController extends Controller
{
    public function getData(Request $request) {

        $query = DB::table("impresion_formatos")
                    ->select(
                        "id","descripcion","abreviacion",
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
