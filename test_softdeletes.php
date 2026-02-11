<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Task;

$userId = 7;

echo "=== CHECK SOFTDELETES STATUS ===\n\n";

// Check deleted_at status
echo "Test 1: Check deleted_at values in tasks table\n";
$deleted = DB::table('tasks')
    ->whereIn('id', [18, 19, 20, 21, 22])
    ->select('id', 'judul', 'deleted_at')
    ->get();
foreach ($deleted as $t) {
    echo "Task {$t->id}: deleted_at = " . ($t->deleted_at ?? 'NULL') . "\n";
}

echo "\n\nTest 2: Eloquent with withoutTrashed (should be 5)\n";
$withoutTrashed = Task::withoutTrashed()
    ->where(function($q) use ($userId) {
        $q->where('assigned_to', $userId)
          ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
    })
    ->get();
echo "Count: " . $withoutTrashed->count() . "\n";

echo "\n\nTest 3: Eloquent with onlyTrashed (deleted ones)\n";
$onlyTrashed = Task::onlyTrashed()
    ->where(function($q) use ($userId) {
        $q->where('assigned_to', $userId)
          ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
    })
    ->get();
echo "Count: " . $onlyTrashed->count() . "\n";
echo "IDs: " . implode(', ', $onlyTrashed->pluck('id')->toArray()) . "\n";

echo "\n\nTest 4: Eloquent with withTrashed (all)\n";
$withTrashed = Task::withTrashed()
    ->where(function($q) use ($userId) {
        $q->where('assigned_to', $userId)
          ->orWhereRaw("JSON_CONTAINS(assigned_to_ids, JSON_ARRAY(?))", [$userId]);
    })
    ->get();
echo "Count: " . $withTrashed->count() . "\n";
echo "IDs: " . implode(', ', $withTrashed->pluck('id')->toArray()) . "\n";
