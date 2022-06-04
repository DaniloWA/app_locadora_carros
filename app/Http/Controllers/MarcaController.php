<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function __construct(Marca $marca){
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Metodo estatico
        //$marcas = Marca::all();
        $marcas = $this->marca->all();
        return response()->json($marcas, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //Metodo estatico
        //$marca = Marca::create($request->all());

        //nome
        //imagem

        $request->validate($this->marca->rules() ,$this->marca->feedback());
        //stateless protocologo

        //dd($request->nome);
        //dd($request->get('nome'));
        //dd($request->input('nome'));

        //dd($request->imagem);
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

      /*        outra forma de fazer o mesmo que o de cima
        $marca->nome = $request->nome
        $marca->imagem = $imagem_urn
        $marca->save() */

        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    //um objeto do tipo Marca que é carregado com base no ID enviado pra rota show
    public function show($id)
    {
        //Sugestão de tipo, type hinting do php
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        };
        return response()->json($marca, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
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
        /*
            print_r($request->all()); // os dados atualizados
            print_r($marca->getAttributes()); // os dados antigos
            $marca->update($request->all());
       */

       $marca = $this->marca->find($id);

       if($marca === null){
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
       };

       if($request->method() === 'PATCH') {
            //return ['reste' => 'Verbo Patch'];

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($marca->rules() as $input => $regra) {

                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            dd($regrasDinamicas);

            $request->validate($regrasDinamicas, $marca->feedback());
       } else {
            $request->validate($marca->rules(), $marca->feedback());
       };

       $marca->update($request->all());

       return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if($marca === null){
           return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        };

        $marca->delete();
          return response()->json(['msg' => 'A marca foi removida com sucesso!'], 201);
    }
}
