<?php

namespace App\Models\Tranzakt;

use App\Classes\Tranzakt\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sqits\UserStamps\Concerns\HasUserStamps;

class Tag extends Model
{
	use HasFactory;
	use HasUserStamps;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'tranzakt_tags';

	/**
	 * Get the application for the tag.
	 */
	public function application()
	{
		return $this->belongsTo(Application::class);
	}

	/**
	 * Get the applications that are assigned this tag.
	 */
	public function applications()
	{
			return $this->morphedByMany(Application::class, 'tranzakt_taggable');
	}

	/**
	 * Get the databases that are assigned this tag.
	 */
	public function databases()
	{
			return $this->morphedByMany(Database::class, 'tranzakt_taggable');
	}

	/**
	 * Get the applications that are assigned this tag.
	 */
	public function table_areas()
	{
			return $this->morphedByMany(TableArea::class, 'tranzakt_taggable');
	}

	/**
	 * Get the tables that are assigned this tag.
	 */
	public function tables()
	{
			return $this->morphedByMany(Table::class, 'tranzakt_taggable');
	}

	/**
	 * Get the applications that are assigned this tag.
	 */
	public function columns()
	{
			return $this->morphedByMany(TableColumn::class, 'tranzakt_taggable');
	}

	/**
	 * Get the applications that are assigned this tag.
	 */
	public function indexes()
	{
			return $this->morphedByMany(TableIndex::class, 'tranzakt_taggable');
	}

	/**
	 * Get the applications that are assigned this tag.
	 */
	public function relationships()
	{
			return $this->morphedByMany(TableRelationship::class, 'tranzakt_taggable');
	}

}
