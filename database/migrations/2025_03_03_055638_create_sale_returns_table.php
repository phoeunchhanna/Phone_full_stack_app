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
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('due_amount', 10, 2);
            $table->string('status');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->text('reason')->nullable();
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
        Schema::dropIfExists('sale_returns');
    }
};
