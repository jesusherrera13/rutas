<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;

class LoginController extends Controller
{
    public function store(Request $request) {

        if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {

            $request->session()->forget('lockscreen');
            $request->session()->forget('sidebar-collapse');
        
            return redirect ('/');
        }
        else {

            Session::flash('message-error', 'Datos incorrectos');

            return redirect('login');
        }
    }

    public function logout(Request $request) {
    
        Auth::logout();

        $request->session()->forget('id_usuario');
        $request->session()->forget('sidebar-collapse');

        return redirect('/');
    }

    public function lockscreen(Request $request) {
    
        $request->session()->put('lockscreen', 1);

        return view('lockscreen');
    }

    public function unlock(Request $request) {

        if(Auth::attempt(['id' => Auth::user()->id, 'password' => $request['password']])) {
            
            $request->session()->forget('lockscreen');

            return redirect ('/');
        }
        else {

            if ($request->session()->has('lockscreen')) return redirect('lockscreen');
            else {

                Session::flash('message-error', 'Datos incorrectos');

                return redirect('login');
            }
        }
    }

    public function sidebar(Request $request) {

        if($request['sidebar_collapse']) $request->session()->put('sidebar-collapse', 'sidebar-collapse');
        else $request->session()->forget('sidebar-collapse');
    }
}
