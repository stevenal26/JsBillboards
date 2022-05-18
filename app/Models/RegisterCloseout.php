<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AttachUnit;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RegisterCloseoutItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Unit;
use App\Models\Register;

class RegisterCloseout extends Model
{
    use HasFactory, AttachUnit;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];
    
    /**
     * Get the items for the catering order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(RegisterCloseoutItem::class, 'register_closeout_id');
    }

    /**
     * Get the unit owns the catering order.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
    
    public function register(): BelongsTo
    {
        return $this->belongsTo(Register::class);
    }
}
