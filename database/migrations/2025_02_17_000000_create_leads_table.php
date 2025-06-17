<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('description')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'closed', 'canceled'])->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Add indexes for frequently queried columns
            $table->index('status');
            $table->index('assigned_to');
            $table->index('created_by');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
}; 