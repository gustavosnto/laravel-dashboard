<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'cnpj',
        'razao',
        'cep',
        'endereco',
        'numero',
        'uf',
        'cidade',
        'email',
        'telefone',
        'nome_socio',
    ];
}
