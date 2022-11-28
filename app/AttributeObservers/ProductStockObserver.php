<?php

namespace App\AttributeObservers;

use App\Models\Product;

class ProductStockObserver
{
    /**
     * Handle changes to the "id" field of Product on "created" events.
     *
     * @param \App\Models\Product $product
     * @param mixed $newValue The current value of the field
     * @param mixed $oldValue The previous value of the field
     * @return void
     */
    public function onStockUpdated(Product $product, mixed $newValue, mixed $oldValue)
    {
        $stock = $product->stock;
        $stock_movements = $product->stock_movements->sortByDesc('id');
        foreach($stock_movements as $movement) {
            $stock_akhir = $stock;
            $stock_awal = $stock - $movement->quantity;
            $movement->update(['stock_awal' => $stock_awal, 'stock_akhir' => $stock_akhir]);
            $stock = $stock_awal;
        }
    }
}
