<?php

namespace App\Http\Requests\Listings;

class ListingUpdateRequest extends ListingStoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules();
    }

    public function persist($area, $listing)
    {
        if($this->has('payment')) {
            return redirect(route('listings.payment.show', [$area, $listing]));
        }

        return back();
    }
}
