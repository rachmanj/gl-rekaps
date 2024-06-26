<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_journals', function (Blueprint $table) {
            $table->id();
            $table->date('create_date')->nullable();
            $table->date('posting_date')->nullable();
            $table->string('tx_num')->nullable();
            $table->string('doc_num')->nullable();
            $table->string('doc_type')->nullable();
            $table->string('project')->nullable();
            $table->string('department')->nullable();
            $table->foreignId('account_id')->nullable();
            $table->decimal('debit', 15, 2)->nullable();
            $table->decimal('credit', 15, 2)->nullable();
            $table->decimal('fc_debit', 15, 2)->nullable();
            $table->decimal('fc_credit', 15, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->integer('batch')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_journals');
    }
};
