@extends('layout.admin')
@section('title','Data Petugas')
@section('page-title','Data Petugas')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">SIPKO</a></li>
<li class="breadcrumb-item active">Data Petugas</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 mb-3 mt-2 align-items-center">
            <form method="GET" class="d-flex flex-wrap gap-2">
                <div class="input-group" style="width:220px">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama / NIP..." value="{{ request('search') }}">
                </div>

               <input type="text" name="satuan" class="form-control" list="satuan-list"
       style="width:130px" placeholder="Semua Tim"
       value="{{ request('satuan') }}">
<datalist id="satuan-list">
    <option value="Tim Alpha">
    <option value="Tim Bravo">
    <option value="Tim Charlie">
</datalist>

                <select name="status" class="form-select" style="width:120px">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <button type="submit" class="btn btn-outline-secondary">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </form>

            <div class="ms-auto">
                <a href="{{ route('admin.petugas.create') }}" class="btn btn-sipko">
                    <i class="bi bi-person-plus"></i> Tambah Petugas
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless table-hover">
                <thead>
                    <tr>
                        <th>Nama / NIP</th>
                        <th>Jabatan</th>
                        <th>Tim</th>
                        <th>No HP</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petugas as $p)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $p->foto_url }}" alt="" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;flex-shrink:0">
                                <div>
                                    <div class="fw-semibold">{{ $p->nama }}</div>
                                    <small class="text-muted">{{ $p->nip }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $p->jabatan }}<br>
                            <small class="text-muted">{{ $p->pangkat }}</small>
                        </td>
                        <td><span class="sipko-badge orange">{{ $p->satuan ?? '-' }}</span></td>
                        <td>{{ $p->no_hp ?? '-' }}</td>
                        <td>
                            @if($p->status === 'aktif')
                                <span class="sipko-badge green"><i class="bi bi-circle-fill"></i> Aktif</span>
                            @else
                                <span class="sipko-badge red"><i class="bi bi-circle-fill"></i> Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.petugas.edit', ['petugas' => $p->id]) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.petugas.toggle', ['petugas' => $p->id]) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-{{ $p->status === 'aktif' ? 'warning' : 'success' }}"
                                            title="{{ $p->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi bi-{{ $p->status === 'aktif' ? 'pause-circle' : 'play-circle' }}"></i>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.petugas.destroy', ['petugas' => $p->id]) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus petugas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-people fs-3 d-block mb-2"></i>Belum ada data petugas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $petugas->links() }}
    </div>
</div>
@endsection
