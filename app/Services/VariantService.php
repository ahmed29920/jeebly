<?php

namespace App\Services;

use App\Models\Variant;
use App\Repositories\VariantRepository;

class VariantService
{
    public function __construct(
        protected VariantRepository $variantRepo
    ) {}

    public function all()
    {
        return $this->variantRepo->all();
    }

    public function findById(int $id)
    {
        return $this->variantRepo->findById($id);
    }
    public function store(array $data)
    {
        $variant = $this->variantRepo->create($data);
        if(isset($data['options']) && !empty($data['options'])){
            $variant->options()->createMany($data['options']);
        }
        return $variant;
    }

    public function update(Variant $variant, array $data)
    {
        $variant = $this->variantRepo->update($variant, $data);

        if (isset($data['options']) && is_array($data['options'])) {
            $options = $data['options'];

            // IDs of options sent in the request
            $incomingIds = collect($options)
                ->pluck('id')
                ->filter()
                ->values();

            // Delete options that are no longer sent
            $variant->options()
                ->whereNotIn('id', $incomingIds)
                ->delete();

            foreach ($options as $option) {
                $optionData = collect($option)->except('id')->toArray();

                if (!empty($option['id'])) {
                    // Update existing option
                    $existing = $variant->options()->where('id', $option['id'])->first();
                    if ($existing) {
                        $existing->update($optionData);
                        continue;
                    }
                }

                // Create new option
                $variant->options()->create($optionData);
            }
        }

        return $variant->fresh('options');
    }

    public function delete(Variant $variant)
    {
        return $this->variantRepo->delete($variant);
    }
}
