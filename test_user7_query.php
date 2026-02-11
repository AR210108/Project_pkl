<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Task;

$userId = 7;

echo "=== TESTING QUERY FOR USER 7 ===\n\n";

// Test 1: Raw query
echo "Test 1: Raw DB query\n";
$raw = DB::table('tasks')
    ->where(function($q) use ($userId) {
        $q->where('assigned_to', $userId)
          ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
    })
    ->select('id', 'judul', 'assigned_to', 'assigned_to_ids', 'status')
    ->get();
echo "Count: " . $raw->count() . "\n";
var_dump($raw);

// Test 2: Eloquent query
echo "\n\nTest 2: Eloquent query (without loading relationships)\n";
$eloquent = Task::where(function($q) use ($userId) {
    $q->where('assigned_to', $userId)
      ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
})
->select('id', 'judul', 'assigned_to', 'assigned_to_ids', 'status')
->get();
echo "Count: " . $eloquent->count() . "\n";
var_dump($eloquent);

// Test 3: Check Task model casting
echo "\n\nTest 3: Check Task model\n";
$task = Task::find(18);
if ($task) {
    echo "Task 18 found\n";
    echo "assigned_to_ids type: " . gettype($task->assigned_to_ids) . "\n";
    echo "assigned_to_ids value: ";
    var_dump($task->assigned_to_ids);
    echo "Is array?: " . (is_array($task->assigned_to_ids) ? 'YES' : 'NO') . "\n";
    if (is_array($task->assigned_to_ids)) {
        echo "In array check (7): " . (in_array(7, $task->assigned_to_ids) ? 'TRUE' : 'FALSE') . "\n";
    }
}

// Test 4: Query with relationships
echo "\n\nTest 4: Eloquent query with relationships\n";
$eloquentRel = Task::with(['creator', 'assigner', 'comments', 'files', 'project', 'targetDivisi'])
    ->where(function($q) use ($userId) {
        $q->where('assigned_to', $userId)
          ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
    })
    ->select('id', 'judul', 'assigned_to', 'assigned_to_ids', 'status')
    ->get();
echo "Count: " . $eloquentRel->count() . "\n";
echo "IDs: " . implode(', ', $eloquentRel->pluck('id')->toArray()) . "\n";

// Test 5: Check assigned_to filter only
echo "\n\nTest 5: Only check assigned_to = 7\n";
$onlyAssignedTo = Task::where('assigned_to', 7)->get();
echo "Count: " . $onlyAssignedTo->count() . "\n";

// Test 6: Check assigned_to_ids contains 7
echo "\n\nTest 6: Only check JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(7))\n";
$onlyJsonContains = Task::whereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [7])->get();
echo "Count: " . $onlyJsonContains->count() . "\n";
echo "IDs: " . implode(', ', $onlyJsonContains->pluck('id')->toArray()) . "\n";
