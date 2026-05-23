<?php

namespace App\Http\Requests\Api;

use App\Models\Zone;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string|max:500',
            'city'        => 'required|string|max:100',
            'country'     => 'required|string|max:100',
            'state'       => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude'    => ['nullable', 'numeric', function (string $attribute, mixed $value, \Closure $fail) {
                $lat = $this->input('latitude');
                $lng = $this->input('longitude');

                // If client provides one coordinate, require the other.
                if (($lat !== null && $lng === null) || ($lat === null && $lng !== null)) {
                    $fail(__('Please provide both latitude and longitude.'));
                    return;
                }

                // If no coordinates, skip zone coverage check (keeps backwards compatibility).
                if ($lat === null && $lng === null) {
                    return;
                }

                $lat = (float) $lat;
                $lng = (float) $lng;

                $covered = Zone::query()
                    ->active()
                    ->get()
                    ->contains(fn (Zone $zone) => $zone->containsPoint(['lat' => $lat, 'lng' => $lng]));

                if (! $covered) {
                    $fail(__('Out of zone. Please choose an address covered by our delivery zones.'));
                }
            }],
            'longitude'   => 'nullable|numeric',
        ];
    }
}
