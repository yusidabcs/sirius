<?php
namespace core\app\classes\spreadsheet;

class spreadsheet_filter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {

    public function __construct($column_range = array('A', 'Z'))
    {
        $this->column_range = $column_range;
    }

    public function readCell($column, $row, $worksheetName = '')
    {
        if (in_array($column, $this->column_range)) {
            return true;
        }

        return false;
    }

}