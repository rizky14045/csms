<?php

namespace App\Models;

use App\Models\AgreementExternal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyAgreementExternal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    
    public function agreement()
    {
        return $this->hasOne(AgreementExternal::class, 'id', 'agreement_external_id')->withTrashed();
    }
}
