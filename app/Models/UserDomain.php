<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class UserDomain extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'domains';
    protected $fillable = ['tenant_id', 'domain'];
}
