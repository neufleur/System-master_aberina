<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schemaファサードは、データベーススキーマ操作（テーブルの作成、変更、削除など）を実行するために使用します。
        Schema::create('user_subject', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // 外部キー制約　user_idがusersテーブルに関連つけられることを示す
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade'); // 外部キー制約　関連するユーザーが削除されたときに、このレコードも自動的に削除されることを示す
            $table->timestamps(); //created_atとupdated_atのタイムスタンプカラムを自動的に追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subject');
    }
}
