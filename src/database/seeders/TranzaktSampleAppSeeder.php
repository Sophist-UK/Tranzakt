<?php
/**
 * Populate the Tranzakt metadata schema with a Sample Tranzakt application.
 *
 * This version of the file holds the sample data for, the application, areas,
 * tables, columns, indexes, relationships.
 *
 * Seeds for a Sample app are defined in database/seeders/TranzaktSampleAppSeeder.php
 *
 * Seed the Sample Application by running:
 */

namespace Database\Seeders;

use App\Models\Tranzakt\Application;
use App\Models\Tranzakt\Database;
use App\Models\Tranzakt\Table;
use App\Models\Tranzakt\TableArea;
use App\Models\Tranzakt\TableColumn;
use App\Models\Tranzakt\TableIndex;
use App\Models\Tranzakt\TableRelationship;
use App\Models\Tranzakt\TableSeed;
use App\Models\Tranzakt\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Create a sample application.
 */
class TranzaktSampleAppSeeder extends Seeder
{
	/**
	 * Seed the various tables
	 *
	 * @return void
	 */
	public function run()
	{
		$db = $this->database_seeder();

		$table_prefix = __('sample');
		$application = $this->applications_seeder(
			__('Sample application'),
			$table_prefix,
			__('This is a sample Tranzakt application for a simple orders database'),
		);

		$cust_tag   = $this->tag_seeder(
			$application,
			__('Customers'),
			__('Definitions related to customers'),
		);
		$orders_tag = $this->tag_seeder(
			$application,
			__('Orders'),
			__('Definitions related to orders'),
		);

		$cust_area   = $this->table_area_seeder(
			$cust_tag,
			$application,
			__('Customers'),
			__('This ER diagram area will contain all tables relating to customers.'),
		);
		$orders_area = $this->table_area_seeder(
			$orders_tag,
			$application,
			__('Orders'),
			__('This ER diagram area will contain all tables relating to orders.'),
		);

		$cust_table = $this->table_seeder(
			$cust_tag, $application, $db, $table_prefix,
			__('customers'),
			__('Table holding customer details'),
			$cust_area,
		);

		$this->columns_seeder();
		$this->index_seeder();
		$this->relationship_seeder();
		$this->seeds_seeder();
	}

	/**
	 * Add tag to model if it is not empty.
	 */
	private function add_tag($model, $tag)
	{
		if (! empty($tag))
		{
			$model->tags()->save($tag);
		}
	}

	/**
	 * Seed Databases
	 */
	private function database_seeder()
	{
		// Databases table is seeded by the ServiceProvider boot
		return Database::where('name', 'tranzakt')->first();
	}

	/**
	 * Seed Applications
	 */
	private function applications_seeder($name, $prefix, $notes)
	{
		return
			Application::firstOrCreate([
				'name'         => $name,
				'table_prefix' => $prefix,
				'notes'        => $notes,
			]);
	}

	/**
	 * Seed Tags
	 */
	private function tag_seeder($application, $name, $notes)
	{
		return
			Tag::firstOrCreate([
				'application_id' => $application->id,
				'name'           => $name,
				'notes'          => $notes,
			]);
	}

	/**
	 * Seed ER diagram areas
	 */
	private function table_area_seeder($tag, $application, $name, $notes)
	{
		$area = TableArea::firstOrCreate([
				'application_id' => $application->id,
				'name'           => $name,
				'notes'          => $notes,
			]);
		$this->add_tag($area, $tag);
		return $area;
	}

	/**
	 * Seed Tables
	 */
	private function table_seeder(
		$tag, $application, $db, $prefix,
		$name, $comment, $area,
	)
	{
		if (! is_null($area))
		{
			$area = $area->id;
		}
		$table = Table::firstOrCreate([
				'application_id'  => $application->id,
				'database_id'     => $db->id,
				'name'            => $name,
				'table_name'      => $prefix . "__" . $name,
				'comment'         => $comment,
				'area_id'         => $area,
			]);
		$this->add_tag($table, $tag);
		return $table;
	}

	/**
	 * Seed Columns
	 */
	private function columns_seeder()
	{
	}

	/**
	 * Seed Indexes
	 */
	private function index_seeder()
	{
	}

	/**
	 * Seed Relationships
	 */
	private function relationship_seeder()
	{
	}

	/**
	 * Seed Seeds
	 */
	private function seeds_seeder()
	{
	}
}
