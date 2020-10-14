<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\ContactoTelefono;

class ContactoTelefonoController extends Controller
{
	public function store(Request $request) {

        $validateData = $request->validate([
            'id_contacto' => 'required',
            'no_telefono' => 'required',
        ]);

        $data = new ContactoTelefono();

        $data->id_contacto = $validateData['id_contacto'];
        $data->no_telefono = $validateData['no_telefono'];
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
            'id_contacto' => 'required',
            'no_telefono' => 'required',
        ]);

        $this->row = ContactoTelefono::find($request['id']);

        $this->row->fill($request->all());
        $this->row->user_id_update = Auth::user()->id;
        
        $this->row->save();

        $rick = new Request();

        $rick->replace([
            'id_contacto' => $request['id'],
            'telefonos' => $request['telefonos'],
            'emails' => $request['emails'],
        ]);

        if($request->ajax()) {
            
            return response()->json([
                'id' => $request['id'],
                'message' => 'El registro ha sido actualizado satisfactoriamente.',
            ]);
        }
    }
}
