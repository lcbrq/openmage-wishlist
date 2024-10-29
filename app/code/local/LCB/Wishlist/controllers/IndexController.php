<?php

class LCB_Wishlist_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function addwishAction()
    {
        $data = $this->getRequest()->getParams();
        $added = Mage::getModel('lcb_wishlist/wishlist')->addToWishlist($data);
        $response = array(
            'status' => $added ? 'success' : 'error',
            'action' => $added ? 'added' : 'failed'
        );
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    public function removewishAction()
    {
        $data = $this->getRequest()->getParams();
        $deleted = Mage::getModel('lcb_wishlist/wishlist')->removeFromWishList($data);
        $response = array(
            'status' => $deleted ? 'success' : 'error',
            'action' => $deleted ? 'removed' : 'failed'
        );
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    public function removeWishByIdAction()
    {
        $id = $this->getRequest()->getParam('item');
        $deleted = Mage::getModel('lcb_wishlist/wishlist')->removeFromWishListById($id);
        if ($deleted) {
            echo $this->__('Item %s has been removed from your wishlist.', $id);
        }
    }
}
