<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TitikRazia extends Model {
    protected $table    = 'titik_razia';
    protected $fillable = ['jadwal_id','lokasi_id','nama_titik','latitude','longitude','urutan','status'];

    public function jadwal()   { return $this->belongsTo(Jadwal::class); }
    public function lokasi()   { return $this->belongsTo(Lokasi::class); }
    public function checkins() { return $this->hasMany(CheckinRazia::class); }
    public function sudahCheckin(int $petugasId): bool {
        return $this->checkins()->where('petugas_id', $petugasId)->exists();
    }
}
