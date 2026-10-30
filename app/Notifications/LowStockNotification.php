<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Product $product
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_quantity' => $this->product->quantity,
            'reorder_level' => $this->product->reorder_level,
            'message' => $this->product->name . ' is running low on stock.',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Stock Alert')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A product has reached its reorder level.')
            ->line('Product: ' . $this->product->name)
            ->line('Current Stock: ' . $this->product->quantity)
            ->line('Reorder Level: ' . $this->product->reorder_level)
            ->line('Please restock this product soon.');
    }
}
