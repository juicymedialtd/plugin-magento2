<?php
namespace Trustpilot\Reviews\Block\System\Config;

use Trustpilot\Reviews\Helper\Data;
use Trustpilot\Reviews\Helper\PastOrders;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Admin extends Field
{
    protected $_helper;
    protected $_pastOrders;
    protected $_integrationAppUrl;
    protected $_template = 'system/config/admin.phtml';

    public function __construct(
        Context $context,
        Data $helper,
        PastOrders $pastOrders,
        array $data = [])
    {
        $this->_helper = $helper;
        $this->_pastOrders = $pastOrders;
        $this->_integrationAppUrl = \Trustpilot\Reviews\Model\Config::TRUSTPILOT_INTEGRATION_APP_URL;
        parent::__construct($context, $data);
    }

    public function getIntegrationAppUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https:" : "http:";
        $domainName = $protocol . $this->_integrationAppUrl;
        return $domainName;
    }

    public function getSettings() {
        return base64_encode($this->_helper->getConfig('master_settings_field'));
    }

    public function getPageUrls() {
        return base64_encode(json_encode($this->_helper->getPageUrls()));
    }

    public function getCustomTrustBoxes()
    {
        $customTrustboxes = $this->_helper->getConfig('custom_trustboxes');
        if ($customTrustboxes) {
            return $customTrustboxes;
        }
        return "{}";
    }

    public function getProductIdentificationOptions() {
        return $this->_helper->getProductIdentificationOptions();
    }

    public function getPastOrdersInfo() {
        $storeId = $this->_helper->getWebsiteOrStoreId();
        $info = $this->_pastOrders->getPastOrdersInfo($storeId);
        $info['basis'] = 'plugin';
        return json_encode($info);
    }
    
    public function getSku()
    {
        return $this->_helper->getFirstProduct()->getSku();
    }

    public function getProductName()
    {
        return $this->_helper->getFirstProduct()->getName();
    }

    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    public function getVersion()
    {
        return $this->_helper->getVersion();
    }
}