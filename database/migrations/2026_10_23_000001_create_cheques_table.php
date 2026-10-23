<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ensure status column in payments table if not exists
        if (!Schema::hasColumn('payments', 'status')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('status', 50)->default('Paid')->after('payment_method');
            });
        }

        // 2. Create cheques table
        if (!Schema::hasTable('cheques')) {
            Schema::create('cheques', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['IN', 'OUT'])->default('IN');
                $table->string('cheque_number', 100);
                $table->date('cheque_date');
                $table->date('received_or_issued_date');
                $table->decimal('amount', 15, 2);
                $table->string('payer_bank', 255)->nullable(); // Bank drawn on (for IN)
                $table->string('payer_branch', 255)->nullable();
                $table->string('payee_name', 255)->nullable(); // Vendor / payee name (for OUT)
                $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
                $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
                $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
                $table->foreignId('expense_id')->nullable()->constrained('expenses')->nullOnDelete();
                $table->foreignId('bank_id')->nullable()->constrained('banks')->nullOnDelete(); // Company Bank Account (for OUT: issue bank; for IN: deposit/clearance bank)
                $table->date('deposit_date')->nullable();
                $table->date('clearance_date')->nullable();
                $table->date('return_date')->nullable();
                $table->string('return_reason', 255)->nullable();
                $table->string('status', 50)->default('In Hand'); // In Hand, Deposited, Realized, Returned, Cancelled, Issued
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['type', 'status']);
                $table->index('cheque_number');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cheques');
        if (Schema::hasColumn('payments', 'status')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
