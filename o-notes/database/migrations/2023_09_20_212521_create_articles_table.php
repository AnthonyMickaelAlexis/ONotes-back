<?php

use App\Models\User;
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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string("title", 100);
            $table->string("subtitle");
            $table->string("slug")->default('');
            $table->text("resume")->nullable();
            $table->text("text_content");
            $table->text("file_content")->nullable();
            $table->string("banner")->nullable();
            $table->string("status")->default('draft');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('subcategory_id')->constrained('sub_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
