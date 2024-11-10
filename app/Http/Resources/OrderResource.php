<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
            ],
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'status' => $this->status ?? 'Pending',
            'date_ordered' => $this->created_at->format('Y-m-d'),
        ];
    }
}
