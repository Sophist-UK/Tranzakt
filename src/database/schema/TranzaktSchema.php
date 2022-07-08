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
 * Remove tables for recreation by running: php artisan migrate:rollback --path=database\schema\TranzaktSchema.php
 * Create tables by running: php artisan migrate --path=database\schema\TranzaktSchema.php
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * The database connection that should be used by the migration.
	 *
	 * @var string
	 */
	protected $connection;

	// Connection type - used for RDBMS specific definitions
	private $connection_type;
	private $is_mysql;
	private $is_postgres;
	private $is_sqlserver;
	private $is_sqlite;

	/**
	 * Determine the database connection for Tranzakt internal tables.
	 *
	 * Also determine RDBMS type in case we need to do RDBMS specific stuff.
	 */
	public function __construct() {
		// Get the Tranzakt default connection
		$this->connection = config('database.tranzakt');

		// Get the connection type
		$this->connection_type = Schema::getConnection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
		echo "Tranzakt schema processing..." . PHP_EOL .
			"\tConnection:" . $this->connection . PHP_EOL .
			"\tConnection type: " . $this->connection_type . PHP_EOL;
		$this->is_mysql     = $this->connection_type == 'mysql';
		$this->is_postgres  = $this->connection_type == 'postgres';
		$this->is_sqlserver = $this->connection_type == 'sqlserver';
		$this->is_sqlite    = $this->connection_type == $this->connection;
	}

	/**
	 * Define columns that are common to all tables.
	 */
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
		$table->softDeletes(); // All records can be sent to trash and recovered.
		$table->timestamps(); // Standard laravel created / last updated timestamps.
		$table->userstamps(); // sqits/laravel-userstamps
		$table->softUserstamps();  // sqits/laravel-userstamps

		// Fields to limit access to specific developers / teams will be added here
	}

	/**
	 * Create tables
	 */
	public function up()
	{
		// Remove tables if they already exist.
		$this->down();

		// Create tables.
		$this->create_general_metadata();
		$this->create_table_metadata();
	}

	/**
	 * Create Tranzakt-general tables.
	 */
	private function create_general_metadata()
	{
		// Databases
		Schema::connection($this->connection)
		->create('tranzakt_databases', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Tranzakt supports multiple database connections'
			);

			$table->string('name', 64);
			$table->json('connection', 64);
			$table->text('notes')->nullable();

			$table->unique(['name', 'deleted_at'], 'tranzakt_database_unique_name');
		});

		// Applications
		Schema::connection($this->connection)
		->create('tranzakt_applications', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Tranzakt supports development of multiple applications with independent tables'
			);

			$table->string('name');
			$table->string('table_prefix', 16);  // The prefix is added to the app_specific table name
			$table->text('notes')->nullable();

			$table->unique(['name', 'deleted_at'], 'tranzakt_applications_unique_name');
			$table->unique(['table_prefix', 'deleted_at'], 'tranzakt_applications_unique_table_prefix');
		});

		// Tags
		Schema::connection($this->connection)
		->create('tranzakt_tags', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Tag names and descriptions',
				False
			);

			$table->foreignId('application_id');
			$table->string('name', 64);
			$table->text('notes')->nullable();

			$table->foreign('application_id')->references('id')->on('tranzakt_applications')
				->onDelete('cascade');

			$table->unique(['application_id', 'name', 'deleted_at'], 'tranzakt_tags_unique_app_id_name');
		});

		// Taggables
		Schema::connection($this->connection)
		->create('tranzakt_taggables', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Link Tags with tagged items',
				False
			);

			$table->foreignId('tag_id');
			$table->morphs('taggable');

			$table->foreign('tag_id')->references('id')->on('tranzakt_tags')
				->onDelete('cascade');

			$table->unique(['tag_id', 'taggable_id', 'taggable_type', 'deleted_at'], 'tranzakt_taggables_unique_tag_id_taggable');
		});
	}

	/**
	 * Create tables metadata tables.
	 */
	private function create_table_metadata()
	{
		// Table areas - for ER Diagrams, boxes around grouped tables
		Schema::connection($this->connection)
		->create('tranzakt_table_areas', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Tranzakt allows you to group tables into areas for diagramming and documentation purposes'
			);

			$table->foreignId('application_id');
			$table->string('name', 64);
			$table->text('notes')->nullable();

			$table->foreign('application_id')->references('id')->on('tranzakt_applications')
				->onDelete('cascade');

			$table->unique(['application_id', 'name', 'deleted_at'], 'tranzakt_table_areas_unique_app_id_name');
		});

		// Tables
		Schema::connection($this->connection)
		->create('tranzakt_tables', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'This table holds the list of tables'
			);

			$table->foreignId('application_id');
			$table->foreignId('database_id');
			$table->string('name', 64)->comment('Application table name');
			$table->string('table_name', 64)->comment('Database table name with application prefix');
			$table->string('comment')->nullable();
			$table->boolean('timestamps')->default(true);
			$table->boolean('userstamps')->default(true);
			$table->boolean('softdeletes')->default(false);
			$table->json('constraints')->nullable()
						->comment(
							"JSON containing table-level constraints. " .
							"These will be used for SQL checks, Laravel validations and Vue validations"
						);
			// Probably should put these in JSON
			// $table->string('engine', 16)->nullable();
			// $table->string('charset', 16)->nullable();
			// $table->string('collation', 16)->nullable();
			$table->json('options')->nullable();
			$table->text('notes')->nullable();
			$table->foreignId('area_id')->nullable();

			$table->json('canvas')->nullable()
						->comment('Holds ER diagram position data');

			$table->foreign('application_id')->references('id')->on('tranzakt_applications')
				->onDelete('cascade');
			$table->foreign('database_id')->references('id')->on('tranzakt_databases')
				->onDelete('cascade');
			$table->foreign('area_id')->references('id')->on('tranzakt_table_areas')
				->onDelete('cascade');

			$table->unique(['application_id', 'name', 'deleted_at'], 'tranzakt_tables_unique_app_id_name');
			$table->unique(['database_id', 'table_name', 'deleted_at'], 'tranzakt_tables_unique_db_id_fullname');
		});

		// Columns
		Schema::connection($this->connection)
		->create('tranzakt_table_columns', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'This table holds details of every column in every table.'
			);

			$table->foreignId('table_id');
			$table->string('name', 64);
			$table->string('type', 64)->comment('Laravel column type rather than SQL');
			$table->boolean('nullable')->default(False);
			$table->string('description')->nullable();
			$table->string('default_value')->nullable();
			$table->enum('generated_value_storage', ['VIRTUAL', 'STORED'])->nullable();
			$table->string('generated_as')->nullable();
			$table->json('constraints')->nullable();
			$table->json('options')->nullable();
			$table->text('notes')->nullable();

			$table->foreign('table_id')->references('id')->on('tranzakt_tables')
				->onDelete('cascade');

			$table->unique(['table_id', 'name', 'deleted_at'], 'tranzakt_columns_unique_table_id_name');
		});

		// Indexes
		Schema::connection($this->connection)
		->create('tranzakt_table_indexes', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Details of all indexes on all tables.'
			);

			$table->foreignId('table_id');
			$table->enum('type', ['primary', 'unique', 'non-unique']);
			$table->string('name', 64)->nullable();
			$table->json('index_columns');
			$table->enum('sort_order', ['ASC', 'DESC'])->default('ASC');
			$table->text('notes')->nullable();

			$table->foreign('table_id')->references('id')->on('tranzakt_tables')
				->onDelete('cascade');
		});

		// Relationships
		Schema::connection($this->connection)
		->create('tranzakt_table_relationships', function(Blueprint $table) {
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
			$table->text('notes')->nullable();
			$table->json('canvas')->nullable()
						->comment('Holds diagramatic data');

			$table->foreign('primary_table_id')->references('id')->on('tranzakt_tables')
				->onDelete('cascade');
			$table->foreign('foreign_table_id')->references('id')->on('tranzakt_tables')
				->onDelete('cascade');
		});

		// Seeds
		Schema::connection($this->connection)
		->create('tranzakt_table_seeds', function(Blueprint $table) {
			$this->common_columns(
				$table,
				'Tranzakt supports the Laravel concept of seeds i.e. rows created when the tables are created'
			);

			$table->foreignId('table_id');
			$table->json('row_values');

			$table->foreign('table_id')->references('id')->on('tranzakt_tables')
				->onDelete('cascade');
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
		Schema::dropIfExists('tranzakt_table_relationships');
		Schema::dropIfExists('tranzakt_table_seeds');
		Schema::dropIfExists('tranzakt_table_indexes');
		Schema::dropIfExists('tranzakt_table_columns');
		Schema::dropIfExists('tranzakt_tables');
		Schema::dropIfExists('tranzakt_table_areas');
	}

	/**
	 * Drop system wide tables.
	 */
	private function drop_system_metadata()
	{
		Schema::dropIfExists('tranzakt_taggables');
		Schema::dropIfExists('tranzakt_tags');
		Schema::dropIfExists('tranzakt_applications');
		Schema::dropIfExists('tranzakt_databases');
	}
};