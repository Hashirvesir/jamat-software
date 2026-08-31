<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMemberTypeInMembersTable extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->enum('member_type', ['householder', 'non_householder', 'child', 'sibling'])->change();
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->enum('member_type', ['householder', 'non_householder', 'child'])->change();
        });
    }
}