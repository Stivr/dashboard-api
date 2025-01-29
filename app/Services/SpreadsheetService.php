<?php

declare(strict_types=1);

namespace App\Services;

use App\Imports\TaskDataImport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class SpreadsheetService
{
    protected string $spreadsheetPath;

    public function __construct()
    {
        // Hardcode to save time
        $this->spreadsheetPath = storage_path('app/public/TaskDataSpreadsheet.xlsx');
    }

    public function parseSpreadsheet(string $path): Collection
    {
        $import = new TaskDataImport();

        Excel::import($import, $path);

        return $import->getAllRows();
    }
}
