<?php

namespace Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Image;
use Tests\TestCase;

class ImageModelTest extends TestCase
{
    public function test_image_morph_to_imageable()
    {
        $this->assertMorphTo(new Image, 'imageable');
    }

    protected function assertMorphTo($model, $relationName)
    {
        $relation = $model->$relationName();

        $this->assertInstanceOf(MorphTo::class, $relation, 'Relation is wrong');
    }
}
