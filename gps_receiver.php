<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Validasi data
    if (!empty($latitude) && !empty($longitude)) {
        // Simpan data ke file atau database
        $data = "Lat: $latitude, Lon: $longitude\n";
        file_put_contents('gps_data.txt', $data, FILE_APPEND);

        // Kirim respons sukses
        echo "Data received successfully!";
    } else {
        echo "Invalid data!";
    }
} else {
    echo "Only POST requests are accepted!";
}
?>
