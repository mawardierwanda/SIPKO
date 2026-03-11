<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nama
 * @property string|null $alamat
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jadwal> $jadwal
 * @property-read int|null $jadwal_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Lokasi extends Model
{
    protected $table = 'lokasi';

    protected $fillable = [
        'nama',
        'alamat',
        'latitude',
        'longitude',
        'keterangan',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }
}
