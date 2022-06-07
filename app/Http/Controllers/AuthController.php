<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){

        $credenciais = $request->all(['email', 'password']);

        //Autentificação do usuario (email e senha)
        $token =auth('api')->attempt($credenciais);

        if($token){ //user autenticado com sucesso!
            return response()->json(['token' => $token], 200);


        } else {  //erro user ou senha
            return response()->json(['erro' => 'Usuário ou senha inválido!'], 403);

            // 401 = Unauthorized -> não autorizado. User pode estar logado mas não tem autorização
            // 403 = forbideen -> proibido (login inválido)
        }

        //retornar um JWT - Json Web Token
        return 'Login';
    }

    public function logout(){
        return 'logout';
    }

    public function refresh(){
        return 'refresh';
    }

    public function me(){
        return response()->json(auth()->user());
    }
}
