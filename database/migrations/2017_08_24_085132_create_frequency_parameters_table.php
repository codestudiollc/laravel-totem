<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Studio\Totem\Database\TotemMigration;

class CreateFrequencyParametersTable extends TotemMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection($this->getConnection())
            ->create(TOTEM_TABLE_PREFIX.'frequency_parameters', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('frequency_id');
                $table->string('name');
                $table->string('value');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->getConnection())
            ->dropIfExists(TOTEM_TABLE_PREFIX.'frequency_parameters');
    }
}
