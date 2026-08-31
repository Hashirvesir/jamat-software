<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('cnic')->unique()->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->default('single');
            $table->unsignedBigInteger('community_id');
            $table->enum('member_type', ['householder', 'non_householder', 'child', 'sibling']);
            $table->unsignedBigInteger('spouse_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_deceased')->default(false);
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('community_id')->references('id')->on('communities')->onDelete('restrict');
            $table->foreign('parent_id')->references('id')->on('members')->onDelete('set null');
            $table->foreign('spouse_id')->references('id')->on('members')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
}