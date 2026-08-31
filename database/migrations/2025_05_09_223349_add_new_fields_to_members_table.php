<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToMembersTable extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('blood_group')->nullable()->after('description');
            $table->integer('card_received')->nullable()->after('blood_group');
            $table->string('b_form_number')->nullable()->after('card_received'); // B-Form Number as string
            $table->string('country')->nullable()->after('b_form_number');
            $table->date('death_date')->nullable()->after('is_deceased');
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['blood_group', 'card_received', 'b_form_number', 'country', 'death_date']);
        });
    }
}