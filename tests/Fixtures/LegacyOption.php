<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

/**
 * A model-backed 2.x option: its id is hidden and its label is an accessor
 * that isn't appended, so neither survives `toArray()`.
 */
class LegacyOption extends Model
{
    protected $guarded = [];

    protected $hidden = ['id'];

    public static function make(int $id, string $name): self
    {
        return (new self)->forceFill(['id' => $id, 'name' => $name]);
    }

    public function getDisplayNameAttribute(): string
    {
        return 'Option '.$this->name;
    }
}
