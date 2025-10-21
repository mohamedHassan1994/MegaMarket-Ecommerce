<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSortOrderToAttributesTable extends Migration
{
    public function up()
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('id')->index();
        });

        // backfill sequential sort_order for existing attributes
        $attrs = DB::table('attributes')->orderBy('id')->get();
        $i = 1;
        foreach ($attrs as $attr) {
            DB::table('attributes')->where('id', $attr->id)->update(['sort_order' => $i++]);
        }
    }

    public function down()
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
            $table->dropColumn('sort_order');
        });
    }
}
