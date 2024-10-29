<?php

class LCB_Wishlist_Model_Wishlist extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('lcb_wishlist/wishlist');
        parent::_construct();
    }

    /**
     * @param $data
     * @return bool
     */
    public function addToWishlist($data)
    {
        $resource = Mage::getSingleton('core/resource');
        $write = $resource->getConnection('core_write');
        $table = $resource->getTableName('lcb_wishlist');
        $exists = $this->checkIfExists($data);
        if ($data['scope'] == 'category') {
            $item = explode('_', $data['item']);
            $itemId = $item[1];
            $itemGroup = $item[0];
        } else {
            $itemId = $data['item'];
            $itemGroup = 'product';
        }
        if ($exists) {
            return false;
        } else {
            $write->insert(
                $table,
                [
                    'customer_id' => $data['user'],
                    'customer_group' => $data['userGroup'],
                    'item_id' => $itemId,
                    'item_group' => $itemGroup,
                    'item_subgroup' => $data['subScope'],
                    'item_path' => $data['url'],
                    'created_at' => varien_date::now()
                ]
            );
        }
        return true;
    }

    /**
     * @param $data
     * @return bool
     * @throws Mage_Core_Exception
     */
    public function checkIfExists($data)
    {
        if ($data['scope'] == 'category') {
            $item = explode('_', $data['item']);
            $itemId = $item[1];
            $itemGroup = $item[0];
        } else {
            $itemId = $data['item'];
            $itemGroup = 'product';
        }

        $wish = $this->getCollection()
            ->addFieldToFilter('customer_id', array('eq' => $data['user']))
            ->addFieldToFilter('customer_group', array('eq' => $data['userGroup']))
            ->addFieldToFilter('item_id', array('eq' => $itemId))
            ->addFieldToFilter('item_group', array('eq' => $itemGroup))
            ->addFieldToFilter('item_subgroup', array('eq' => $data['subScope']));

        if (!empty($wish->getData())) {
            return true;
        }
        return false;
    }

    /**
     * @param $data
     * @return bool|Exception
     * @throws Mage_Core_Exception
     * @throws Throwable
     */
    public function removeFromWishList($data)
    {
        $exists = $this->checkIfExists($data);
        if ($data['scope'] == 'category') {
            $item = explode('_', $data['item']);
            $itemId = $item[1];
            $itemGroup = $item[0];
        } else {
            $itemId = $data['item'];
            $itemGroup = 'product';
        }
        if (!$exists) {
            return false;
        } else {
            $wish = $this->getCollection()
                ->addFieldToFilter('customer_id', array('eq' => $data['user']))
                ->addFieldToFilter('customer_group', array('eq' => $data['userGroup']))
                ->addFieldToFilter('item_id', array('eq' => $itemId))
                ->addFieldToFilter('item_group', array('eq' => $itemGroup))
                ->addFieldToFilter('item_subgroup', array('eq' => $data['subScope']));

            if (!empty($wish->getData())) {
                try {
                    foreach ($wish as $coll) {
                        $this->load($coll->getId())->delete();
                    }
                } catch (Exception $e) {
                    return $e;
                }
            }
        }
        return true;
    }

    /**
     * @param $wishlistId
     * @return bool|Exception
     * @throws Mage_Core_Exception
     * @throws Throwable
     */
    public function removeFromWishListById($wishlistId)
    {
        $wish = $this->getCollection()
            ->addFieldToFilter('id', array('eq' => $wishlistId));
        if (!empty($wish->getData())) {
            try {
                foreach ($wish as $coll) {
                    $this->load($coll->getId())->delete();
                }
            } catch (Exception $e) {
                return $e;
            }
        }
        return true;
    }

    /**
     * @param $customerId
     * @return bool|false|Mage_Core_Model_Resource_Db_Collection_Abstract
     * @throws Mage_Core_Exception
     */
    public function getAllWishes($customerId)
    {
        $wish = $this->getCollection()
            ->addFieldToFilter('customer_id', array('eq' => $customerId));
        if (!empty($wish)) {
            return $wish;
        }
        return false;
    }

    /**
     * @param $customerId
     * @param $itemSubgroup
     * @param int $itemGroup
     * @return bool|false|Mage_Core_Model_Resource_Db_Collection_Abstract
     * @throws Mage_Core_Exception
     */
    public function getWishesGroup($customerId, $itemSubgroup, $itemGroup = 0)
    {
        if (is_numeric($itemGroup)) {
            $wish = $this->getCollection()
                ->addFieldToFilter('customer_id', array('eq' => $customerId))
                ->addFieldToFilter('item_group', array('neq' => 'product'))
                ->addFieldToFilter('item_subgroup', array('eq' => $itemSubgroup));
        } else {
            $wish = $this->getCollection()
                ->addFieldToFilter('customer_id', array('eq' => $customerId))
                ->addFieldToFilter('item_group', array('eq' => 'product'))
                ->addFieldToFilter('item_subgroup', array('eq' => $itemSubgroup));
        }

        if (!empty($wish)) {
            return $wish;
        }
        return false;
    }
}
