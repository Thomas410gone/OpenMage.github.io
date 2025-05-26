<?php
class Gone_Shipping_Model_Carrier_Gone
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'gone';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $result = Mage::getModel('shipping/rate_result');

        $orderTotal = $request->getPackageValueWithDiscount();
        $packageWeight = $request->getPackageWeight();

        $threshold = (float)$this->getConfigData('free_threshold');
        $weightLimit = (float)$this->getConfigData('weight_limit');

        if ($orderTotal >= $threshold && $packageWeight <= $weightLimit) {
            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod('standard');
            $method->setMethodTitle($this->getConfigData('name'));
            $method->setPrice(0);
            $method->setCost(0);
            $result->append($method);
        }

        return $result;
    }

    public function getAllowedMethods()
    {
        return array('standard' => $this->getConfigData('name'));
    }
}

