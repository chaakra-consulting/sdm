@foreach($pegawais as $pegawai)
    <table>
        <tr><td>Nama</td><td>{{ $pegawai->nama_lengkap ?? ''}}</td></tr>
        <tr><td>Jabatan</td><td>{{ $pegawai->kepegawaian->subJabatan && $pegawai->kepegawaian->subJabatan->nama_sub_jabatan ? $pegawai->kepegawaian->subJabatan->nama_sub_jabatan : ''}}</td></tr>
        <tr><td>Bank</td><td></td></tr>
        <tr><td>a/n</td><td></td></tr>
    </table>

    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>In</th>
                <th>Out</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $pegawaiAbsensis = $absensis->get($pegawai->id, collect());
            @endphp

            @foreach($pegawaiAbsensis as $index => $absen)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($absen->tanggal_kerja)->format('d/m/Y') ?? '-'}}</td>
                <td>{{ $absen->hari_kerja ?? '-' }}</td>
                <td>{{ $absen->waktu_masuk }}</td>
                <td>{{ $absen->waktu_pulang }}</td>
                <td>{{ $absen->keteranganAbsensi && $absen->keteranganAbsensi->nama ? $absen->keteranganAbsensi->nama : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
