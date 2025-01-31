<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\SpreadsheetService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    private SpreadsheetService $spreadsheet;

    public function __construct(SpreadsheetService $spreadsheet)
    {
        $this->spreadsheet = $spreadsheet;
    }


    public function getProjectStatusOverview(): JsonResponse
    {
        $rows = $this->loadData();

        $statusCounts = [];
        foreach ($rows as $row) {
            $status = $row['project_status'] ?? 'Unknown';

            if (!isset($statusCounts[$status])) {
                $statusCounts[$status] = 0;
            }
            $statusCounts[$status]++;
        }

        return response()->json($statusCounts);
    }

    public function getProjectsByArea(): JsonResponse
    {
        $rows = $this->loadData();

        // Define all expected areas
        $expectedAreas = ['Technology', 'People and Culture', 'Operations', 'Marketing', 'Customer Experience'];

        // Initialize area counts with zero values
        $areaCounts = array_fill_keys($expectedAreas, 0);

        // Count occurrences in the dataset
        foreach ($rows as $row) {
            $area = $row['area'] ?? null;
            if ($area !== null && isset($areaCounts[$area])) {
                $areaCounts[$area]++;
            }
        }

        // Convert results to an array
        $results = [];
        foreach ($areaCounts as $area => $count) {
            $results[] = [
                'area'  => $area,
                'count' => $count
            ];
        }

        return response()->json($results);
    }

    public function getBudgetSummary()
    {
        $rows = $this->loadData();
        $totalBudget = 0.0;
        $count = 0;

        $areaBudgets = [
            'Technology'          => 0.0,
            'Marketing'           => 0.0,
            'People and Culture'  => 0.0,
            'Customer Experience' => 0.0,
            'Operations'          => 0.0,
        ];

        foreach ($rows as $row) {
            $budgetStr = (string) ($row['budget'] ?? '0');
            $budgetVal = (float) str_replace(['£', ',', '$'], '', $budgetStr);
            $totalBudget += $budgetVal;
            $count++;

            $area = $row['area'] ?? 'Unknown';
            if (isset($areaBudgets[$area])) {
                $areaBudgets[$area] += $budgetVal;
            }
        }

        $averageBudget = $count > 0 ? $totalBudget / $count : 0.0;

        return response()->json([
            'totalBudget'   => $totalBudget,
            'averageBudget' => $averageBudget,
            'breakdown'     => $areaBudgets,
        ]);
    }

    public function getProjectSizeSummary(): JsonResponse
    {
        $rows = $this->loadData();

        $sizeCounts = [];
        foreach ($rows as $row) {
            $size = $row['project_size'] ?? 'Unknown';
            if (!isset($sizeCounts[$size])) {
                $sizeCounts[$size] = 0;
            }
            $sizeCounts[$size]++;
        }

        $results = [];
        foreach ($sizeCounts as $size => $count) {
            $results[] = [
                'size'  => $size,
                'count' => $count
            ];
        }

        return response()->json($results);
    }

    public function getPriorityProjects(): JsonResponse
    {
        $rows = $this->loadData();

        // Define all expected priorities
        $expectedPriorities = ['High', 'Medium', 'Low'];

        // Initialize priority counts with zero values
        $priorityCounts = array_fill_keys($expectedPriorities, 0);

        // Count occurrences in the dataset
        foreach ($rows as $row) {
            $priority = $row['priority'] ?? null;
            if ($priority !== null && isset($priorityCounts[$priority])) {
                $priorityCounts[$priority]++;
            }
        }

        return response()->json($priorityCounts);
    }

    private function loadData()
    {
        $path = storage_path('app/public/DashboardTaskData.xlsx');

        return $this->spreadsheet->parseSpreadsheet($path);
    }
}
