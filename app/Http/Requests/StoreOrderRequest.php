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
        return false;
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

            'ketersediaan_hewan_id' =>
            'required_if:tipe_pendaftaran,transfer|nullable|exists:ketersediaan_hewan,id',

            'jenis_hewan' =>
            'required_if:tipe_pendaftaran,kirim langsung|string|max:100',

            'total_hewan' => 'required|integer|min:1',

            'berat_hewan' =>
            'required|numeric|min:1',

            'bukti_pembayaran' =>
            'required_if:tipe_pendaftaran,transfer|nullable|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'ketersediaan_hewan_id.required_if' =>
            'Pilih hewan untuk metode transfer',

            'jenis_hewan.required_if' =>
            'Jenis hewan wajib diisi untuk kirim langsung',

            'bukti_pembayaran.required_if' =>
            'Bukti pembayaran wajib diupload',
        ];
    }
}
