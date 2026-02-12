<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Test 1: Get all tasks
echo "=== ALL TASKS ===\n";
$all = DB::table('tasks')->select('id', 'judul', 'assigned_to', 'assigned_to_ids')->get();
var_dump($all);

// Test 2: Query for user 7
echo "\n=== TASKS FOR USER 7 (JSON_CONTAINS) ===\n";
$user7 = DB::table('tasks')
    ->whereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [7])
    ->select('id', 'judul', 'assigned_to', 'assigned_to_ids')
    ->get();
var_dump($user7);

// Test 3: Query with OR condition (assigned_to OR in array)
echo "\n=== TASKS FOR USER 7 (assigned_to OR JSON_CONTAINS) ===\n";
$user7_full = DB::table('tasks')
    ->where(function($q) {
        $q->where('assigned_to', 7)
          ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [7]);
    })
    ->select('id', 'judul', 'assigned_to', 'assigned_to_ids')
    ->get();
var_dump($user7_full);

// Query 4: Check with logging
echo "\n=== RAW SQL ===\n";
DB::enableQueryLog();
DB::table('tasks')
    ->where(function($q) {
        $q->where('assigned_to', 7)
          ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [7]);
    })
    ->select('id', 'judul', 'assigned_to', 'assigned_to_ids')
    ->get();
$queries = DB::getQueryLog();
foreach ($queries as $query) {
    echo $query['query'] . "\n";
    print_r($query['bindings']);
}
