@php
    $jumlahPengajuanAdmin = \App\Models\PengajuanKpi::where('status', \App\Models\PengajuanKpi::DIAJUKAN)->count();
    $jumlahDokumenAdmin = \App\Models\DokumenKpi::query()
        ->selectRaw('jenis_dokumen, COUNT(*) as total')
        ->where('status_verifikasi', \App\Models\DokumenKpi::MENUNGGU)
        ->whereIn('jenis_dokumen', \App\Models\DokumenKpi::PERLU_VERIFIKASI)
        ->groupBy('jenis_dokumen')
        ->pluck('total', 'jenis_dokumen');
    $jumlahAkunMenunggu = \App\Models\User::where('role', \App\Models\User::PEMBIMBING)
        ->where('status', \App\Models\User::MENUNGGU)
        ->count();
    $peringatanAdmin = [];

    if ($jumlahPengajuanAdmin) {
        $peringatanAdmin[] = ['jumlah' => $jumlahPengajuanAdmin, 'teks' => 'pengajuan KPI mahasiswa menunggu verifikasi', 'url' => route('admin.pengajuan.index', ['status' => 'diajukan'])];
    }

    $jumlahSuratBalasan = (int) ($jumlahDokumenAdmin['surat_balasan'] ?? 0);
    if ($jumlahSuratBalasan) {
        $peringatanAdmin[] = ['jumlah' => $jumlahSuratBalasan, 'teks' => 'surat balasan menunggu verifikasi', 'url' => route('admin.verifikasi.index', ['tab' => 'surat', 'status' => 'menunggu'])];
    }

    $jumlahPenilaian = (int) ($jumlahDokumenAdmin['penilaian_penguji'] ?? 0) + (int) ($jumlahDokumenAdmin['penilaian_lapangan'] ?? 0);
    if ($jumlahPenilaian) {
        $peringatanAdmin[] = ['jumlah' => $jumlahPenilaian, 'teks' => 'dokumen penilaian menunggu verifikasi', 'url' => route('admin.verifikasi.index', ['tab' => 'penilaian', 'status' => 'menunggu'])];
    }

    $jumlahNilaiAkhir = (int) ($jumlahDokumenAdmin['nilai_akhir'] ?? 0);
    if ($jumlahNilaiAkhir) {
        $peringatanAdmin[] = ['jumlah' => $jumlahNilaiAkhir, 'teks' => 'dokumen nilai akhir menunggu verifikasi', 'url' => route('admin.verifikasi.index', ['tab' => 'nilai_akhir', 'status' => 'menunggu'])];
    }

    if ($jumlahAkunMenunggu) {
        $peringatanAdmin[] = ['jumlah' => $jumlahAkunMenunggu, 'teks' => 'akun dosen menunggu aktivasi', 'url' => route('admin.pengguna.index', ['peran' => 'pembimbing'])];
    }
@endphp

<aside class="pk-admin-reminders" aria-label="Pengingat tindak lanjut admin">
    <span class="pk-admin-reminders-label"><i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i> Tindak lanjut</span>
    @if ($peringatanAdmin)
        <div class="pk-admin-reminders-viewport">
            <div class="pk-admin-reminders-marquee">
                @foreach ([false, true] as $duplikat)
                    <div class="pk-admin-reminders-track" @if ($duplikat) aria-hidden="true" @endif>
                        @foreach ($peringatanAdmin as $peringatan)
                            <a href="{{ $peringatan['url'] }}" @if ($duplikat) tabindex="-1" @endif>
                                <strong>{{ $peringatan['jumlah'] }}</strong> {{ $peringatan['teks'] }}
                                <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                            </a>
                            <span class="pk-admin-reminders-separator" aria-hidden="true">•</span>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <span class="pk-admin-reminders-empty"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Tidak ada verifikasi tertunda</span>
    @endif
</aside>
