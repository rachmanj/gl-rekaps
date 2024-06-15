<?php

namespace App\Imports;

use App\Models\Transit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransitImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Transit([
            'create_date' => $this->convert_date($row['create_date']),
            'posting_date' => $this->convert_date($row['posting_date']),
            'tx_num' => $row['tx_num'],
            'doc_num' => $row['doc_num'],
            'doc_type' => $row['doc_type'],
            'project_code' => $row['project_code'],
            'account' => $row['account'],
            'debit' => $row['debit'],
            'credit' => $row['credit'],
            'remarks' => $row['remarks'],
        ]);
    }

    public function convert_date($date)
    {
        if ($date) {
            $year = substr($date, 6, 4);
            $month = substr($date, 3, 2);
            $day = substr($date, 0, 2);
            $new_date = $year . '-' . $month . '-' . $day;
            return $new_date;
        } else {
            return null;
        }
    }
}
