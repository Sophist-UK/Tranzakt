<?php

namespace App\Models\Tranzakt;

use App\Classes\Tranzakt\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sqits\UserStamps\Concerns\HasUserStamps;

class TableArea extends Model
{
	use HasFactory;
	use HasUserStamps;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'tranzakt_table_areas';

	/**
	 * Get the application that owns the table area.
	 */
	public function application()
	{
		return $this->belongsTo(Application::class);
	}

	/**
	 * Get the tables that belong to the table area.
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
