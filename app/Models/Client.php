<?php
// app/Models/Client.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_type',
        'nin_bcrn',
        'dob_dor',
        'mobile_no',
        'wa',
        'district',
        'occupation',
        'source',
        'status',
        'signed_up',
        'employer',
        'clid',
        'contact_person',
        'income_source',
        'married',
        'spouses_name',
        'alternate_no',
        'email_address',
        'location',
        'island',
        'country',
        'po_box_no',
        'pep',
        'pep_comment',
        'image',
        'salutation',
        'first_name',
        'other_names',
        'surname',
        'passport_no',
        'id_document_path',
        'poa_document_path',
        'business_docs_path'
    ];

    protected $casts = [
        'dob_dor' => 'date',
        'signed_up' => 'date',
        'married' => 'boolean',
        'pep' => 'boolean'
    ];

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }
}