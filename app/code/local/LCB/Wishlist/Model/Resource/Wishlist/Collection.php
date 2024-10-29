<?php

class LCB_Wishlist_Model_Resource_Wishlist_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('lcb_wishlist/wishlist');
        parent::_construct();
    }
}
