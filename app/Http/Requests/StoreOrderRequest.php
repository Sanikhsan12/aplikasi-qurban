<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipe_pendaftaran' => 'required|in:transfer,kirim langsung',
            'ketersediaan_hewan_id' => 'required_if:tipe_pendaftaran,transfer|exists:ketersediaan_hewan,id',
            'jenis_hewan' => 'required_if:tipe_pendaftaran,kirim langsung|string|max:100',
            'berat_kirim' => 'required_if:tipe_pendaftaran,kirim langsung|numeric|min:1',
            'total_hewan' => 'required|integer|min:1|max:1',
            'peserta_2' => 'nullable|string|max:255',
            'peserta_3' => 'nullable|string|max:255',
            'peserta_4' => 'nullable|string|max:255',
            'peserta_5' => 'nullable|string|max:255',
            'peserta_6' => 'nullable|string|max:255',
            'peserta_7' => 'nullable|string|max:255',
        ];
    }
}
