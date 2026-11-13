<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateLaptopRequest extends FormRequest
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
            'serial_number' => 'required|string|unique:laptops,serial_number,' . $this->route('laptop')->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_sewa_per_hari' => 'required|numeric|min:0',
            'stok_baru' => 'nullable|integer|min:0',
        ];
    }

    protected function passedValidation(): void
    {
        $this->validateSerialNumber($this->merk, $this->serial_number);
    }

    private function validateSerialNumber(string $merk, string $serialNumber): void
    {
        $rules = [
            'Asus'     => [15],
            'Acer'     => [22],
            'HP'       => [10],
            'Apple'    => [10, 11, 12],
            'Dell'     => [7],
            'Lenovo'   => [8],
            'Gigabyte' => [10],
            'MSI'      => range(15, 20),
        ];

        if (array_key_exists($merk, $rules)) {
            $expectedLengths = $rules[$merk];
            if (!in_array(strlen($serialNumber), $expectedLengths)) {
                $lengthInfo = count($expectedLengths) === 1
                    ? "persis {$expectedLengths[0]} karakter"
                    : implode(' atau ', $expectedLengths) . " karakter";
                throw ValidationException::withMessages([
                    'serial_number' => "Serial Number untuk brand {$merk} harus {$lengthInfo}."
                ]);
            }
        }
    }
}
