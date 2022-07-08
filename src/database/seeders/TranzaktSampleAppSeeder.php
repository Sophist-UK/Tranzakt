<?php

namespace Database\Seeders;

use App\Models\Tranzakt\Application;
use App\Models\Tranzakt\Database;
use App\Models\Tranzakt\Table;
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
		$db_id = $this->database_seeder();

		$table_prefix = __('sample');
		$app_id = $this->applications_seeder(
			__('Sample application'),
			$table_prefix,
			__('This is a sample Tranzakt application for a simple orders database'),
		);

		$cust_tag_id   = $this->tag_seeder(
			$app_id,
			__('Customers'),
			__('Definitions related to customers'),
		);
		$orders_tag_id = $this->tag_seeder(
			$app_id,
			__('Orders'),
			__('Definitions related to orders'),
		);

		$cust_area_id   = $this->table_area_seeder(
			$app_id,
			__('Customers'),
			__('This ER diagram area will contain all tables relating to customers.'),
		);
		$orders_area_id = $this->table_area_seeder(
			$app_id,
			__('Orders'),
			__('This ER diagram area will contain all tables relating to orders.'),
		);

		$cust_table_id = $this->table_seeder(
			$app_id,
			__('Orders'),
			__('Definitions related to orders'),
		);
		$this->columns_seeder();
		$this->index_seeder();
		$this->relationship_seeder();
		$this->seeder_seeder();
	}

	/**
	 * Seed Databases
	 */
	private function database_seeder()
	{
		// Databases table is seeded by the ServiceProvider boot
		return Database::where('name', 'tranzakt')->first()->id();
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
			])->id();
	}

	/**
	 * Seed Tags
	 */
	private function tag_seeder($app_id, $tag, $notes)
	{
		return
			Tag::firstOrCreate([
				'application_id'  => $app_id,
				'name'            => $name,
				'notes'           => $notes,
			])->id();
	}

	/**
	 * Seed ER diagram areas
	 */
	private function table_area_seeder($app_id, $name, $notes)
	{
		return
			TableArea::firstOrCreate([
				'application_id'  => $app_id,
				'name'             => $name,
				'notes'           => $notes,
			])->id();
	}

	/**
	 * Seed Tables
	 */
	private function table_seeder(
		$app_id, $db_id, $prefix,
		$name, $comment, $area_id,
	)
	{
		return
			Table::firstOrCreate([
				'application_id'  => $app_id,
				'database_id'     => $db_id,
				'name'            => $name,
				'table_name'      => $prefix . "__" . $name,
				'comment'         => $comment,
				'area_id'         => $area_id,
			])->id();
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
