<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 */
class Setting extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'setting';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'value', 'created_at', 'updated_at'];

}
