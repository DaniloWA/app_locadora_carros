<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelos')){
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;

            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if($request->has('filtro')){
            $marcaRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $marcaRepository->selectAtributos($request->atributos);
        };

        //Metodo estatico
        //$marcas = Marca::all();
        //$marcas = $this->marca->with('modelos')->get();
        return response()->json($marcaRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllData(Request $request, $all)
    {
        if($all == 'all'){
            $marcaRepository = new MarcaRepository($this->marca);

            if($request->has('atributos_modelos')){
                $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;

                $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
            } else {
                $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
            }

            if($request->has('filtro')){
                $marcaRepository->filtro($request->filtro);
            }

            if($request->has('atributos')){
                $marcaRepository->selectAtributos($request->atributos);
            };

            //Metodo estatico
            //$marcas = Marca::all();
            //$marcas = $this->marca->with('modelos')->get();
            return response()->json($marcaRepository->getResultado(), 200);;
        } else{
            dd('chegamos else');
        }
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
    //um objeto do tipo Marca que ?? carregado com base no ID enviado pra rota show
    public function show($id)
    {
        //Sugest??o de tipo, type hinting do php
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Recurso pesquisado n??o existe'], 404);
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
            return response()->json(['erro' => 'Imposs??vel realizar a atualiza????o. O recurso solicitado n??o existe'], 404);
       };

       if($request->method() === 'PATCH') {
            //return ['reste' => 'Verbo Patch'];

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($marca->rules() as $input => $regra) {

                //coletar apenas as regras aplic??veis aos par??metros parciais da requisi????o PATCH
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());
       } else {
            $request->validate($marca->rules(), $marca->feedback());
       };

       //Remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
/*        if($request->file('imagem')) {
           Storage::disk('public')->delete($marca->imagem);
       } */

       if($request->file('imagem')) {
        Storage::disk('public')->delete($marca->imagem);
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
        }


        $marca->fill($request->all());
        $request->file('imagem') ? $marca->imagem = $imagem_urn : '';
        $marca->save();

       /* $marca->update([
        'nome' => $request->nome,
        'imagem' => $imagem_urn
       ]); */


       return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $marca = $this->marca->find($id);

        if($marca === null){
           return response()->json(['erro' => 'Imposs??vel realizar a exclus??o. O recurso solicitado n??o existe'], 404);
        };

        //Remove o arquivo antigo caso um novo arquivo tenha sido enviado no request

        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
          return response()->json(['msg' => 'A marca foi removida com sucesso!'], 201);
    }
}
