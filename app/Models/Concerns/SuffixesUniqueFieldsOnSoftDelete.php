<?php

namespace App\Models\Concerns;

trait SuffixesUniqueFieldsOnSoftDelete
{
    public static function bootSuffixesUniqueFieldsOnSoftDelete(): void
    {
        static::deleting(function ($model) {
            if ($model->isForceDeleting()) {
                return;
            }

            foreach (static::uniqueFieldsForSoftDelete() as $field) {
                $value = $model->getAttribute($field);

                if ($value === null || $value === '') {
                    continue;
                }

                $suffix = '-deleted-' . $model->getKey();

                if (! str_ends_with($value, $suffix)) {
                    $model->setAttribute($field, $value . $suffix);
                }
            }

            $model->saveQuietly();
        });
    }

    /**
     * @return array<int, string>
     */
    protected static function uniqueFieldsForSoftDelete(): array
    {
        return ['slug', 'sku'];
    }
}
