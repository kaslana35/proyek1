<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreLaptopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'spesifikasi' => 'required|string',
            'serial_number' => 'required|string|unique:laptops,serial_number',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'harga_sewa_per_hari' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ];
    }

    protected function passedValidation(): void
    {
        $this->validateSerialNumber($this->merk, $this->serial_number);
    }

    private function validateSerialNumber(string $merk, string $serialNumber): void
    {
        $rules = [
            'Asus'     => 12,
            'Acer'     => 10,
            'HP'       => 10,
            'Apple'    => 12,
            'Dell'     => 7,
            'Lenovo'   => 8,
            'Gigabyte' => 10,
        ];

        if (array_key_exists($merk, $rules)) {
            $expectedLength = $rules[$merk];
            if (strlen($serialNumber) !== $expectedLength) {
                throw ValidationException::withMessages([
                    'serial_number' => "Serial Number untuk brand {$merk} harus persis {$expectedLength} karakter alfanumerik."
                ]);
            }
        }
    }
}
