<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
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
        return response()->json($this->modelo->with('marca')->get(), 200);
        //all() -> criando um obj de consulta + get() => collection / não permite modificar a consulta (ex: with())
        //get() -> modificar a constula -> collection / permite modificação de consulta ex com o with()
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
            'marca_id' => $request->marca_id,
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

        $modelo = $this->modelo->with('marca')->find($id);
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

/*        if($request->file('imagem')) {
           Storage::disk('public')->delete($modelo->imagem);
       } */

       if($request->file('imagem')) {
        Storage::disk('public')->delete($modelo->imagem);
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');
        }


        $modelo->fill($request->all());
        $request->file('imagem') ? $modelo->imagem = $imagem_urn : '';
        $modelo->save();

      /*  $modelo->update([
        'modelo_id' => $request->modelo_id,
        'nome' => $request->nome,
        'imagem' => $imagem_urn,
        'numero_portas' => $request->numero_portas,
        'lugares' => $request->lugares,
        'air_bag' => $request->air_bag,
        'abs' => $request->abs
        ]); */

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
