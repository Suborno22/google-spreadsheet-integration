<?php
require __DIR__.'/vendor/autoload.php';

$client = new \Google_Client();
$client->setApplicationName('sheets PHP');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');

$client->setAuthConfig(__DIR__.'/credentials.json');
$service = new \Google_Service_Sheets($client);
$spreadsheetId = "1KoiCYmkyg8aPLsqle2RL0UrV-Z8HsRK7ARxbTqIq74s";

$range = 'Sheet1';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$rows = $response->getValues();

$headers = array_shift($rows);

// var_dump($headers);

$array = [];
foreach ($rows as $row) {
    // Check if $row is not NULL
    if ($row !== null) {
        if (count($headers) === count($row)) {
            $array[] = array_combine($headers, $row);
        } else {
            // Handle the case where the number of elements is not the same
            // You can log an error, skip this row, or handle it based on your requirements
            // echo "Number of elements in headers and row are not the same for this row\n";
        }
    } else {
        // Handle the case where $row is NULL
        // echo "Row is NULL\n";
    }
}

$jsonString = json_encode($array, JSON_PRETTY_PRINT);
print($jsonString);

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Data</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Poster</th>
                <th>Overview</th>
                <th>Release Date</th>
                <th>Genres</th>
            </tr>
        </thead>
        <tbody id="data-container"></tbody>
    </table>

    <script>
        var jsonData = <?php echo $jsonString; ?>;

        var tableBody = document.getElementById('data-container');

        // Loop through the JSON data and populate the table
        jsonData.forEach(function(movie) {
            var row = tableBody.insertRow();
            
            // Insert cells for each property
            Object.keys(movie).forEach(function(key) {
                var cell = row.insertCell();
                cell.textContent = movie[key];
            });
        });
    </script>
</body>
</html>