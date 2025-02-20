<?php

namespace App\Models;

use App\Models\ResponsiblePerson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyResponsiblePerson extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'monthly_responsible_persons';

    public function person()
    {
        return $this->hasOne(ResponsiblePerson::class, 'id', 'responsible_person_id')->withTrashed();
    }
}

