<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $nip
 * @property string $nama
 * @property string $jabatan
 * @property string|null $pangkat
 * @property string|null $satuan
 * @property string|null $no_hp
 * @property string|null $alamat
 * @property string|null $foto
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $foto_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laporan> $laporan
 * @property-read int|null $laporan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Penugasan> $penugasan
 * @property-read int|null $penugasan_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas wherePangkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Petugas whereUserId($value)
 * @mixin \Eloquent
 */
class Petugas extends Model {
    protected $table    = 'petugas';
    protected $fillable = ['user_id','nip','nama','jabatan','pangkat','satuan','no_hp','alamat','foto','status'];
    public function user()      { return $this->belongsTo(User::class); }
    public function penugasan() { return $this->hasMany(Penugasan::class); }
    public function laporan()   { return $this->hasMany(Laporan::class); }
    public function getFotoUrlAttribute(): string {
        return $this->foto
            ? asset('storage/'.$this->foto)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->nama).'&background=2d6a4f&color=fff&size=80';
    }
}
