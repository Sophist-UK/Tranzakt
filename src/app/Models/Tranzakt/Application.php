<?php

namespace App\Models\Tranzakt;

use App\Classes\Tranzakt\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sqits\UserStamps\Concerns\HasUserStamps;

class Application extends Model
{
	use HasFactory;
	use HasUserStamps;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'tranzakt_applications';

	/**
	 * Get the tables for the application.
	 */
	public function tables()
	{
		return $this->hasMany(Table::class);
	}

	/**
	 * Get the table areas for the application.
	 */
	public function table_areas()
	{
		return $this->hasMany(TableArea::class);
	}

	/**
	 * Get the tags for the application.
	 */
	public function tags()
	{
		return $this->hasMany(Tag::class);
	}

	/**
	 * Get tags.
	 */
	public function tags()
	{
			return $this->morphToMany(Tag::class, 'tranzakt_taggable');
	}
}
