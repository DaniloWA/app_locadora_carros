<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules(){
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png'
        ];
        /*
                     1      2      3
            unique:marcas,nome,$this->id
            unique:<tabela>,<coluna>,<id>

            1) tabela onde sera feito a pesquisa da existencia unica do valor que passamos
            2) nome da coluna que será pesquisada na tabela - por padrão é o nome do input que passamos
            3) id do registro que será desconsiderado na pesquisa

            Ou seja ele vai pesquisar por todos os registro da Tabela Marcas na coluna nome exceto o id passado como 3 parametro
        */
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da marca já existe',
            'nome.min' => 'O nome deve ter no minimo 3 caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG'
        ];
    }

    public function modelos(){
        //UMA marca POSSUI MUITOS modelos
        return $this->hasMany('App\Models\Modelo');
    }
    
}
