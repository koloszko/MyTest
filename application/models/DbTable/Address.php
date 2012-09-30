<?php

class Application_Model_DbTable_Address extends Zend_Db_Table_Abstract {

    protected $_name = 'addresses';
    protected $_primary = 'id';
    protected $_referenceMap = array(
        'Person' => array(
            'columns' => 'id_person',
            'refTableClass' => 'Person',
            'refColumns' => 'id')
    );
    private $_columns = array('id_person', 'town', 'street', 'zipcode');

    public function getByPerson($idPerson) {
        $idPerson = (int) $idPerson;
        $rows = $this->fetchAll('id_person =' . $idPerson);
        return $rows->toArray();
    }

    public function save($data) {
        $helper = new Application_Model_DbTableHelper();
        $row = $helper->populateRow($this->_columns, $data);
        $id = (int)$data['id'];
        
        if ($id > 0) {
            parent::update($row, 'id=' . $id);
        } elseif ($id < 0) {
            parent::delete('id=' . -1*$id);
        } else {
            parent::insert($row, $data);
        }
    }

    public function getColumns() {
        return $this->_columns;
    }

}

