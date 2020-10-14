<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\ContactoEmail;

class ContactoEmailController extends Controller
{
	public function store(Request $request) {

        // dd($request);

        /*
        $validateData = $request->validate([
            'id_distrito_local' => 'required',
            'id_distrito_federal' => 'required',
        ]);
        */

        $validateData = $request->validate([
            'id_contacto' => 'required',
            'email' => 'required',
        ]);

        $data = new ContactoEmail();

        $data->id_contacto = $validateData['id_contacto'];
        $data->email = $validateData['email'];
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
            'email' => 'required',
        ]);

        $this->row = ContactoEmail::find($request['id']);

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
}
