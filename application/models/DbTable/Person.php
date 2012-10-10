<?php

class Application_Model_DbTable_Person extends Commons_Model_DbTable_Generic {

    const ADDRESSES_KEY = 'addresses';

    protected $_name = 'persons';
    protected $_primary = 'id';
    private $_columns = array('name', 'surname');

    public function getWithDependencies($id) {
        $row = $this->get($id);

        $addressTable = new Application_Model_DbTable_Address();
        $row[self::ADDRESSES_KEY] = $addressTable->getByPerson($row['id']);
        return $row;
    }

    public function save(array $data) {
        $helper = new Application_Model_DbTableHelper();
        $this->getAdapter()->beginTransaction();

        try {
            if ($data[$this->_primary] > 0) {
                $where = $this->_primary . '=' . $data['id'];
                parent::update($helper->populateRow($this->_columns, $data), $where);
            } else {
                $data[$this->_primary] = parent::insert($helper->populateRow($this->_columns, $data));
            }
            $this->_saveDependencies($data);

            $this->getAdapter()->commit();
        } catch (Exception $exc) {
            $this->getAdapter()->rollBack();
            throw $exc;
        }
    }

    public function getEmptyRow() {
        $emptyRow = $this->createRow()->toArray();

        $addressTable = new Application_Model_DbTable_Address();
        $emptyRow[self::ADDRESSES_KEY] = array();
        $emptyRow[self::ADDRESSES_KEY][1] = $addressTable->createRow()->toArray();
        return $emptyRow;
    }

    private function _saveDependencies($data) {
        if (count($data[self::ADDRESSES_KEY]) > 0) {
            $addressDbTable = new Application_Model_DbTable_Address();
            foreach ($data[self::ADDRESSES_KEY] as $address) {
                $address['id_person'] = $data['id'];
                $addressDbTable->save($address);
            }
        }
    }

}

