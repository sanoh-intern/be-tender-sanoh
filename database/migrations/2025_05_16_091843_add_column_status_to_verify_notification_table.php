 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\After;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('verify_notification', function (Blueprint $table) {
            $table->string('status', 25)->after('description')->nullable();
            $table->string('message', 255)->after('status')->nullable();
            $table->unsignedBigInteger('verify_by')->after('message')->nullable();
            $table->foreign('verify_by')->references('id')->on('user')->onDelete('cascade');
            $table->timestamp('verify_at')->after('verify_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verify_notification', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('message');
            $table->dropForeign('verify_notification_verify_by_foreign');
            $table->dropColumn('verify_by');
            $table->dropColumn('verify_at');
        });
    }
};
