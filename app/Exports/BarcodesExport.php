<?php

namespace App\Exports;

use App\Models\Barcode;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BarcodesExport Implements WithMapping, WithHeadings, FromQuery, ShouldAutoSize
{
    use Exportable;

    /**
     * @return Builder
     */
    public function query()
    {
        return Barcode::with('customer', 'product');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Public Code',
            'Secret Code',
            'Product Name',
            'Is Scanned',
            'Scanning Date',
            'Scanned By',
        ];
    }

    /**
     * @param mixed $barcode
     * @return array
     */
    public function map($barcode): array
    {
        return [
            $barcode->public_code,
            $barcode->secret_code,
            $barcode->product->name ?? '',
            $barcode->scan_before,
            $barcode->scan_date,
            $barcode->customer->telephone ?? '',
        ];
    }
}
