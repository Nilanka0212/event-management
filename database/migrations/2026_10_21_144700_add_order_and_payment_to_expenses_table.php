<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'order_id')) {
                $table->foreignId('order_id')->nullable()->after('event_id')->constrained('orders')->nullOnDelete();
            }
            if (!Schema::hasColumn('expenses', 'payment_method')) {
                $table->string('payment_method')->default('cash')->after('amount');
            }
            if (!Schema::hasColumn('expenses', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('expenses', 'cheque_no')) {
                $table->string('cheque_no')->nullable()->after('bank_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
            if (Schema::hasColumn('expenses', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('expenses', 'bank_name')) {
                $table->dropColumn('bank_name');
            }
            if (Schema::hasColumn('expenses', 'cheque_no')) {
                $table->dropColumn('cheque_no');
            }
        });
    }
};
