<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $t) {
            $t->timestamp('last_activity_at')->nullable()->after('active');
        });
        Schema::table('products', function (Blueprint $t) {
            $t->foreignId('supplier_id')->nullable()->after('id')->constrained()->restrictOnDelete();
        });
        DB::table('suppliers')->whereNull('last_activity_at')->update(['last_activity_at' => DB::raw('COALESCE(updated_at, created_at)')]);
        // Existing products inherit the supplier that most recently delivered them.
        foreach (DB::table('purchase_order_items')->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id')->orderBy('purchase_orders.id')->get(['purchase_order_items.product_id', 'purchase_orders.supplier_id']) as $row) {
            DB::table('products')->where('id', $row->product_id)->update(['supplier_id' => $row->supplier_id]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $t) {
            $t->dropConstrainedForeignId('supplier_id');
        });
        Schema::table('suppliers', fn (Blueprint $t) => $t->dropColumn('last_activity_at'));
    }
};
