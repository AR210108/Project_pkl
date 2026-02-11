<?php
// Test API endpoint untuk check assigned_names field

$ch = curl_init('http://localhost:8000/manager-divisi/api/tasks');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest',
    'Cookie: XSRF-TOKEN=null; laravel_session=null'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: " . $http_code . PHP_EOL;
echo PHP_EOL;

$data = json_decode($response, true);

if (!$data) {
    echo "Failed to parse JSON response" . PHP_EOL;
    echo "Raw response: " . substr($response, 0, 500) . PHP_EOL;
    exit;
}

if (isset($data['data']) && is_array($data['data']) && count($data['data']) > 0) {
    foreach ($data['data'] as $i => $task) {
        echo "=== Task " . ($i + 1) . " ===" . PHP_EOL;
        echo "ID: " . $task['id'] . PHP_EOL;
        echo "assigned_to: " . $task['assigned_to'] . PHP_EOL;
        echo "assigned_to_ids: " . json_encode($task['assigned_to_ids']) . PHP_EOL;
        echo "assigned_names: " . ($task['assigned_names'] ?? 'NULL') . PHP_EOL;
        echo "assignee_name: " . ($task['assignee_name'] ?? 'NULL') . PHP_EOL;
        echo PHP_EOL;
        
        if ($i >= 2) break; // Only show first 3
    }
} else {
    echo "No data in response" . PHP_EOL;
    var_dump($data);
}
