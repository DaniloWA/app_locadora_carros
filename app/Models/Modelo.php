<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules(){
        return [           //verificando se existe na tabela marcas coluna id
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean', // boolean: true | false ou 1, 0 | "1" , "0" strings também aceitas!
            'abs'=> 'required|boolean'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome do modelo já existe',
            'nome.min' => 'O nome deve ter no minimo 3 caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG',
            'numero_portas.digits_between' => 'No minimo 1 porta no maximo 5',
            'lugares.digits_between' => 'No minimo 1 lugar no maximo 20',
        ];
    }

    public function marca(){
        //UM modelo PERTENCE a UMA marca
        return $this->belongsTo('App\Models\Marca');
    }
}
