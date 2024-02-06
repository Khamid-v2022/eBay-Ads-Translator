<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        $credentials = $request->only('email','password');

        if (
            $credentials['email'] === env('USER_EMAIL')
            && $credentials['password'] === env('USER_PWD')
        ) {
            session(['authenticated' => true]);

            return redirect()->route('tiendas.index');
        }

        return back()->with('mensaje', 'Credenciales Incorrectas');
    }

}
