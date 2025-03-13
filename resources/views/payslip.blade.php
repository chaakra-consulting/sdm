<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 10px;
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid black;
        }

        .kop-surat img {
            height: 70px;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
            float: right;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 18px;
        }

        .kop-text p {
            margin: 2px 0;
            font-size: 14px;
        }

        .slip-gaji {
            font-size: 20px;
            font-weight: bold;
            margin-top: 15px;
            display: block;
            text-align: center;
        }

        .section {
            margin-top: 15px;
        }

        .section table {
            width: 100%;
            border-collapse: collapse;
        }

        .section th, .section td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .no-border td, .no-border th {
            border: none !important;
        }

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .take-home-pay {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Signature Section */
        .signature-section {
            display: flex;
            justify-content: space-between; /* Kolom berada di sisi kiri dan kanan */
            margin-top: 40px;
            text-align: center;
        }

        .signature-column {
            flex: 0 1 auto;
            max-width: 200px;
        }

        .signature-space {
            height: 80px;
            /* border-bottom: 1px solid #000; */
            margin: 20px 0;
            position: relative; /* Untuk memposisikan pseudo-element */
        }

        /* .signature-space::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%; 
            transform: translateX(-50%); 
            width: 100px; 
            height: 1px;
            background-color: #000; 
        } */

        .signature-column p {
            margin: 5px 0;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            padding: 10px 0;
            border-top: 1px solid #ddd;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <img src="{{ public_path('images/chaakra.png') }}" alt="Company Logo" class="logo">
            <div class="kop-text">
                <h2>Chaakra Consulting</h2>
                <p>Jl. Jambangan VII B No.14, Jambangan, Kec. Jambangan, Surabaya, Jawa Timur 60232</p>
                <p>Website: https://chaakra-consulting.com | Telp: 0856-4820-0701</p>
            </div>
        </div>
        <div class="header">
            <center>
                <span class="slip-gaji">Slip Gaji {{ $tanggal_gaji }}</span>
            </center>
        </div>
        <!-- Informasi Karyawan -->
        <div class="section">
            <table class="no-border">
                <tr>
                    <td>Cut Off</td>
                    <td>: {{ $cutoff }}</td>
                    <td>Divisi</td>
                    <td>: {{ $divisi }}</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>: {{ $pegawai_id }}</td>
                    <td>Jabatan</td>
                    <td>: {{ $jabatan }}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>: {{ $pegawai_nama }}</td>
                </tr>
                <tr>
                    <td>NPWP</td>
                    <td>: {{ $npwp }}</td>
                </tr>
            </table>
        </div>

        <!-- Rincian Gaji -->
        <div class="section">
            <table>
                <tr>
                    <th>Pendapatan</th>
                    <th>Jumlah</th>
                    <th>Potongan</th>
                    <th>Jumlah</th>
                </tr>
                @foreach ($earnings as $earning)
                <tr>
                    <td>{{ $earning['name'] }}</td>
                    <td>{{ $earning['amount'] }}</td>
                    @if ($loop->index < count($deductions))
                        <td>{{ $deductions[$loop->index]['name'] }}</td>
                        <td>{{ $deductions[$loop->index]['amount'] }}</td>
                    @else
                        <td></td><td></td>
                    @endif
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Pendapatan</td>
                    <td>{{ $pendapatan_total }}</td>
                    <td>Total Potongan</td>
                    <td>{{ $potongan_total }}</td>
                </tr>
            </table>
        </div>

        <div class="take-home-pay">
            Total Gaji: <strong>Rp. {{ $gaji_total }}</strong>
        </div>

        <div class="signature-section">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; text-align: center;">
                        <p>TTD Divisi SDM,</p>
                        <div class="signature-space"></div>
                        <p>______________________________</p>
                        <p>{{ $nama_admin_sdm }}</p>
                    </td>
                    <td style="width: 50%; text-align: center;">
                        <p>TTD Direktur,</p>
                        <div class="signature-space"></div>
                        <p>______________________________</p>
                        <p>{{ $nama_direktur }}</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    {{-- <div class="footer">
        Payslip ini digenerate oleh <strong>Sistem SDM Chaakraconsulting</strong>
    </div>     --}}
</body>
</html>