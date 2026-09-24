<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyMainSecurityProgram extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function mainProgram()
    {
        return $this->hasOne(MainSecurityProgram::class, 'id', 'main_program_id');
    }

    /** Sel realisasi. schedule null = data lama (rentang start-end); '[]' = belum ada realisasi. */
    public function actualCells(): array
    {
        if ($this->schedule !== null) {
            $decoded = json_decode($this->schedule, true);

            return is_array($decoded) ? MainSecurityProgram::normalizeCells($decoded) : [];
        }

        return MainSecurityProgram::cellsFromRange($this->start_month, $this->start_week, $this->end_month, $this->end_week);
    }

    /** Sel rencana dari master; jika master sudah dihapus, pakai salinan rentang di baris ini. */
    public function planCells(): array
    {
        return $this->mainProgram ? MainSecurityProgram::cellsFor($this->mainProgram) : MainSecurityProgram::cellsFromRange($this->start_month, $this->start_week, $this->end_month, $this->end_week);
    }
}
