<!DOCTYPE html>
<html>

<head>
    <title>Data Laporan Luas Tanaman Padi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Data Laporan Luas Tanaman Padi</h2>
    <p>{{ $title }}</p>
    <p>{{ $date }}</p>
    <h3>Filter yang Digunakan:</h3>
    <ul>
        @if (!empty($filterKecamatan))
            <li>Kecamatan: {{ $filterKecamatan }}</li>
        @endif
        @if (!empty($filterDesa))
            <li>Desa: {{ $filterDesa }}</li>
        @endif
        @if (!empty($filterDate))
            <li>Tanggal: {{ $filterDate }}</li>
        @endif
    </ul>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Alamat</th>
                <th>Jenis Lahan</th>
                <th>Tanggal Input</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
