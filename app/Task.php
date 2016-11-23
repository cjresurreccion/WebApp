<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tasks';
	
	 /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
	
	/**
     * The storage format of the model's date columns.
     *
     * @var string
     */
   // protected $dateFormat = 'U';
	
	 /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql';
	
	
	
	
}