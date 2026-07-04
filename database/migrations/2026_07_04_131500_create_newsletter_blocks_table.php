<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_blocks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('newsletter_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('point_title')->nullable();
            $table->text('point_body')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('newsletters')
            ->select('id', 'image_path', 'description', 'created_at', 'updated_at')
            ->orderBy('id')
            ->get()
            ->each(function (object $newsletter): void {
                $sortOrder = 1;

                if (filled($newsletter->image_path)) {
                    DB::table('newsletter_blocks')->insert([
                        'newsletter_id' => $newsletter->id,
                        'type' => 'image',
                        'point_title' => null,
                        'point_body' => null,
                        'image_path' => $newsletter->image_path,
                        'sort_order' => $sortOrder++,
                        'created_at' => $newsletter->created_at,
                        'updated_at' => $newsletter->updated_at,
                    ]);
                }

                if (filled($newsletter->description)) {
                    DB::table('newsletter_blocks')->insert([
                        'newsletter_id' => $newsletter->id,
                        'type' => 'description',
                        'point_title' => null,
                        'point_body' => $newsletter->description,
                        'image_path' => null,
                        'sort_order' => $sortOrder,
                        'created_at' => $newsletter->created_at,
                        'updated_at' => $newsletter->updated_at,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_blocks');
    }
};
