<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'matricula',
        'data_nascimento',
        'sexo',
        'serie',
        'curso',
        'email',
        'telefone',
        'senha'
    ];

    public function frequencias()
    {
        return $this->hasMany(Frequencia::class, 'id_aluno');
    }
}
