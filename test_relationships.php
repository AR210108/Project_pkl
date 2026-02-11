<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$userId = 7;

echo "\n=== TEST 1: Without relationships ===\n";
$task1 = Task::where(function($q) use ($userId) {
    $q->where('assigned_to', $userId)
        ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
})->get();
echo "Count: " . $task1->count() . "\n";
if ($task1->count() > 0) {
    echo "IDs: " . implode(', ', $task1->pluck('id')->toArray()) . "\n";
}

echo "\n=== TEST 2: With creator ===\n";
$task2 = Task::where(function($q) use ($userId) {
    $q->where('assigned_to', $userId)
        ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
})->with('creator')->get();
echo "Count: " . $task2->count() . "\n";

echo "\n=== TEST 3: With assigner ===\n";
$task3 = Task::where(function($q) use ($userId) {
    $q->where('assigned_to', $userId)
        ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
})->with('assigner')->get();
echo "Count: " . $task3->count() . "\n";

echo "\n=== TEST 4: With comments ===\n";
$task4 = Task::where(function($q) use ($userId) {
    $q->where('assigned_to', $userId)
        ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
})->with('comments')->get();
echo "Count: " . $task4->count() . "\n";

echo "\n=== TEST 5: With files ===\n";
$task5 = Task::where(function($q) use ($userId) {
    $q->where('assigned_to', $userId)
        ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
})->with('files')->get();
echo "Count: " . $task5->count() . "\n";

echo "\n=== TEST 6: With project ===\n";
$task6 = Task::where(function($q) use ($userId) {
    $q->where('assigned_to', $userId)
        ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
})->with('project')->get();
echo "Count: " . $task6->count() . "\n";

echo "\n=== TEST 7: With targetDivisi ===\n";
$task7 = Task::where(function($q) use ($userId) {
    $q->where('assigned_to', $userId)
        ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
})->with('targetDivisi')->get();
echo "Count: " . $task7->count() . "\n";

echo "\n=== TEST 8: With ALL relationships ===\n";
$task8 = Task::where(function($q) use ($userId) {
    $q->where('assigned_to', $userId)
        ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
})->with(['creator', 'assigner', 'comments', 'files', 'project', 'targetDivisi'])->get();
echo "Count: " . $task8->count() . "\n";
if ($task8->count() > 0) {
    echo "IDs: " . implode(', ', $task8->pluck('id')->toArray()) . "\n";
}

echo "\n=== SQL Queries ===\n";
echo "Test 1 SQL:\n";
$task1Query = Task::where(function($q) use ($userId) {
    $q->where('assigned_to', $userId)
        ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
});
echo $task1Query->toSql() . "\n";
echo "Params: " . json_encode($task1Query->getBindings()) . "\n";
?>
