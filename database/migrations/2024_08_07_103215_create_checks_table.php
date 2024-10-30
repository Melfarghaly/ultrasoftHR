    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateChecksTable extends Migration
    {
        public function up()
        {
            Schema::create('checks', function (Blueprint $table) {
                $table->id();
                $table->string('check_number')->unique();
                $table->string('account_name');
                $table->string('bank');
                $table->string('cost_center')->nullable();
                $table->date('issue_date');
                $table->date('due_date');
                $table->decimal('check_value', 15, 2);
                $table->string('currency')->default('الجنيه'); // تأكد من استخدام القيمة الافتراضية المناسبة
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('checks');
        }
    }