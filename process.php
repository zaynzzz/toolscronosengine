<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = $_POST['key'];
    $token = $_POST['token'];
    $idsInput = $_POST['ids'];

    // Convert comma-separated IDs into an array
    $ids = array_map('trim', explode(',', $idsInput));

    checkApi($ids, $key, $token);
}

function checkApi($ids, $key, $token) {
    $urlBase = 'https://api.cronosengine.com/api/check/';
    $headers = [
        'On-Key: ' . $key,
        'On-Token: ' . $token,
        'On-Signature: ' . hash_hmac('sha512', $key, $token),
        'Accept: application/json'
    ];

    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>API Response</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="container mt-5">
            <h2 class="text-center">API Response</h2>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Results</h5>
                    <div class="list-group">'; // Start a list group for better formatting

    foreach ($ids as $id) {
        $ch = curl_init();
        $url = $urlBase . $id . '?resendCallback=true';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $res = json_decode($response, true);
        
        if (isset($res['responseData'])) {
            echo '<a href="https://backoffice.cronosengine.com/transactions?tableSearch='.$res['responseData']['id'].'" target="_blank" class="list-group-item list-group-item-action">' . 
                 '<strong>ID:</strong> ' . htmlspecialchars($res['responseData']['id']) . '<br>' . 
                 '<strong>Message:</strong> ' . htmlspecialchars($res['responseMessage']) . 
                 '</a>';
        } else {
            echo '<a href="#" class="list-group-item list-group-item-action text-danger">' . 
                 'Error: No response data for ID ' . htmlspecialchars($id) . 
                 '</a>';
        }
    }

    echo '</div>'; // End of list group
    echo '</div>'; // End of card body
    echo '</div>'; // End of card
    echo '</div>'; // End of container
    echo '</body></html>'; // Close the HTML structure
}
?>
