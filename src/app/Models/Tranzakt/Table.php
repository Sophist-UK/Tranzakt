<?php

namespace App\Models\Tranzakt;

use App\Classes\Tranzakt\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sqits\UserStamps\Concerns\HasUserStamps;

class Table extends Model
{
	use HasFactory;
	use HasUserStamps;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'tranzakt_tables';

	/**
	 * Get the application that owns the table.
	 */
	public function application()
	{
		return $this->belongsTo(Application::class);
	}

	/**
	 * Get the database that owns the table.
	 */
	public function database()
	{
		return $this->belongsTo(Database::class);
	}

	/**
	 * Get the table area (optional) that owns the table.
	 */
	public function table_area()
	{
		return $this->belongsTo(TableArea::class)->withDefault();
	}

	/**
	 * Get the columns for the table.
	 */
	public function columns()
	{
		return $this->hasMany(TableColumn::class);
	}

	/**
	 * Get the indexes for the table.
	 */
	public function indexes()
	{
		return $this->hasMany(TableIndex::class);
	}

	/**
	 * Get the relationships where this table is owner.
	 */
	public function relationships_as_owner()
	{
		return $this->hasMany(TableRelationship::class, 'primary_table_id');
	}

	/**
	 * Get the foreign key relationships for the table.
	 */
	public function relationships_as_foreign()
	{
		return $this->hasMany(TableRelationship::class, 'foreign_table_id');
	}

	/**
	 * Get the seeds for the application.
	 */
	public function seeds()
	{
		return $this->hasMany(TableSeed::class);
	}

	/**
	 * Get tags.
	 */
	public function tags()
	{
			return $this->morphToMany(Tag::class, 'tranzakt_taggable');
	}

}
