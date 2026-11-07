<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dlss5_control_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document_type', 8); // ta | oc
            $table->unsignedBigInteger('sequence');
            $table->longText('body_b64');
            $table->text('signature_b64');
            $table->string('key_id', 64);
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['document_type', 'is_active', 'sequence']);
        });

        Schema::create('dlss5_control_audit_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('prev_hash', 128)->nullable();
            $table->string('row_hash', 128);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('ip_address', 64)->nullable();
            $table->string('action', 128);
            $table->text('diff_summary')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        if (Schema::hasTable('dlss5_app_releases')) {
            Schema::table('dlss5_app_releases', function (Blueprint $table) {
                $table->text('external_download_urls')->nullable();
                $table->string('download_provider', 64)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('dlss5_app_releases')) {
            Schema::table('dlss5_app_releases', function (Blueprint $table) {
                $table->dropColumn(['external_download_urls', 'download_provider']);
            });
        }
        Schema::dropIfExists('dlss5_control_audit_log');
        Schema::dropIfExists('dlss5_control_documents');
    }
};
