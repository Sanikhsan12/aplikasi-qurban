<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKetersediaanHewanRequest extends FormRequest
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
            'jenis_hewan' => 'required|string|max:50',
            'bobot' => 'required|numeric|min:0|max:1000',
            'harga' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_foto' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_hewan.required' => 'Jenis hewan harus diisi.',
            'bobot.required' => 'Bobot hewan harus diisi.',
            'bobot.min' => 'Bobot tidak boleh kurang dari 0 kg.',
            'bobot.max' => 'Bobot maksimal 1000 kg.',
            'harga.required' => 'Harga hewan harus diisi.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
            'jumlah.required' => 'Jumlah hewan harus diisi.',
            'jumlah.min' => 'Jumlah minimal 1 ekor.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, gif.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
