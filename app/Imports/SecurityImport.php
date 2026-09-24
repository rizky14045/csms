<?php

namespace App\Imports;

use App\Models\Security;
use App\Rules\UniqueKtaNumber;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SecurityImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $userId;
    protected $unitId;
    protected $isGroup = false;
    protected $unitCodes = [];
    protected $seenNumbers = [];
    protected $skipped = [];
    protected $imported = 0;

    public function __construct($userId)
    {
        $this->userId = $userId;

        $user = \App\Models\User::find($userId);
        $this->unitId = $user->unit_id ?? null;
        $this->isGroup = $user ? \App\Services\Unit\UnitScope::isGroup($user) : false;

        if ($this->isGroup) {
            \App\Services\Unit\UnitScope::assignableUnits($user)->each(function ($unit) {
                if ($unit->unit_code) {
                    $this->unitCodes[strtolower(trim($unit->unit_code))] = ['id' => (int) $unit->id, 'code' => $unit->unit_code];
                }
            });
        }
    }

    public function skippedCount(): int
    {
        return count($this->skipped);
    }

    public function skippedNumbers(): array
    {
        return $this->skipped;
    }

    public function importedCount(): int
    {
        return $this->imported;
    }

    public function model(array $row)
    {
        // No REG KTA harus unik: yang sudah ada di master atau sudah muncul di file ini dilewati.
        $number = UniqueKtaNumber::normalize($row['nomor_registrasi_kta'] ?? '');
        if (isset($this->seenNumbers[$number]) || UniqueKtaNumber::exists($number)) {
            $this->skipped[] = trim((string) $row['nomor_registrasi_kta']);

            return null;
        }
        $this->seenNumbers[$number] = true;
        $this->imported++;

        $unitId = $this->unitId;
        if ($this->isGroup) {
            $unitId = $this->unitCodes[strtolower(trim($row['kode_unit'] ?? ''))]['id'] ?? $this->unitId;
        }

        return new Security([
            'user_id'             => $this->userId,
            'unit_id'             => $unitId,
            'name'                => $row['nama'] ?? null,
            'gender'              => $row['jenis_kelamin'] ?? null,
            'unit_work'           => $row['unit_kerja'] ?? null,
            'nid'                 => $row['nid'] ?? null,
            'registration_number' => $row['nomor_registrasi_kta'] ?? null,
            'expired_card_date'   => $row['tanggal_kadaluarsa_kta'] ?? null,
            'position'            => $row['jabatan'] ?? null,
            'birth_place'         => $row['tempat_lahir'] ?? null,
            'birth_date'          => $row['tanggal_lahir'] ?? null,
            'qualification'       => $row['kualifikasi'] ?? null,
            'last_education'      => $row['pendidikan_terakhir'] ?? null,
            'note'                => $row['catatan'] ?? null,
            'kta_file'            => null,
            'created_by'          => $this->userId,
        ]);
    }

    public function prepareForValidation($data, $index)
    {
        $data['tanggal_kadaluarsa_kta'] = $this->normalizeDate($data['tanggal_kadaluarsa_kta'] ?? null);
        $data['tanggal_lahir'] = $this->normalizeDate($data['tanggal_lahir'] ?? null);

        if ($this->isGroup) {
            $key = strtolower(trim((string) ($data['kode_unit'] ?? '')));
            $data['kode_unit'] = $this->unitCodes[$key]['code'] ?? trim((string) ($data['kode_unit'] ?? ''));
        }

        return $data;
    }

    protected function normalizeDate($value)
    {
        if (empty($value) && $value !== 0) {
            return $value;
        }

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return $value;
            }
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        foreach (['Y-m-d', 'd-m-Y', 'd/m/Y', 'm/d/Y'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, trim($value));
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return $value;
    }

    public function rules(): array
    {
        $rules = [
            'nama'                    => 'required|string',
            'jenis_kelamin'           => 'required|in:Pria,Wanita',
            'unit_kerja'              => 'required|string',
            'nid'                     => 'required',
            'nomor_registrasi_kta'    => 'required',
            'tanggal_kadaluarsa_kta'  => 'required|date',
            'jabatan'                 => 'required|in:Komandan,Anggota,Chief',
            'tempat_lahir'            => 'required|string',
            'tanggal_lahir'           => 'required|date|before:today',
            'kualifikasi'             => 'required|in:Pratama,Madya,Utama',
            'pendidikan_terakhir'     => 'required|string',
            'catatan'                 => 'nullable|string',
        ];

        if ($this->isGroup) {
            $rules['kode_unit'] = ['required', \Illuminate\Validation\Rule::in(array_column($this->unitCodes, 'code'))];
        }

        return $rules;
    }

    public function customValidationMessages()
    {
        return [
            'kode_unit.required'              => 'Kode unit harus diisi!',
            'kode_unit.in'                    => 'Kode unit tidak sesuai dengan unit induk / UL yang terdaftar (' . implode(', ', array_column($this->unitCodes, 'code')) . ')!',
            'nama.required'                   => 'Nama harus diisi!',
            'jenis_kelamin.required'          => 'Jenis kelamin harus diisi!',
            'jenis_kelamin.in'                => 'Jenis kelamin harus berupa Pria atau Wanita!',
            'unit_kerja.required'             => 'Unit kerja harus diisi!',
            'nid.required'                    => 'NID harus diisi!',
            'nomor_registrasi_kta.required'   => 'Nomor registrasi KTA harus diisi!',
            'tanggal_kadaluarsa_kta.required' => 'Tanggal kadaluarsa KTA harus diisi!',
            'tanggal_kadaluarsa_kta.date'     => 'Tanggal kadaluarsa KTA harus berupa format tanggal yang valid (YYYY-MM-DD)!',
            'jabatan.required'                => 'Jabatan harus diisi!',
            'jabatan.in'                      => 'Jabatan harus berupa Komandan, Anggota, atau Chief!',
            'tempat_lahir.required'           => 'Tempat lahir harus diisi!',
            'tanggal_lahir.required'          => 'Tanggal lahir harus diisi!',
            'tanggal_lahir.date'              => 'Tanggal lahir harus berupa format tanggal yang valid (YYYY-MM-DD)!',
            'tanggal_lahir.before'            => 'Tanggal lahir tidak boleh melebihi hari ini!',
            'kualifikasi.required'            => 'Kualifikasi harus diisi!',
            'kualifikasi.in'                  => 'Kualifikasi harus berupa Pratama, Madya, atau Utama!',
            'pendidikan_terakhir.required'    => 'Pendidikan terakhir harus diisi!',
        ];
    }
}
