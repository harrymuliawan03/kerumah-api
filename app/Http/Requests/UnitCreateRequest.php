<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UnitCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'name' => 'required|string|max:100',
            // 'name' => 'required|string|max:100|unique:units,name',
            'kode_unit' => 'required|string|max:100',
            'id_parent' => 'required|integer',
            'status' => 'required|in:empty,filled,late',
            'type' => 'required|in:perumahan,kontrakan,kostan',
            'periode_pembayaran' => 'required|in:year,month',
            'nama_penghuni' => 'nullable|string|max:100',
            'no_identitas' => 'nullable|integer',
            'alamat' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kota' => 'nullable|string',
            'kode_pos' => 'nullable|integer',
            'tanggal_mulai' => 'nullable|date',
        ];
    }
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
