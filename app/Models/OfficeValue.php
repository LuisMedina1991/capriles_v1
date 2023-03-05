<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OfficeValue extends Pivot
{
    use HasFactory;
    
    public function value()
    {
        return $this->belongsTo(Value::class,'value_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class,'office_id');
    }

    public function incomes(){

        return $this->hasMany(Income::class,'office_value_id','id');
    }

    public function transfers(){

        return $this->hasMany(Transfer::class);
    }

    public function sales(){

        return $this->hasMany(Sale::class);
    }
}
