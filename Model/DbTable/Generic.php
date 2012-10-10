<?php
/**
 * Description of Generic
 *
 * @author Paweł
 */
class Commons_Model_DbTable_Address extends Zend_Db_Table_Abstract {

    public function get($id) {
        $id = (int) $id;
        $row = $this->fetchRow($this->_primary . '=' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

}

?>
