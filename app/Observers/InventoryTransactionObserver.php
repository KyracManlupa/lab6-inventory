<?php

namespace App\Observers;

use App\Models\InventoryTransaction;
use App\Notifications\LowStockNotification;

class InventoryTransactionObserver
{
    public function created(InventoryTransaction $transaction): void
    {
        // Reload the product after the database trigger updates its stock.
        $product = $transaction->product()->first();

        if (!$product || $product->quantity > $product->reorder_level) {
            return;
        }

        $user = $transaction->user;

        if (!$user) {
            return;
        }

        // Avoid sending repeated unread low-stock notifications.
        $alreadyNotified = $user->notifications()
            ->where('type', LowStockNotification::class)
            ->whereJsonContains('data->product_id', $product->id)
            ->whereNull('read_at')
            ->exists();

        if (!$alreadyNotified) {
            $user->notify(new LowStockNotification($product));
        }
    }
}
