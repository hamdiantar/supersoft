<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class DB_Backup extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_db-backup');
    }

    function index() {

        if (!auth()->user()->can('view_db-backup')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $targetTables = [];
        $newLine = "\r\n";

        $get_all_table_query = "SHOW TABLES";
        $result = DB::select(DB::raw($get_all_table_query));
        foreach($result as $table){
            foreach($table as $t) {
                $targetTables[] = $t;
            }
        }

        $structure = '';
        $data = '';
        foreach ($targetTables as $table) {
            $show_table_query = "SHOW CREATE TABLE " . $table . "";

            $show_table_result = DB::select(DB::raw($show_table_query));

            foreach ($show_table_result as $show_table_row) {
                $show_table_row = (array)$show_table_row;
                $structure .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
            }
            $select_query = "SELECT * FROM " . $table;
            $records = DB::select(DB::raw($select_query));

            foreach ($records as $record) {
                $record = (array)$record;
                $table_column_array = array_keys($record);
                foreach ($table_column_array as $key => $name) {
                    $table_column_array[$key] = '`' . $table_column_array[$key] . '`';
                }

                $table_value_array = array_values($record);
                $data .= "\nINSERT INTO $table (";

                $data .= "" . implode(", ", $table_column_array) . ") VALUES \n";

                foreach($table_value_array as $key => $record_column)
                    $table_value_array[$key] = $record_column == NULL ? NULL : addslashes($record_column);

                $data .= "('" . implode("','", $table_value_array) . "');\n";
            }
        }
        $file = 'database_backup_on_' . date('y_m_d') . '.sql';
        $file_name = public_path($file);
        $file_handle = fopen($file_name, 'w + ');

        $output = $structure . $data;
        fwrite($file_handle, $output);
        fclose($file_handle);

        $link = asset($file);
        return view("db-backup" ,compact('link'));
    }
}
