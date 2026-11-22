@extends('layouts.cetak')
@section('judul', 'Pratinjau surat permohonan KPI')
@section('aksi-cetak')
    @if (auth()->user()->isAdmin())
        <a href="{{ route('admin.pengajuan.surat.unduh', $pengajuan) }}">Unduh DOCX</a>
    @else
        <a href="{{ route('mahasiswa.pengajuan.surat-permohonan.unduh', $pengajuan) }}">Unduh DOCX</a>
    @endif
@endsection

@section('content')
    <div class="surat-meta">
        <span><strong>Nomor</strong>: {{ $surat->nomor_surat }}</span>
        <span><strong>Lampiran</strong>: -</span>
    </div>
    <h1>PERMOHONAN KERJA PRAKTEK INDUSTRI</h1>

    <div class="surat-recipient">
        <div>Kepada Yth.</div>
        <div>{{ $surat->penerima }} {{ $surat->nama_instansi }}</div>
        <div>Di — {{ $surat->kota_instansi }}</div>
    </div>

    <p>Dengan hormat, yang bertanda tangan di bawah ini:</p>
    <table class="identitas">
        <tr><td>Nama</td><td>: {{ $surat->nama_penandatangan }}</td></tr>
        <tr><td>NIP</td><td>: {{ $surat->nip_penandatangan }}</td></tr>
        <tr><td>Pangkat/Golongan</td><td>: {{ $surat->pangkat_golongan }}</td></tr>
        <tr><td>Jabatan</td><td>: {{ $surat->jabatan_penandatangan }}</td></tr>
    </table>

    <p class="surat-paragraph">Memohon kepada Bapak/Ibu {{ $surat->penerima }} {{ $surat->nama_instansi }} agar mahasiswa kami dapat diberi izin untuk melaksanakan Praktek Kerja Industri di instansi/perusahaan yang Bapak/Ibu pimpin.</p>
    <p class="surat-paragraph">Pelaksanaan Kerja Praktek Industri direncanakan pada tanggal {{ $surat->tanggal_mulai->locale('id')->translatedFormat('j F Y') }} sampai dengan {{ $surat->tanggal_selesai->locale('id')->translatedFormat('j F Y') }}. Adapun nama mahasiswa yang mengikuti kegiatan tersebut:</p>

    <table class="tabel surat-table">
        <thead><tr><th style="width: 8%">No.</th><th>Nama</th><th>NIM</th><th>Program Studi</th></tr></thead>
        <tbody>
            @foreach ($surat->anggota as $i => $anggota)
                <tr><td>{{ $i + 1 }}</td><td>{{ $anggota['nama'] }}</td><td>{{ $anggota['nim'] }}</td><td>{{ $pengajuan->mahasiswa->program_studi }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <p class="surat-paragraph">Demikian surat permohonan izin ini dibuat. Atas perhatian dan kerja samanya kami ucapkan terima kasih.</p>
    <div class="surat-signature">
        <div>{{ $surat->kota_surat }}, {{ $surat->tanggal_surat->locale('id')->translatedFormat('j F Y') }}</div>
        <div>a.n. Dekan</div>
        <div>{{ $surat->jabatan_penandatangan }},</div>
        <div class="surat-signature-space"></div>
        <div class="surat-signature-name">{{ $surat->nama_penandatangan }}</div>
        <div>NIP. {{ $surat->nip_penandatangan }}</div>
    </div>
@endsection
