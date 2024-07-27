<?php
include '../config.php';

$id = $_GET['id'];
$sql = "DELETE FROM tarif WHERE id_tarif=$id";

if ($conn->query($sql) === TRUE) {
    header("location: read.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
