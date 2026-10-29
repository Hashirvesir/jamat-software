<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Member;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $members = DB::table('members')
            ->whereNull('membership_number')
            ->orWhere('membership_number', '')
            ->orWhere('membership_number', 'LIKE', '03%') // replace phone numbers entered by mistake
            ->get(['id']);

        foreach ($members as $m) {
            DB::table('members')
                ->where('id', $m->id)
                ->update([
                    'membership_number' => 'SHJ-' . str_pad($m->id, 4, '0', STR_PAD_LEFT),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
