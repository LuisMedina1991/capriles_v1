<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtype extends Model
{
    use HasFactory;

    protected $fillable = ['name','type_id'];

    public function type(){

        return $this->belongsTo(Type::class);
    }

    public function balance_sheet_accounts(){

        return $this->hasMany(BalanceSheetAccount::class);
    }
}
