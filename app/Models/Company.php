<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    // Don't forget to pray before you start coding. Enjoy and happy coding.

    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'logo', 'website'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function routeNotificationFor($driver, $notification = null)
    {
        return $this->email;
    }
}
