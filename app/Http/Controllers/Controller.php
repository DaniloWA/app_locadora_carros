<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->modelo->all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate($this->modelo->rules() ,$this->modelo->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos','public');

        $modelo = $this->modelo->create([
            'modelo_id' => $request->modelo_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    //um objeto do tipo modelo que é carregado com base no ID enviado pra rota show
    public function show($id)
    {
        //Sugestão de tipo, type hinting do php
        $modelo = $this->modelo->find($id);
        if($modelo === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        };
        return response()->json($modelo, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $modelo = $this->modelo->find($id);

       if($modelo === null){
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
       };

       if($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            foreach($modelo->rules() as $input => $regra) {
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $modelo->feedback());
       } else {
            $request->validate($modelo->rules(), $modelo->feedback());
       };

       if($request->file('imagem')) {
           Storage::disk('public')->delete($modelo->imagem);
       }

       $imagem = $request->file('imagem');
       $imagem_urn = $imagem->store('imagens/modelos','public');

       $modelo->update([
        'modelo_id' => $request->modelo_id,
        'nome' => $request->nome,
        'imagem' => $imagem_urn,
        'numero_portas' => $request->numero_portas,
        'lugares' => $request->lugares,
        'air_bag' => $request->air_bag,
        'abs' => $request->abs
        ]);

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null){
           return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        };

        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
          return response()->json(['msg' => 'A modelo foi removida com sucesso!'], 201);
    }
}
