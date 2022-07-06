<?php

namespace App\Models\Tranzakt;

use App\Classes\Tranzakt\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sqits\UserStamps\Concerns\HasUserStamps;

class TableIndex extends Model
{
	use HasFactory;
	use HasUserStamps;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'tranzakt_table_indexes';

	/**
	 * Get the table for the index.
	 */
	public function table()
	{
		return $this->belongsTo(Table::class);
	}

	/**
	 * Get tags.
	 */
	public function tags()
	{
			return $this->morphToMany(Tag::class, 'tranzakt_taggable');
	}

}
