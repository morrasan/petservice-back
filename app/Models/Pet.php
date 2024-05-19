<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Pet
 * @package App\Models
 *
 * @property int id
 * @property string name
 * @property array photo_urls
 * @property int category_id
 * @property string status
 *
 * @property Category category
 * @property Collection tags
 */
class Pet extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'photo_urls',
        'category_id',
        'status'
    ];

    protected $casts = [
        'photo_urls' => 'array'
    ];

    public function category (): BelongsTo {

        return $this->belongsTo(Category::class);
    }

    public function  tags (): BelongsToMany {

        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
}
