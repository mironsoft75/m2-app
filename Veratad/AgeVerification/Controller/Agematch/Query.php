<?php

        namespace Veratad\AgeVerification\Controller\Agematch;

        use Magento\Framework\App\Action\Context;
        use Magento\Framework\View\Result\PageFactory;
        use Magento\Framework\Controller\Result\JsonFactory;

        class Query extends \Magento\Framework\App\Action\Action
        {


            protected $helper;
            protected $_veratadHistory;
            protected $orderRepository;


            public function __construct(
                Context $context,
                PageFactory $resultPageFactory,
                JsonFactory $resultJsonFactory,
                \Veratad\AgeVerification\Helper\Data $helper,
                \Veratad\AgeVerification\Model\HistoryFactory $history,
                \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
                )
            {

                $this->resultPageFactory = $resultPageFactory;
                $this->resultJsonFactory = $resultJsonFactory;
                $this->helper = $helper;
                $this->_veratadHistory = $history;
                $this->orderRepository = $orderRepository;
                return parent::__construct($context);
            }


            public function execute()
            {

              $fn = $this->getRequest()->getParam('fn');
              $ln = $this->getRequest()->getParam('ln');
              $addr = $this->getRequest()->getParam('addr');
              $city = $this->getRequest()->getParam('city');
              $region = $this->getRequest()->getParam('region');
              $zip = $this->getRequest()->getParam('zip');
              $dob = $this->getRequest()->getParam('dob');
              $ssn = $this->getRequest()->getParam('ssn');
              $phone = $this->getRequest()->getParam('phone');
              $email = $this->getRequest()->getParam('email');
              $order_id = $this->getRequest()->getParam('order_id');
              $customer_id = null;
              $address_type = "billing";

              $order = $this->orderRepository->get($order_id);

              $target = array(
                "firstname" => $fn,
                "lastname" => $ln,
                "street" => $addr,
                "city" => $city,
                "region" => $region,
                "postcode" => $zip,
                "dob" => $dob,
                "ssn" => $ssn,
                "telephone" => $phone,
                "email" => $email
              );

              $isVerified = $this->helper->veratadPost($target, $order_id, $customer_id, $address_type);
              if($isVerified){
                $return = array(
                  "action" => "PASS"
                );
                $order->setVeratadAction("PASS");
                $order->save();
              }else{
                $return = array(
                  "action" => "FAIL"
                );
                $order->setVeratadAction("FAIL");
                $order->save();
              }
              $json_result = $this->resultJsonFactory->create();
              return $json_result->setData($return);
            }
      }
