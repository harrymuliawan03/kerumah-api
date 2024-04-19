<?php

namespace App\Models;

use App\Kontrakan;
use App\ListIdleProperty;
use App\ListPayment;
use App\Perumahan;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements Authenticatable
{
    use SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    public function perumahans(): HasMany
    {
        return $this->hasMany(Perumahan::class, 'user_id', 'id');
    }

    public function kontrakans(): HasMany
    {
        return $this->hasMany(Kontrakan::class, 'user_id', 'id');
    }

    public function listPayments(): HasMany
    {
        return $this->hasMany(ListPayment::class, 'user_id', 'id');
    }

    public function listIdleProperties(): HasMany
    {
        return $this->hasMany(ListIdleProperty::class, 'user_id', 'id');
    }

    public function getAuthIdentifierName()
    {
        return 'name';
    }

    public function getAuthIdentifier()
    {
        return $this->name;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->token;
    }

    public function setRememberToken($value)
    {
        $this->token = $value;
    }

    public function getRememberTokenName()
    {
        return 'token';
    }
}
