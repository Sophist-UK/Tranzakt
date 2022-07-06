<?php

namespace App\Models\Tranzakt;

use App\Classes\Tranzakt\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sqits\UserStamps\Concerns\HasUserStamps;

class TableRelationship extends Model
{
	use HasFactory;
	use HasUserStamps;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'tranzakt_table_relationships';

	/**
	 * Get the primary table for the relationship.
	 */
	public function primary_table()
	{
		return $this->belongsTo(Table::class, 'primary_table_id');
	}

	/**
	 * Get the foreign table for the relationship.
	 */
	public function foreign_table()
	{
		return $this->belongsTo(Table::class, 'foreign_table_id');
	}

	/**
	 * Get tags.
	 */
	public function tags()
	{
			return $this->morphToMany(Tag::class, 'tranzakt_taggable');
	}

}
