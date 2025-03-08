<?php

namespace App\Models;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithMapping, WithHeadings, WithStyles
{
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            ['Exported on: ' . now()->format('d-m-Y h:i A')],
            [
                'Product Code',
                'Product Name',
                'Brand Name',
                'Category Name', 
                'Description',   
                'Benefits',
                'How To Use', 
                'Unit',
                'VAT',   
                'Current Stock',
                'Unit Price',
                'Discount',
                'Discount Type',
                'Discount Start date',
                'Discount End date',
                'Keywords',
                'Return Available',
                'Publish Status',
                'thumbnail image',
                'gallery images'
            ],
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:D1');
       
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(80);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(100);
        $sheet->getColumnDimension('F')->setWidth(100);
        $sheet->getColumnDimension('G')->setWidth(100);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(25);
        $sheet->getColumnDimension('O')->setWidth(25);
        $sheet->getColumnDimension('P')->setWidth(40);
        $sheet->getColumnDimension('Q')->setWidth(20);
        $sheet->getColumnDimension('R')->setWidth(20);
        $sheet->getColumnDimension('S')->setWidth(100);
        $sheet->getColumnDimension('T')->setWidth(100);

        $sheet->getStyle('B')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E')->getAlignment()->setWrapText(true);
        $sheet->getStyle('F')->getAlignment()->setWrapText(true);
        $sheet->getStyle('G')->getAlignment()->setWrapText(true);
        $sheet->getStyle('P')->getAlignment()->setWrapText(true);
        $sheet->getStyle('S')->getAlignment()->setWrapText(true);
        $sheet->getStyle('T')->getAlignment()->setWrapText(true);

        return [
            // Optional: Add specific styles for the header row
            1 => ['font' => ['bold' => true, 'size' => '14px']], // Row 1 styling
            2 => ['font' => ['bold' => true, 'size' => '14px']], // Row 1 styling
            'A' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],
            'C' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'D' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'H' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'I' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'J' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'K' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'L' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'M' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'N' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'O' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'Q' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'R' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
        ];
    }

    /**
    * @var Product $product
    */
    public function map($product): array
    {
        $tabs = [
            'Benefits' => '',
            'How to use' => '',
        ];

        foreach ($product->tabs as $tab) {
            if (isset($tabs[$tab->heading])) {
                $tabs[$tab->heading] = $tab->content;
            }
        }

        $photos = '';
        if ($product->photos) {
            $photoPaths = explode(',', $product->photos);
            $fullPhotoLinks = array_map(function ($path) {
                return asset($path);
            }, $photoPaths);
            $photos = implode("\n", $fullPhotoLinks);
        }

        // Process thumbnail image
        $thumbnail = $product->thumbnail_img ? asset($product->thumbnail_img) : '';
        
        return [
            $product->sku,
            $product->name,
            $product->brand->name ?? '',
            $product->category->name ?? '',
            $product->description,
            $tabs['Benefits'],
            $tabs['How to use'],
            $product->unit,
            (string)$product->vat,
            (string)$product->stocks[0]->qty ?? 0,
            (string)$product->unit_price,
            $product->discount,
            ucfirst($product->discount_type),
            ($product->discount_start_date != NULL) ? date('d-m-Y H:i:s', $product->discount_start_date) : '',
            ($product->discount_end_date != NULL) ? date('d-m-Y H:i:s', $product->discount_end_date) : '',
            $product->tags,
            ($product->return_refund == 1) ? 'Yes' : 'No',
            ($product->published == 1) ? 'Yes' : 'No',
            $thumbnail,
            $photos
        ];
    }
}
