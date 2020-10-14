<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	public function __construct() {

		$this->middleware('auth');
	}

    public function index(Request $request) {

		if ($request->session()->has('lockscreen')) return redirect('lockscreen');
		else {

	    	$page_title = 'Usuarios';
        	$content_header = 'Usuarios';

        	$data = User::all();

        	return view('usuarios.inicio', compact('page_title','content_header','data'));
		}
    }

    public function dashboard(Request $request) {

    	// dd($request);

		if ($request->session()->has('lockscreen')) return redirect('lockscreen');
		else {

	    	$titulo = 'Dashboard';

	    	return view('inicio.dashboard');
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

        if($request->ajax()) {
            
            return response()->json([
                'id' => $data->id,
                'message' => 'El registro ha sido creado satisfactoriamente.',
            ]);
        }
    }

    public function getData(Request $request) {

    	$data = User::all();

    	/*$query = DB::table("users")
    				->select("id","descripcion","no_distrito")
    				->orderBy("no_distrito");

        if($request['id']) $query->where("id", $request['id']);

        $data = $query->get();*/

        // $password = Hash::make('villegas');

    	if($request['dataType'] == "json") return response()->json($data);
        else return $data;
    }
}
