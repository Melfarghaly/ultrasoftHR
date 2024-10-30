<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class CashTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_type', 'document_number', 'document_date', 'currency', 
        'bank_name', 'account_name', 'amount', 'notes'
    ];

    public static function getNextDocumentNumber($type)
    {
        $latestTransaction = self::where('transaction_type', $type)
            ->orderBy('document_number', 'desc')
            ->first();

        $latestNumber = $latestTransaction ? intval(explode('-', $latestTransaction->document_number)[2]) : 0;
        $nextNumber = str_pad($latestNumber + 1, 4, '0', STR_PAD_LEFT);

        return $type . '-' . date('Ymd') . '-' . $nextNumber;
    }
}