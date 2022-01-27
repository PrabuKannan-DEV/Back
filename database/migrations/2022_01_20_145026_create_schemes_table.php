<?php

use App\Models\Scheme;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('amount');
            $table->string('duration');
            $table->float('interest');
            $table->timestamps();
        });
        Scheme ::create([
            'name'=>'1L/12',
            'amount'=>'100000',
            'duration'=>'12',
            'interest'=>'0.05',
        ]);
        Scheme ::create([
            'name'=>'2L/24',
            'amount'=>'200000',
            'duration'=>'24',
            'interest'=>'0.08',
        ]);Scheme ::create([
            'name'=>'3L/36',
            'amount'=>'300000',
            'duration'=>'36',
            'interest'=>'0.10',
        ]);Scheme ::create([
            'name'=>'5L/60',
            'amount'=>'500000',
            'duration'=>'60',
            'interest'=>'0.12',
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schemes');
    }
}
