<?php
session_start();
include 'config.php';
if (!isset($_SESSION['username'])) {
    header("location: login.php");
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM pelanggan WHERE username='$username'";
$result = $conn->query($sql);
$pelanggan = $result->fetch_assoc();

$id_pelanggan = $pelanggan['id_pelanggan'];

$sql = "SELECT * FROM tagihan WHERE id_pelanggan='$id_pelanggan' AND status='Belum Bayar'";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_tagihan = $_POST['id_tagihan'];
    $tanggal_pembayaran = date("Y-m-d");
    $biaya_admin = 2500;

    $sql = "SELECT * FROM tagihan WHERE id_tagihan='$id_tagihan'";
    $result_tagihan = $conn->query($sql);
    $tagihan = $result_tagihan->fetch_assoc();

    $jumlah_meter = $tagihan['jumlah_meter'];
    $sql = "SELECT * FROM tarif WHERE id_tarif=" . $pelanggan['id_tarif'];
    $result_tarif = $conn->query($sql);
    $tarif = $result_tarif->fetch_assoc();

    $total_bayar = ($jumlah_meter * $tarif['tarifperkwh']) + $biaya_admin;

    $sql = "INSERT INTO pembayaran (id_tagihan, id_pelanggan, tanggal_pembayaran, bulan_bayar, biaya_admin, total_bayar, id_user)
            VALUES ('$id_tagihan', '$id_pelanggan', '$tanggal_pembayaran', '" . $tagihan['bulan'] . "', '$biaya_admin', '$total_bayar', '1')";

    if ($conn->query($sql) === TRUE) {
        $sql = "UPDATE tagihan SET status='Lunas' WHERE id_tagihan='$id_tagihan'";
        $conn->query($sql);
        echo "Pembayaran berhasil.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
</head>
<body>
    <h1>Pembayaran</h1>
    <form method="POST" action="">
        <select name="id_tagihan" required>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <option value="<?php echo $row['id_tagihan']; ?>"><?php echo $row['bulan'] . " " . $row['tahun']; ?></option>
            <?php } ?>
        </select>
        <button type="submit">Bayar</button>
    </form>
</body>
</html>
