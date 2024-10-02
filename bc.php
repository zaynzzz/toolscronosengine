$key = 'CE-OX6GW51V3IYFX90N';
$token = '3AGVxytvo7k7TI7qWHB9jISu5y4d4N1N';
$ids = 
["347e875b-56b8-4b65-8a11-deff5829205b", "3e310a3a-a675-4ad7-ae0f-af4ae91a5b83", "a89de03e-c68f-422f-9a26-f164428dbda5", "lpkil2qzp2ywxnwiwvydbs", "lxajublbkwmtjjnsn3c4qf", "udoendr0clj9bwthnglc9f", "efdupw7gtgitq3th6dyctu", "h2brixqhwaicbzaa8oft4h", "ildy6apeez7jpo0wmlrngc", "hzcyvukdctjho8orsf5hpv", "f3b06c31-f9e0-4301-8e3b-e5fd6c51838f", "7104528e-6aa1-4e38-8e3f-a36d7d58304c", "82ef0550-55f8-476c-94d5-331eb2f384bb", "ggfdzll2d1ampvshb7udqa", "2720701a-07e2-4408-8c0c-0e2c9c383d4c", "68f6247d-7679-454f-9890-e0bc9202bd2a", "ebvdbq0d9sxrqjw0ellq6u", "jb1jecu2gpvlumzhnzdcq9", "vw7qzgvzviowxzqpz7odzv", "lr0zulnvasqvpsnn5hwjdf", "cdc7d21c-9c5b-4ac1-99af-9d41af3b544f"];
checkApi($ids, $key, $token);
function checkApi($ids, $key, $token) {
    $urlBase = 'https://api.cronosengine.com/api/check/';
    $headers = [
        'On-Key: ' . $key,
        'On-Token: ' . $token,
        'On-Signature: ' . hash_hmac('sha512', $key, $token),
        'Accept: application/json'
    ];

    foreach ($ids as $id) {
        $ch = curl_init();
        $url = $urlBase . $id . '?resendCallback=true';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        // echo $response . "\n";
        $res = json_decode($response, true);
        $responseCode = $res;
        echo $responseCode['responseData']['id']." : ".$responseCode['responseMessage']."</br>";

    }
}