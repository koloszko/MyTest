<?php

/**
 * Description of DbTableHelper
 *
 * @author Paweł
 */
class Application_Model_DbTableHelper {

    public function populateRow(array $columns, array $data) {
        $row = array();
        foreach ($columns as $column) {
            $row[$column] = $data[$column];
        }
        return $row;
    }

}

?>
