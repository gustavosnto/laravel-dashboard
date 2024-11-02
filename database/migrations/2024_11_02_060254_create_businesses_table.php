<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('cnpj')->unique();
            $table->string('razao');
            $table->string('cep');
            $table->string('endereco');
            $table->string('numero');
            $table->string('uf', 2);
            $table->string('cidade');
            $table->string('email')->unique();
            $table->string('telefone');
            $table->string('nome_socio');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('businesses');
    }
}
