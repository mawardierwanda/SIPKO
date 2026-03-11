<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property int $jadwal_id
 * @property int $petugas_id
 * @property string $peran
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Jadwal $jadwal
 * @property-read \App\Models\Petugas $petugas
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan wherePeran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan wherePetugasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Penugasan extends Model {
    protected $table    = 'penugasan';
    protected $fillable = ['jadwal_id','petugas_id','peran','catatan'];
    public function jadwal()  { return $this->belongsTo(Jadwal::class); }
    public function petugas() { return $this->belongsTo(Petugas::class); }
}
