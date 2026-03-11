<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property string $nama
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jadwal> $jadwal
 * @property-read int|null $jadwal_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Shift extends Model {
    protected $fillable = ['nama','jam_mulai','jam_selesai','keterangan'];
    public function jadwal() { return $this->hasMany(Jadwal::class); }
}
