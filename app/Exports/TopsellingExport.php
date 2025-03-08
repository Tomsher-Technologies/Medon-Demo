<?php

namespace App\Exports;

use App\Models\Product; // Replace with your Order model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Auth;

class TopsellingExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $category_id;
    
     public function __construct($category_id = null)
    {
        $this->category_id = $category_id;
    }
    
    /**
     * Fetch order details for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $product = Product::withSum('orderDetails', 'quantity')
                                ->orderBy('order_details_sum_quantity', 'DESC');

        if ($this->category_id && $this->category_id !== '0') {
            $childIds = [];
            $categoryfilter = $this->category_id;
            $childIds[] = array($this->category_id);
            
            if($categoryfilter != ''){
                $childIds[] = getChildCategoryIds($categoryfilter);
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            
            $product = $product->whereHas('category', function ($q) use ($childIds) {
                $q->whereIn('id', $childIds);
            });
        }
    
        $topProducts = $product->get()->where('order_details_sum_quantity', '>', 0);
        
        return $topProducts;   
    }

    /**
     * Define headings for the Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            ['Exported on: ' . now()->format('d-m-Y h:i A')],
            ['Sl No.', 'Product SKU', 'Product Name', 'Total Sales'],
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        $totalColumns = 3;
        $sheet->mergeCells('A1:D1');
       
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(120);
        $sheet->getColumnDimension('D')->setWidth(20);

        // $sheet->getStyle('A1:A' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // $sheet->getStyle('C1:C' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [
            // Optional: Add specific styles for the header row
            1 => ['font' => ['bold' => true, 'size' => '14px']], // Row 1 styling
            2 => ['font' => ['bold' => true, 'size' => '14px']], // Row 1 styling
            'A' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],
            'D' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]
        ];
    }
    
    public function map($products): array
    {
        // Combine product details into a single field
        static $count = 0;
        $count++;

        return [
            $count,
            $products->sku,
            $products->name,
            (string)$products->order_details_sum_quantity ?? 0
        ];
    }
}
