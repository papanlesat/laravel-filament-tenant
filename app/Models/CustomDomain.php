<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomDomain extends Model
{
    use HasFactory;

    protected $table = 'custom_domains';

    protected $fillable = [
        'user_id',
        'custom_domain',
        'unique_key',
        'custom_domain_status',
        'old_domain'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'old_domain', 'id');
    }
}
