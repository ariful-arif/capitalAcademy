<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       // database/migrations/xxxx_xx_xx_create_password_otps_table.php

Schema::create('password_otps', function (Blueprint $table) {
    $table->id();
    $table->string('email');
    $table->string('otp');
    $table->timestamp('expires_at');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_otps');
    }
};
