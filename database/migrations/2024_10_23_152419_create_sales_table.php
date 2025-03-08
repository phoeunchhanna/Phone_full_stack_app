<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable(); // Must match users.id type
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('due_amount', 10, 2);
            $table->string('status');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->date('due_date')->nullable();
        });
    }
    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
