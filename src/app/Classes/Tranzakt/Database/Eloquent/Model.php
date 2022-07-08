<?php

namespace App\Classes\Tranzakt\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as LaravelModel;

abstract class Model extends LaravelModel
{
	/**
	 * The connection name for the model.
	 *
	 * @var string|null
	 */
	protected $connection;

	/**
	 * Default is that all fields are mass fillable.
	 *
	 * To guard a specific security sensitive field in a model,
	 * add the field in that specific model.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * Create a new Eloquent model instance with tranzakt default connection
	 *
	 * @param  array  $attributes
	 * @return void
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		$this->connection = config('database.tranzakt');
	}

}
