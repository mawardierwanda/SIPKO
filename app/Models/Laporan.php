<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property int $jadwal_id
 * @property int $petugas_id
 * @property string $isi_laporan
 * @property string $kondisi
 * @property int $jumlah_pelanggaran
 * @property array<array-key, mixed>|null $foto
 * @property \Illuminate\Support\Carbon $waktu_laporan
 * @property string $status
 * @property string|null $catatan_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Jadwal $jadwal
 * @property-read \App\Models\Petugas $petugas
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereCatatanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereIsiLaporan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereJumlahPelanggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereKondisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan wherePetugasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Laporan whereWaktuLaporan($value)
 * @mixin \Eloquent
 */
class Laporan extends Model {
    protected $table    = 'laporan';
    protected $fillable = ['jadwal_id','petugas_id','isi_laporan','kondisi','jumlah_pelanggaran','foto','waktu_laporan','status','catatan_admin'];
    protected $casts    = ['foto'=>'array','waktu_laporan'=>'datetime'];
    public function jadwal()  { return $this->belongsTo(Jadwal::class); }
    public function petugas() { return $this->belongsTo(Petugas::class); }
}
