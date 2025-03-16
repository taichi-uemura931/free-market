<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('id', 'category_id');
        });
    }

    public function down() {
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('category_id', 'id');
        });
    }
};
