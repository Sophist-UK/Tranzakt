<?php
/**
 * Create the Tranzakt metadata schema.
 *
 * This version of the file holds the definitions for metadata relating to:
 * a. System wide definitions for Connections/Databases, Apps, Tags
 * b. Tables to hold all the Metadata needed to define user app Tables.
 *
 * Seeds for a Sample app are defined in database/seeds/TranzaktSampleApp.php
 *
 * Create tables by running `php artisan migrate --path=database\schema\TranzaktSchema.php`
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\TranzaktSampleAppSeeder

return new class extends Migration
{
	// Connection
	private $connection_type;
	private $is_mysql;
	private $is_postgres;
	private $is_sqlserver;
	private $is_sqlite;

	// get driver name for DBMS specific actions
	public function __construct() {
		$this->connection_type = Schema::getConnection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
		echo "Tranzakt schema processing...\nConnection type: " . $this->connection_type . PHP_EOL;
		$this->is_mysql     = $this->connection_type == 'mysql';
		$this->is_postgres  = $this->connection_type == 'postgres';
		$this->is_sqlserver = $this->connection_type == 'sqlserver';
		$this->is_sqlite    = $this->connection_type == 'sqlite';
	}

	private function common_columns(Blueprint $table, string $comment = '')
	{
		if ($this->is_mysql) {
			$table->engine = 'InnoDB';
		}
		$table->charset = 'utf8mb4';
		if ($comment <> '' and ($this->is_mysql or $this->is_postgres)) {
			$table->comment($comment);
		}

		$table->id();
		$table->bigInteger('user_created_id')->nullable();  // We store details of which user created the record...
		$table->bigInteger('user_modified_id')->nullable(); // ... and who last updated it.
		$table->timestamps(); // Standard laravel created / last updated timestamps.
		$table->softDeletes(); // All records can be sent to trash and recovered.

		// Fields to limit access to specific developers / teams will be added here

		// $table->primary('id'); // Primary key is defined automatically.
	}

	public function up()
	{
		$this->down();
		$this->create_system_metadata();
		$this->create_table_metadata();
	}

	/**
	 * Create system wide tables.
	 */
	private function create_system_metadata()
	{
		Schema::create('Tranzakt_Databases', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Tranzact supports multiple database connections - default Laravel connection is connection 1'
			);

			$table->string('connection', 20);
			$table->string('host');
			$table->unsignedSmallInteger('port');
			$table->string('database', 64);
			$table->string('username', 64);
			$table->string('password', 64)->nullable();
			$table->text('notes');

			$table->unique(['host', 'port', 'database', 'deleted_at'], 'tranzakt_databases_unique');
		});

		Schema::create('Tranzakt_Applications', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Tranzact supports development of multiple applications with independent tables'
			);

			$table->string('app');
			$table->string('table_prefix', 16);  // The prefix is added to the app_specific table name
			$table->text('notes');

			$table->unique(['app', 'deleted_at'], 'tranzakt_applications_unique_app_name');
			$table->unique(['table_prefix', 'deleted_at'], 'tranzakt_applications_unique_table_prefix');
		});

		Schema::create('Tranzakt_Tags', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Tag names and descriptions',
				False
			);

			$table->foreignId('app_id')->constrained('Tranzakt_Applications');
			$table->string('tag', 64);
			$table->text('notes');

			$table->unique(['app_id', 'tag', 'deleted_at'], 'tranzakt_tags_unique_app_id_tag');
		});

		Schema::create('Tranzakt_Taggables', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Link Tags with tagged items',
				False
			);

			$table->foreignId('tag_id');
			$table->morphs('taggable');

			$table->foreign('tag_id')->references('id')->on('Tranzakt_Tags');

			$table->unique(['tag_id', 'taggable_id', 'taggable_type', 'deleted_at'], 'tranzakt_taggables_unique_tag_id_taggable');
		});
	}

	/**
	 * Create tables metadata tables.
	 */
	private function create_table_metadata()
	{
		Schema::create('Tranzakt_Table_Areas', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Tranzact allows you to group tables into areas for diagramming and documentation purposes'
			);

			$table->foreignId('application_id');
			$table->string('area', 64);
			$table->string('description');
			$table->text('notes');

			$table->foreign('application_id')->references('id')->on('Tranzakt_Applications');

			$table->unique(['application_id', 'area', 'deleted_at'], 'tranzakt_table_areas_unique_app_id_area');
		});

		Schema::create('Tranzakt_Tables', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'This table holds the list of tables'
			);

			$table->foreignId('application_id');
			$table->foreignId('database_id');
			$table->string('table', 64);
			$table->string('full_name', 64);
			$table->string('description')->nullable();
			$table->string('engine', 16)->nullable();
			$table->string('charset', 16)->nullable();
			$table->string('collation', 16)->nullable();
			$table->string('comment')->nullable();
			$table->boolean('timestamps')->nullable();
			$table->boolean('whostamps')->nullable();
			$table->boolean('softdeletes')->nullable();
			$table->json('constraints')->nullable();
			$table->json('options')->nullable();
			$table->text('notes');
			$table->foreignId('area_id')->nullable();
			if ($this->is_mysql or $this->is_postgres) {
				$table->json('canvas')->nullable()
							->comment('Holds diagramatic data');
			} else {
				$table->json('canvas')->nullable();
			}

			$table->foreign('application_id')->references('id')->on('Tranzakt_Applications');
			$table->foreign('database_id')->references('id')->on('Tranzakt_Databases');
			$table->foreign('area_id')->references('id')->on('Tranzakt_Table_Areas');

			$table->unique(['application_id', 'table', 'deleted_at'], 'tranzakt_tables_unique_app_id_table');
			$table->unique(['database_id', 'full_name', 'deleted_at'], 'tranzakt_tables_unique_db_id_fullname');
		});

		Schema::create('Tranzakt_Table_Columns', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'This table holds details of every column in every table.'
			);

			$table->foreignId('table_id');
			$table->string('column', 64);
			if ($this->is_mysql or $this->is_postgres) {
				$table->string('type', 64)->comment('Laravel column type rather than SQL');
			} else {
				$table->string('type', 64);
			}
			$table->boolean('nullable')->default(False);
			$table->string('comment')->nullable();
			$table->string('default_value')->nullable();
			$table->enum('generated_value_storage', ['VIRTUAL', 'STORED'])->nullable();
			$table->string('generated_as')->nullable();
			$table->json('constraints')->nullable();
			$table->json('options')->nullable();
			$table->text('notes');

			$table->foreign('table_id')->references('id')->on('Tranzakt_Tables');

			$table->unique(['table_id', 'column', 'deleted_at'], 'tranzakt_columns_unique_table_id_column');
		});

		Schema::create('Tranzakt_Table_Indexes', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Details of all indexes on all tables.'
			);

			$table->foreignId('table_id');
			$table->enum('type', ['primary', 'unique', 'non-unique']);
			$table->string('name', 64)->nullable();
			$table->json('index_columns');
			$table->enum('sort_order', ['ASC', 'DESC'])->default('ASC');
			$table->text('notes');

			$table->foreign('table_id')->references('id')->on('Tranzakt_Tables');
		});

		Schema::create('Tranzakt_Table_Seeds', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Tranzakt supports the Laravel concept of seeds i.e. rows created when the tables are created'
			);

			$table->foreignId('table_id');
			$table->json('row_values');

			$table->foreign('table_id')->references('id')->on('Tranzakt_Tables');
		});

		Schema::create('Tranzakt_Table_Relationships', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'This table holds details of the relationships between tables and supports Laravel polymorphic relationships'
			);
			$table->string('relationship_name', 64);
			$table->string('comment');
			$table->enum('cardinality', ['0..*', '0..1', '1..*', '1..1']);
			$table->foreignId('primary_table_id');
			$table->foreignId('foreign_table_id');
			$table->json('columns');
			$table->boolean('foreign_mandatory');
			$table->text('notes');
			if ($this->is_mysql or $this->is_postgres) {
				$table->json('canvas')->nullable()
							->comment('Holds diagramatic data');
			} else {
				$table->json('canvas')->nullable();
			}

			$table->foreign('primary_table_id')->references('id')->on('Tranzakt_Tables');
			$table->foreign('foreign_table_id')->references('id')->on('Tranzakt_Tables');
		});

		echo "Tranzakt schema tables defined." .PHP_EOL;
	}

	public function down()
	{
		// Because of foreign key relationships, tables need to be dropped in reverse order
		$this->drop_table_metadata();
		$this->drop_system_metadata();
	}

	/**
	 * Drop tables metadata tables.
	 */
	private function drop_table_metadata()
	{
		Schema::dropIfExists('Tranzakt_Table_Relationships');
		Schema::dropIfExists('Tranzakt_Table_Seeds');
		Schema::dropIfExists('Tranzakt_Table_Indexes');
		Schema::dropIfExists('Tranzakt_Table_Columns');
		Schema::dropIfExists('Tranzakt_Tables');
		Schema::dropIfExists('Tranzakt_Table_Areas');
	}

	/**
	 * Drop system wide tables.
	 */
	private function drop_system_metadata()
	{
		Schema::dropIfExists('Tranzakt_Taggables');
		Schema::dropIfExists('Tranzakt_Tags');
		Schema::dropIfExists('Tranzakt_Applications');
		Schema::dropIfExists('Tranzakt_Databases');
	}
};