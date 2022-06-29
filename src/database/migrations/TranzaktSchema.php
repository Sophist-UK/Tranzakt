<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTranzaktTables extends Migration {

	public function common_columns(Blueprint $table, bool $tags = True)
	{
		$table->engine = 'InnoDB';
		$table->charset = 'utf8mb4';

		$table->increments('id');
		$table->bigInteger('user_created_id')->nullable();
		$table->bigInteger('user_modified_id')->nullable();
		$table->timestamps();
		$table->softDeletes();
		if ($tags) {
			$table->morphs('taggable');
		}
	}

	public function up()
	{

		Schema::create('Tranzakt_Tags', function(Blueprint $table) {
			$this->common_columns($table, False);

		});

		Schema::create('Tranzakt_Databases', function(Blueprint $table) {
			$this->common_columns($table);

			$table->string('connection', 20);
			$table->string('host');
			$table->unsignedSmallInteger('port');
			$table->string('database', 64);
			$table->string('username', 64);
			$table->string('password', 64)->nullable();
		});

		Schema::create('Tranzakt_Applications', function(Blueprint $table) {
			$this->common_columns($table);

			$table->foreignId('database_id');
			$table->string('application');

			$table->foreign('database_id')->references('id')->on('Tranzakt_Databases');
		});

		Schema::create('Tranzakt_App_Areas', function(Blueprint $table) {
			$this->common_columns($table);

		});

		Schema::create('Tranzakt_Tables', function(Blueprint $table) {
			$this->common_columns($table);

			$table->foreignId('application_id');
			$table->string('table', 64);

			$table->foreign('application_id')->references('id')->on('Tranzakt_Applications');
		});

		Schema::create('Tranzakt_Table_Columns', function(Blueprint $table) {
			$this->common_columns($table);

		});

		Schema::create('Tranzakt_Table_Indexes', function(Blueprint $table) {
			$this->common_columns($table);

		});

		Schema::create('Tranzakt_Table_Seeds', function(Blueprint $table) {
			$this->common_columns($table);

		});

		Schema::create('Tranzakt_Table_Relationships', function(Blueprint $table) {
			$this->common_columns($table);

		});

		Schema::create('Tranzakt_Constraints', function(Blueprint $table) {
			$this->common_columns($table);

		});
	}

	public function down()
	{
		Schema::dropIfExists('TranzaktDefault');
		Schema::dropIfExists('TranzaktDefault');
		Schema::dropIfExists('TranzaktDefault');
		Schema::dropIfExists('TranzaktDefault');
		Schema::dropIfExists('TranzaktDefault');
		Schema::dropIfExists('TranzaktDefault');
		Schema::dropIfExists('TranzaktDefault');
		Schema::dropIfExists('TranzaktDefault');
		Schema::dropIfExists('TranzaktDefault');
		Schema::dropIfExists('TranzaktDefault');
		Schema::dropIfExists('TranzaktDefault');
	}
}