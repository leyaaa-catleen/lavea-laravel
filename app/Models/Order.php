<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'completed', 'cancelled'];
    protected $fillable = ['user_id', 'total', 'status', 'phone', 'address', 'payment', 'notes'];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }

    public function cancel(): void
    {
        foreach ($this->items as $i) {
            if ($i->perfume_id) Perfume::where('id', $i->perfume_id)->increment('stock', $i->qty);
        }
        $this->update(['status' => 'cancelled']);
    }
}
