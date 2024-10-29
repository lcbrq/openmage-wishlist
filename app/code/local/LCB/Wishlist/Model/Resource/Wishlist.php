<?php

class LCB_Wishlist_Model_Resource_Wishlist extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('lcb_wishlist/wishlist', 'id');
    }
}
