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
            $table->unsignedBigInteger('user_id'); // unsignedBigIntegerを使用
            $table->unsignedBigInteger('subject_id');
            $table->timestamps(); //created_atとupdated_atのタイムスタンプカラムを自動的に追加

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // 外部キー制約　user_idがusersテーブルに関連つけられることを示す
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade'); // 外部キー制約　関連するユーザーが削除されたときに、このレコードも自動的に削除されることを示す
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
