<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Exports\RekapExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class RekapController extends Controller {
    public function index(Request $r) { return view('admin.rekap.index',$this->getData($r)); }
    public function exportPdf(Request $r) {
        $data = $this->getData($r);
        $pdf  = Pdf::loadView('admin.rekap.pdf',$data)->setPaper('A4','landscape');
        return $pdf->download('rekap-sipko-'.now()->format('Y-m').'.pdf');
    }
    public function exportExcel(Request $r) {
        return Excel::download(new RekapExport($r->all()),'rekap-sipko-'.now()->format('Y-m').'.xlsx');
    }
    private function getData(Request $r): array {
        $dari   = $r->dari   ?? now()->startOfMonth()->toDateString();
        $sampai = $r->sampai ?? now()->toDateString();
        $jadwal = Jadwal::with(['laporan','penugasan.petugas','jenisKegiatan','shift','lokasi'])->whereDate('tanggal','>=',$dari)->whereDate('tanggal','<=',$sampai)->orderBy('tanggal')->get();
        return ['jadwal'=>$jadwal,'dari'=>$dari,'sampai'=>$sampai,'total_jadwal'=>$jadwal->count(),'sudah_laporan'=>$jadwal->filter(fn($j)=>$j->sudahLaporan())->count(),'belum_laporan'=>$jadwal->filter(fn($j)=>!$j->sudahLaporan())->count()];
    }
}
