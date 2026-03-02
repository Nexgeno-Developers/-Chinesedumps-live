<?php
// daily_status.php
$file = __DIR__ . '/daily_status_stable-unstable.json';
$today = date('Y-m-d');

// If JSON file doesn't exist or date changed → regenerate
if (!file_exists($file) || json_decode(file_get_contents($file), true)['date'] != $today) {
    $statuses = [];
    // Suppose you have 50 rows, change this accordingly
    $totalRows = 50; 

    for ($i = 0; $i < $totalRows; $i++) {
        // Random Stable / Unstable
        $statuses[] = (mt_rand(0, 1) == 1) ? 'Stable' : 'Unstable';
    }

    $data = [
        'date' => $today,
        'statuses' => $statuses
    ];
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Return JSON
header('Content-Type: application/json');
echo file_get_contents($file);
