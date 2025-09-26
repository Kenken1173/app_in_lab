<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Book extends Model
{
    protected $casts = [
        'updated_at' => 'datetime',
    ];

    /* 借りている本のスコープ */
    public function scopeBorrowed($query)
    {
        return $query->whereNotNull('borrower');
    }

    /* 借りた日を取得 */
    public function getBorrowedDateAttribute()
    {
        return $this->updated_at;
    }

    /* 経過日数を取得 */
    public function getDaysPassedAttribute()
    {
        return $this->borrowed_date ? $this->borrowed_date->diffInDays(now()) : null;
    }
}