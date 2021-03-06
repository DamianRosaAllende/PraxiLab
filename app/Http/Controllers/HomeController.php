<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Persona;
use Illuminate\Http\Request;
use App\Http\Requests;
use Exception;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    private $path = 'persona';

	public function index(){

        return view('index');
    }

    protected $redirectTo = '/index';

    protected function validator(array $persona)
    {
        return Validator::make($persona, [
            'nombre' => 'required|string|max:255',
            'mail' => 'required|string|mail|max:255|unique:persona',
            'contrasena' => 'required|string|min:6|confirmed',
        ]);
    }

    public function registro (){

        $persona = new Persona;

        return view('registro', compact('persona'));
    }

	public function usuarioCreado(Request $request){

        try{
            $persona = New Persona();
            $persona->nombre = $request->nombre;
            $persona->apellido = $request->apellido;
            $persona->telefono = $request->telefono;
            $persona->mail = $request->mail;
            $persona->contrasena = bcrypt($request->contrasena);

            $persona->save();
        
            return view('index', compact('persona'));
        }
        catch(Exception $e){

            return "Fatal error -".$e->getMessage();
        }
    }

    public function inicioSesion(){
        return view('inicioSesion');
    }

    public function login(){
        $persona = [
                'mail' => Input::get('mail'),
                'contrasena' => Input::get('contrasena')
            ];
     
            // Verificamos los datos
            if (Auth::attempt($persona)) 
            {
                // Si nuestros datos son correctos mostramos la página de inicio
                return Redirect::intended('/');
            }
            // Si los datos no son los correctos volvemos al login y mostramos un error
            return Redirect::back()->with('error_message', 'El usuario no existe')->withInput();
        
    }
}