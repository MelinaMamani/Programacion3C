<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends \Illuminate\Database\Eloquent\Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    // protected $primaryKey = 'flight_id';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
     public $timestamps = false;

    // const CREATED_AT = 'creation_date';
    // const UPDATED_AT = 'last_update';
    
    //use SoftDeletes;
}

?>