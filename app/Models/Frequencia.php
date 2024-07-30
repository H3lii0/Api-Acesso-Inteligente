<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frequencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_aluno',
        'registro_acesso',
        'data_acesso',
        'hora_acesso',
        'dia_semana',
    ];
}
