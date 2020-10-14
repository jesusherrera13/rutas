<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Municipio;

class MunicipioController extends Controller
{
	public function getData(Request $request) {

        $query = DB::table("municipios as mun")
                    ->leftJoin("estados as est", function($join) {

                        $join->on("est.id_pais", "mun.id_pais");
                        $join->on("est.id_estado", "mun.id_estado");
                    })
                    ->leftJoin("paises as pais", "pais.id_pais", "mun.id_pais")
                    ->select(
                        "mun.id","mun.id_municipio","mun.descripcion","mun.descripcion as municipio",
                        "mun.id_estado","est.descripcion as estado","mun.id_pais","pais.descripcion as pais"
                    )
                    ->where("mun.id_estado", "25")
                    // ->where("asenta.id_municipio", "012")
                    ->orderBy("est.descripcion")
                    ->orderBy("mun.descripcion");

        if($request['term']) {

            // $query->addSelect(DB::raw("concat(loc.descripcion,', ',est.descripcion,', ',pais.descripcion) as localidad_"));
            $query->where(DB::raw("concat(asenta.descripcion,' ',est.descripcion)"),'like', '%'.$request['term'].'%');
            $query->limit(20);
        }
        // else $query->limit(100);

        if($request['mod_op'] == 'existe_registro') {

            $query->where("mun.id_agencia", $request['id_agencia']);
            $query->where("mun.id_contacto", $request['id_contacto']);
        }
        else $query->whereNull("mun.deleted_at");

        if($request['id']) $query->whereIn("mun.id", [$request['id']]);

        if($request['id_localidad']) $query->whereIn("mun.id_localidad", [$request['id_localidad']]);

        if($request['id_pais']) {

            $query->where("mun.id_pais", $request['id_pais']);
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
