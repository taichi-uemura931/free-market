<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCategoriesInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('products', function (Blueprint $table) {
            // 既存のcategoryカラムを削除
            $table->dropColumn('category');
        });

        Schema::table('products', function (Blueprint $table) {
            // 新しいcategoryカラムを追加
            $table->enum('category', [
                'ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', 
                '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 
                'アクセサリー', 'おもちゃ', 'ベビー・キッズ'
            ])->after('img_url'); // `img_url`カラムの後に配置
        });
    }

    public function down() {
        Schema::table('products', function (Blueprint $table) {
            // 追加した新しいcategoryカラムを削除
            $table->dropColumn('category');
        });

        Schema::table('products', function (Blueprint $table) {
            // 元のcategoryカラムを復元
            $table->enum('category', [
                'Fashions', 'Electronics', 'Interiors', 'Women', 'Men', 'Cosmetics', 
                'Books', 'Games', 'Sports', 'Kitchen', 'Handmade', 
                'Accessories', 'Toys', 'Baby & Kids'
            ])->after('img_url');
        });
    }
}
