<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GajiBulananSync extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_gaji_bulanans_sync';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    public function puchaseInvoice()
    {
        return $this->belongsTo(BukukasPurchaseInvoice::class, 'bukukas_id', 'id');
    }
}
