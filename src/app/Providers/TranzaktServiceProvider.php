<?php

namespace App\Providers;

use App\Models\Tranzakt\Database;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;


class TranzaktServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// We do not want to store the actual models in morph tables
		// like Tags in case the object names change
		// (which is likely as the prototype is made more production ready).
		// This MorphMap allows us to change object names and update the map
		// without the contents of the database tables changing.
		Relation::enforceMorphMap([
			'tranzakt_application'  => 'App\Models\Trazakt\Application',
			'tranzakt_database'     => 'App\Models\Trazakt\Database',
			'tranzakt_area'         => 'App\Models\Trazakt\TableArea',
			'tranzakt_table'        => 'App\Models\Trazakt\Table',
			'tranzakt_column'       => 'App\Models\Trazakt\TableColumn',
			'tranzakt_index'        => 'App\Models\Trazakt\TableIndex',
			'tranzakt_relationship' => 'App\Models\Trazakt\TableRelationship',
			'tranzakt_seed'         => 'App\Models\Trazakt\TableSeed',
		]);

		// Prevent lazy loading during Tranzakt development
		Model::preventLazyLoading(! $this->app->isProduction());

		// Lookup the Tranzakt connection name and add this
		// to the Database table as a default database
		// if the database table doesn't already hold a record for it.
		$tranzakt_connection = config('database.tranzakt');

		try
		{
			$database = Database::on($tranzakt_connection)
			->firstOrNew([
				'name' => $tranzakt_connection,
			]);

			$database->connection = config('database.connections.' . $tranzakt_connection);
			$database->notes = __(
				"This is the connection where Tranzakt's own tables are stored. " .
				"You can use this same connection / database to store your own tables, " .
				"or use a separate connection / database if you want to keep them separate."
			);
			if $database->isDirty('connection')
			{
				$database->save();
			}
		} catch (QueryException $e) {
			// ignore exception if e.g. database tables does not exist during Migration
		}

	}
}
