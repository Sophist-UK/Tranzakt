<?php

namespace Illuminate\Database\Eloquent;

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
