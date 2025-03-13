<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukukasPurchaseInvoice extends Model
{
    use HasFactory, Notifiable;

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'db_bukukas';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchase_invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];
    
    /**
     * Disable automatic timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    // /**
    //  * ! Get the department that owns the user.
    //  */
    // public function gajiBulananSync(): BelongsTo
    // {
    //     return $this->belongsTo(TasklistDepartment::class, 'departement_id');
    // }

}