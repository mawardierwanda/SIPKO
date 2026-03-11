<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CheckinRazia extends Model {
    protected $table    = 'checkin_razia';
    protected $fillable = ['titik_razia_id','petugas_id','latitude','longitude','waktu_checkin','catatan'];
    protected $casts    = ['waktu_checkin' => 'datetime'];

    public function titikRazia() { return $this->belongsTo(TitikRazia::class); }
    public function petugas()    { return $this->belongsTo(Petugas::class); }
}
