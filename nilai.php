<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nilai</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #8e2de2, #f000cc);
            margin: 0;
            color: white;
        }

        .container {
            padding: 30px;
        }

        table {
            width: 100%;
            background: white;
            color: black;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background: #8e2de2;
            color: white;
        }

        td, th {
            padding: 15px;
            text-align: center;
        }

        tr:hover {
            background: #f2f2f2;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back">⬅ Kembali</a>

    <h2>📊 Nilai Siswa</h2>

    <table>
        <tr>
            <th>Mata Pelajaran</th>
            <th>Nilai</th>
        </tr>
        <tr>
            <td>Matematika</td>
            <td>85</td>
        </tr>
        <tr>
            <td>Bahasa Indonesia</td>
            <td>90</td>
        </tr>
        <tr>
            <td>IPA</td>
            <td>88</td>
        </tr>
    </table>
</div>

</body>
</html>