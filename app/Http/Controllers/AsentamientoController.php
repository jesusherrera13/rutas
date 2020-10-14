<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Asentamiento;

class AsentamientoController extends Controller
{
    public function getData(Request $request) {

        $query = DB::table("asentamientos as asenta")
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
                    ->select(
                        "asenta.id","asenta.descripcion","asenta.descripcion as asentamiento",
                        "asenta.id_municipio","mun.descripcion as municipio",
                        "asenta.id_estado","est.descripcion as estado","asenta.id_pais","pais.descripcion as pais",
                        DB::raw("concat(asenta.descripcion,', ',mun.descripcion,', ',est.descripcion,', ',pais.descripcion) as asentamiento_")
                    )
                    ->where("asenta.id_estado", "25")
                    // ->where("asenta.id_municipio", "012")
                    ->orderBy("est.descripcion")
                    ->orderBy("asenta.descripcion");

        if($request['term']) {

            // $query->addSelect(DB::raw("concat(loc.descripcion,', ',est.descripcion,', ',pais.descripcion) as localidad_"));
            $query->where(DB::raw("concat(asenta.descripcion,' ',est.descripcion)"),'like', '%'.$request['term'].'%');
            $query->limit(20);
        }
        // else $query->limit(100);

        if($request['mod_op'] == 'existe_registro') {

            $query->where("asenta.id_agencia", $request['id_agencia']);
            $query->where("asenta.id_contacto", $request['id_contacto']);
        }
        else $query->whereNull("asenta.deleted_at");

        if($request['id']) $query->whereIn("asenta.id", [$request['id']]);

        if($request['id_localidad']) $query->whereIn("asenta.id_localidad", [$request['id_localidad']]);

        if($request['id_pais']) {

            $query->where("asenta.id_pais", $request['id_pais']);
        }

        if($request['id_estado']) {

            $query->where("asenta.id_estado", $request['id_estado']);
        }

        if($request['id_asentamiento']) {

            $query->where("asenta.id_asentamiento", $request['id_asentamiento']);
        }

        if($request['items_seleccionados']) {

            $query->whereNotIn("asenta.id", explode(';', $request['items_seleccionados']));
        }

        // dd($query->toSql());

        $data = $query->get();

        return $data;
    }
}
