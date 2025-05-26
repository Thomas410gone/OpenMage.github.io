---
layout: post
title: Weight-Based Free Shipping Example
category: Tutorial
thumbnail: 
author: OpenMage Team
permalink: /:year/:month/:day/:title:output_ext
lang: en
show_in_blog_page_last_posts_section: yes
---

This tutorial explains how to create a custom shipping method in OpenMage that grants free shipping when the order total exceeds **100&nbsp;€** and the total cart weight does not exceed **10&nbsp;kg**.

## 1. Create a custom shipping module

1. Create a new module with the structure:
   `app/code/local/Gone/Shipping`
2. Define `config.xml` to register the carrier model and system settings.
3. Provide a carrier model extending `Mage_Shipping_Model_Carrier_Abstract`.

## 2. Implement the carrier logic

In the carrier model's `collectRates` method (see `app/code/local/Gone/Shipping` in this repository for a full example):

```php
public function collectRates(Mage_Shipping_Model_Rate_Request $request)
{
    if (!$this->getConfigData('active')) {
        return false;
    }

    $result = Mage::getModel('shipping/rate_result');

    $orderTotal = $request->getPackageValueWithDiscount();
    $packageWeight = $request->getPackageWeight();

    if ($orderTotal >= 100 && $packageWeight <= 10) {
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
```

## 3. Enable the shipping method

After installing the module, enable it from **System → Configuration → Shipping Methods**. There you can configure the free shipping threshold and the maximum allowed cart weight.

This simple example shows how to tailor shipping logic to business needs. You can extend it further with more complex conditions or additional shipping options.
