<div class="code-snippet">
    <h3>database/migrations/2024_07_26_182431_create_disabled_{{ $names["table_name"] }}_table_.php</h3>
    <pre id="disabled_migration"><code>

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class CreateDisabled{{ $names["plural_model_name"] }}Table extends Migration
  {
      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
          Schema::create('disabled_{{ $names["table_name"] }}', function (Blueprint $table) {
              $table->id();

              $table->foreignId('{{ $names["singular_table_name"] }}_id')
              ->constrained('{{ $names["table_name"] }}')
              ->onDelete('cascade');

              $table->unsignedBigInteger("business_id");

              $table->unsignedBigInteger("created_by")->nullable();


              $table->timestamps();
          });
      }

      /**
       * Reverse the migrations.
       *
       * @return void
       */
      public function down()
      {
          Schema::dropIfExists('disabled_letter_templates');
      }
  }




</code></pre>
    <button class="copy-button" onclick="copyToClipboard('disabled_migration')">Copy</button>
</div>
