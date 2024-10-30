<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'check_number', 'account_name', 'bank', 'cost_center', 'issue_date', 'due_date', 'check_value', 'currency', 'notes', 'check_type'
    ];

    // Date casting
    protected $casts = [
        'issue_date' => 'datetime',
        'due_date' => 'datetime',
    ];

    // Relationship with CheckTransaction model
    public function transactions()
    {
        return $this->hasMany(CheckTransaction::class);
    }
}
