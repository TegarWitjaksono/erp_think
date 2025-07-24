    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\DB;

    return new class extends Migration {
        /**
         * Jalankan migration.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('master_kategori', function (Blueprint $table) {
                $table->id('id_kategori'); // PRIMARY KEY dengan AUTO_INCREMENT
                $table->string('nama_kategori', 255);
                $table->enum('tipe', ['text', 'foto', 'video']); // Menyimpan tipe kategori
                $table->integer('sts')->default(1);
                $table->timestamps(); // created_at & updated_at
            });

            // Insert default values
            DB::table('master_kategori')->insert([
                ['nama_kategori' => 'Kategori Teks', 'tipe' => 'text', 'sts' => 1],
                ['nama_kategori' => 'Kategori Foto', 'tipe' => 'foto', 'sts' => 1],
                ['nama_kategori' => 'Kategori Video', 'tipe' => 'video', 'sts' => 1],
            ]);
        }

        /**
         * Rollback migration.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('master_kategori');
        }
    };
