<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Offer extends Model
{
    use HasFactory;
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'offered_by',
        'user_id',
        'company_id',
        'offer_title',
        'offer_description',
        'reward_total_cents',
        'reward_split_percent',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'offered_by' => 'string',
        'reward_total_cents' => 'integer',
        'reward_split_percent' => 'decimal:2',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Default attribute values
     *
     * @var array
     */
    protected $attributes = [
        'reward_split_percent' => 0.50,
        'status' => 'active',
    ];

    /**
     * Get the user that created the offer
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company associated with the offer
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get all ratings for this offer
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Accessor for reward in euros
     */
    public function getRewardTotalEurosAttribute(): float
    {
        return $this->reward_total_cents / 100;
    }

    /**
     * Mutator for reward in euros
     */
    public function setRewardTotalEurosAttribute(float $value): void
    {
        $this->attributes['reward_total_cents'] = $value * 100;
    }

    /**
     * Scope for active offers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for offers by referrer
     */
    public function scopeByReferrer($query)
    {
        return $query->where('offered_by', 'referrer');
    }

    /**
     * Get human-readable status
     */
    public function getStatusLabelAttribute(): string
    {
        return [
            'active' => 'Aktiv',
            'inactive' => 'Inaktiv',
            'matched' => 'Zugewiesen',
            'closed' => 'Abgeschlossen'
        ][$this->status] ?? $this->status;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Automatically set the user_id if not provided
            if (empty($model->user_id) && Auth::check()) {
                $model->user_id = Auth::id();
            }
        });
    }
}
