<?php


        namespace Veratad\AgeVerification\Model;

        use Magento\Framework\Model\AbstractModel;

        class VeratadAccount extends AbstractModel
        {
        /**
        * Define resource model
        */
        protected function _construct()
        {
        $this->_init('Veratad\AgeVerification\Model\ResourceModel\VeratadAccount');
      }
    }
