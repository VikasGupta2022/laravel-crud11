<?php

// namespace App\Imports;

// use App\Models\Product;
// use Maatwebsite\Excel\Concerns\ToModel;

// class ProductsImport implements ToModel
// {
//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */
//     public function model(array $row)
//     {
//         return new Product([
//             //
//         ]);
//     }
// }

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ProductsImport implements ToModel, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    /**
     * Map each row of the spreadsheet to a Product model
     */
    public function model(array $row)
    {
        return new Product([
            'id'          => $row[0],  // Assuming 'id' is the first column (auto-increment, optional)
            'name'        => $row[1],  // 'name' is in the second column
            'sku'         => $row[2],  // 'sku' is in the third column
            'price'       => $row[3],  // 'price' is in the fourth column
            'description' => $row[4],  // 'description' is in the fifth column (optional)
        ]);
    }

    /**
     * Define validation rules for each row
     */
    public function rules(): array
    {
        return [
            '1' => 'required|min:5',      // 'name' validation: required, min 5 characters
            '2' => 'required|min:3',      // 'sku' validation: required, min 3 characters
            '3' => 'required|numeric',    // 'price' validation: required, must be numeric
            '4' => 'nullable|string',     // 'description' validation: optional, can be any string
        ];
    }

    /**
     * Custom messages for validation failures (optional)
     */
    public function customValidationMessages()
    {
        return [
            '1.required' => 'Product name is required and must be at least 5 characters long',
            '2.required' => 'SKU is required and must be at least 3 characters long',
            '3.required' => 'Price is required and must be numeric',
        ];
    }
}

