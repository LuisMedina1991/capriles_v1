<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtsWithSupplier extends Model
{
    use HasFactory;

    protected $fillable = ['description','amount','status_id','income_id','supplier_id'];


    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function status()
    {
        return $this->belongsTo(status::class);
    }
}
