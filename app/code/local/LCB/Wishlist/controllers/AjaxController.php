<?php

class LCB_Wishlist_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * @return Mage_Core_Controller_Response_Http
     */
    public function addAction()
    {
        $customerId = Mage::getSingleton('customer/session')->getId();
        $itemId = $this->getRequest()->getParam('item_id');
        $itemType = $this->getRequest()->getParam('item_type');
        $itemName = $this->getRequest()->getParam('item_name');

        $item = Mage::getModel('lcb_wishlist/wishlist')->getCollection()
                ->addFieldToFilter('customer_id', $customerId)
                ->addFieldToFilter('item_id', $itemId)
                ->getFirstItem();

        if (!$item->getId()) {
            $item->setData([
                'customer_id' => $customerId,
                'item_id' => $itemId,
                'item_name' => $itemName,
                'item_group' =>$itemType,
            ])->save();
        }

        $response = array(
            'status' => $item->getId() ? 'success' : 'error',
            'action' => $item->getId() ? 'added' : 'failed'
        );

        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * @return Mage_Core_Controller_Response_Http
     */
    public function getAction()
    {
        $addedItemsIds = Mage::getModel('lcb_wishlist/wishlist')->getCollection()
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getId())
                ->addFieldToFilter('item_group', $this->getRequest()->getParam('type'))
                ->getColumnValues('item_id');

        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($addedItemsIds));
    }

    /**
     * @return Mage_Core_Controller_Response_Http
     */
    public function removeAction()
    {
        $customerId = Mage::getSingleton('customer/session')->getId();
        $itemId = $this->getRequest()->getParam('item_id');

        $item = Mage::getModel('lcb_wishlist/wishlist')->getCollection()
                ->addFieldToFilter('customer_id', $customerId)
                ->addFieldToFilter('item_id', $itemId)
                ->getFirstItem()
                ->delete();

        $response = array(
            'status' => 'success',
            'action' => 'removed',
        );

        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }
}
