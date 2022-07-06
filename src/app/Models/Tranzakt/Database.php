<?php

namespace App\Models\Tranzakt;

use App\Classes\Tranzakt\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sqits\UserStamps\Concerns\HasUserStamps;

class Database extends Model
{
	use HasFactory;
	use HasUserStamps;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'tranzakt_databases';

	/**
	 * Get the tables for the application.
	 */
	public function tables()
	{
		return $this->hasMany(Table::class);
	}

	/**
	 * Get tags.
	 */
	public function tags()
	{
			return $this->morphToMany(Tag::class, 'tranzakt_taggable');
	}
}
