<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property string $nama
 * @property string|null $kode
 * @property string|null $deskripsi
 * @property bool $aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jadwal> $jadwal
 * @property-read int|null $jadwal_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKegiatan whereAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKegiatan whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKegiatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKegiatan whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKegiatan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKegiatan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JenisKegiatan extends Model {
    protected $table    = 'jenis_kegiatan';
    protected $fillable = ['nama','kode','deskripsi','aktif'];
    protected $casts    = ['aktif'=>'boolean'];
    public function jadwal() { return $this->hasMany(Jadwal::class,'jenis_kegiatan_id'); }
}
