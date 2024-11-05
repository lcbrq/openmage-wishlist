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
        $itemSubgroup = $this->getRequest()->getParam('item_subgroup');

        $item = Mage::getModel('lcb_wishlist/wishlist')->getCollection()
                ->addFieldToFilter('customer_id', $customerId)
                ->addFieldToFilter('item_id', $itemId)
                ->addFieldToFilter('item_group', $itemType)
                ->addFieldToFilter('item_subgroup', $itemSubgroup)
                ->getFirstItem();

        if (!$item->getId()) {
            $item->setData([
                'customer_id' => $customerId,
                'item_id' => $itemId,
                'item_name' => $itemName,
                'item_group' =>$itemType,
                'item_subgroup' =>$itemSubgroup,
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
                ->addFieldToFilter('item_group', $this->getRequest()->getParam('item_type'))
                ->addFieldToFilter('item_subgroup', $this->getRequest()->getParam('item_subgroup'))
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
        $itemSubgroup = $this->getRequest()->getParam('item_subgroup');
        $itemGroup = $this->getRequest()->getParam('item_type');

        $item = Mage::getModel('lcb_wishlist/wishlist')->getCollection()
                ->addFieldToFilter('customer_id', $customerId)
                ->addFieldToFilter('item_id', $itemId)
                ->addFieldToFilter('item_group', $itemGroup)
                ->addFieldToFilter('item_subgroup', $itemSubgroup)
                ->getFirstItem()
                ->delete();

        $response = array(
            'status' => 'success',
            'action' => 'removed',
        );

        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }
}
