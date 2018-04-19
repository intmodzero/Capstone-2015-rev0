<?php
function convert_to_csv($input_array, $output_file_name, $delimiter)
{
    $file = fopen('php://memory', 'w');
    
    /** loop through array  */
    foreach ($input_array as $line) {
        /** default php csv handler **/
        fputcsv($file, $line, $delimiter);
    }
    /** rewrind the "file" with the csv lines **/
    fseek($file, 0);
    /** modify header to be downloadable csv file **/
    header('Content-Type: application/csv');
    header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
    /** Send file to browser for download */
    fpassthru($file);
    fclose($file);
}
?>
