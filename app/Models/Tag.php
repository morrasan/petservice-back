<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Tag
 * @package App\Models
 *
 * @property int id
 * @property string name
 *
 * @property Collection pets
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function pets (): BelongsToMany {

        return $this->belongsToMany(Pet::class)->withTimestamps();
    }
}
