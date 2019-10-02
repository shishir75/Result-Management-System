<?php

namespace App\Imports;

use App\Models\FinalMarks;
use Maatwebsite\Excel\Concerns\ToModel;

class SecondExaminerImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new FinalMarks([
            //
        ]);
    }
}
