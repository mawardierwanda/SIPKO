<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model {
    use SoftDeletes;

    protected $table    = 'jadwal';
    protected $fillable = ['jenis_kegiatan_id','nama_kegiatan','tanggal','shift_id','lokasi_id','satuan','keterangan','status','dibuat_oleh'];
    protected $casts    = ['tanggal'=>'date'];

    public function jenisKegiatan() { return $this->belongsTo(JenisKegiatan::class,'jenis_kegiatan_id'); }
    public function shift()         { return $this->belongsTo(Shift::class); }
    public function lokasi()        { return $this->belongsTo(Lokasi::class); }
    public function dibuatOleh()    { return $this->belongsTo(User::class,'dibuat_oleh'); }
    public function penugasan()     { return $this->hasMany(Penugasan::class); }
    public function laporan()       { return $this->hasOne(Laporan::class); }
    public function titikRazia()    { return $this->hasMany(TitikRazia::class)->orderBy('urutan'); }

    public function sudahLaporan(): bool { return $this->laporan()->exists(); }
    public function scopeBelumLaporan($q) { return $q->whereDate('tanggal','<=',now())->doesntHave('laporan'); }
}
