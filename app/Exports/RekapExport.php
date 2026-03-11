<?php
namespace App\Exports;
use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\{FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithTitle};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle {
    public function __construct(private array $filters = []) {}
    public function collection() {
        $dari   = $this->filters['dari']   ?? now()->startOfMonth()->toDateString();
        $sampai = $this->filters['sampai'] ?? now()->toDateString();
        return Jadwal::with(['laporan','penugasan.petugas','jenisKegiatan','shift','lokasi'])->whereDate('tanggal','>=',$dari)->whereDate('tanggal','<=',$sampai)->orderBy('tanggal')->get()->map(fn($j,$i)=>['No'=>$i+1,'Tanggal'=>$j->tanggal->format('d/m/Y'),'Kegiatan'=>$j->nama_kegiatan,'Jenis'=>$j->jenisKegiatan->nama,'Shift'=>$j->shift->nama,'Lokasi'=>$j->lokasi->nama,'Tim'=>$j->satuan??'-','Petugas'=>$j->penugasan->count().' org','Laporan'=>$j->sudahLaporan()?'Ada':'Belum','Kondisi'=>$j->laporan?->kondisi??'-','Pelanggaran'=>$j->laporan?->jumlah_pelanggaran??0,'Waktu Laporan'=>$j->laporan?->waktu_laporan?->format('d/m/Y H:i')??'-','Pelapor'=>$j->laporan?->petugas?->nama??'-']);
    }
    public function headings(): array { return ['No','Tanggal','Nama Kegiatan','Jenis','Shift','Lokasi','Tim','Jml Petugas','Status Laporan','Kondisi','Pelanggaran','Waktu Laporan','Pelapor']; }
    public function styles(Worksheet $sheet) { return [1=>['font'=>['bold'=>true,'color'=>['rgb'=>'FFFFFF']],'fill'=>['fillType'=>'solid','startColor'=>['rgb'=>'2D6A4F']]]]; }
    public function title(): string { return 'Rekap Kegiatan SIPKO'; }
}
