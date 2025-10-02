<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add new form_status column
        Schema::table('forms', function (Blueprint $table) {
            $table->enum('form_status', ['active', 'inactive'])->default('active')->after('status');
        });
        
        // Step 2: Copy and transform data from visibility to form_status
        DB::table('forms')->where('visibility', 'public')->update(['form_status' => 'active']);
        DB::table('forms')->where('visibility', 'only_me')->update(['form_status' => 'inactive']);
        
        // Step 3: Drop old visibility column
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Add back visibility column
        Schema::table('forms', function (Blueprint $table) {
            $table->enum('visibility', ['public', 'only_me'])->default('public')->after('status');
        });
        
        // Step 2: Copy and transform data from form_status to visibility
        DB::table('forms')->where('form_status', 'active')->update(['visibility' => 'public']);
        DB::table('forms')->where('form_status', 'inactive')->update(['visibility' => 'only_me']);
        
        // Step 3: Drop form_status column
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn('form_status');
        });
    }
};
