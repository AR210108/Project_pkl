$result = Invoke-RestMethod 'http://127.0.0.1:8000/debug/tasks-for-user-int/7'
$allTasks = $result.value

if ($allTasks -is [array]) {
    $task16 = $allTasks | Where-Object { $_.id -eq 16 }
} else {
    $task16 = $result.value | Where-Object { $_.id -eq 16 }
}

if ($task16) {
    Write-Host "SUCCESS: Task 16 found for user 7!"
    Write-Host "Task name: $($task16.nama_tugas)"
    Write-Host "Assigned to IDs: $($task16.assigned_to_ids -join ', ')"
} else {
    Write-Host "FAILED: Task 16 NOT found for user 7"
    Write-Host "Total tasks found: $(($result.value | Measure-Object).Count)"
}
