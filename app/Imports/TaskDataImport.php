<?php

declare(strict_types=1);

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TaskDataImport implements ToCollection, WithHeadingRow
{
    private Collection $allRows;

    public function headingRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows): void
    {
        $this->allRows = $rows;
    }

    public function getAllRows(): Collection
    {
        return $this->allRows;
    }
}
