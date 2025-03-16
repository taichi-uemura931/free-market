<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category', 255)->change(); // ENUM → VARCHAR(255) に変更
        });
    }

    public function down() {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('category', [
                'ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', 
                '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 
                'アクセサリー', 'おもちゃ', 'ベビー・キッズ'
            ])->change(); // もとの ENUM に戻す
        });
    }
};
