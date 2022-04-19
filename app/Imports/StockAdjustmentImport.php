<?php

namespace App\Imports;

use App\Models\StockAdjustment;
use App\Models\StockMovement;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use DB;

class StockAdjustmentImport implements ToCollection, WithHeadingRow
{
    private $products;

    public function __construct()
    {
        $this->products = Product::select('id', 'slug')->get();
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row)
            {
                $product = $this->products->where('slug', $row['product'])->first();

                $stockAdjustment = StockAdjustment::create([
                    'date' => $row['tanggal'],
                    'operation' => $row['operation'],
                    'product_id' => $product->id,
                    'quantity' => $row['quantity'],
                    'note' => $row['note']
                ]);

                StockMovement::create([
                    'reference' => $stockAdjustment->id,
                    'type' => 'adjustment',
                    'product_id' => $product->id,
                    'quantity' => $row['quantity']
                ]);

                $updateProduct = Product::find($product->id);

                if ($stockAdjustment->is_increase) {
                    $newStock = $updateProduct->stock + (int) $row['quantity'];
                } else {
                    $newStock = $updateProduct->stock - (int) $row['quantity'];
                }

                $updateProduct->update(['stock' => $newStock]);
            }
            DB::commit();
        }  catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
