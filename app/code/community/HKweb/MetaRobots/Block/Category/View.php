<?php
/**
 * HKweb
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    HKweb
 * @package     HKweb_MetaRobots
 * @copyright   Copyright (c) 2011 HKweb Internet. (http://www.HKweb.nl)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Category View block
 *
 * @category   HKweb
 * @package    HKweb_MetaRobots
 * @author     Magento Core Team <core@magentocommerce.com>
 * @editor     HKweb <support@HKweb.nl>
 */
class HKweb_MetaRobots_Block_Category_View extends Mage_Catalog_Block_Category_View
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->getLayout()->createBlock('catalog/breadcrumbs');

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $category = $this->getCurrentCategory();
            if ($title = $category->getMetaTitle()) {
                $headBlock->setTitle($title);
            }
            
            /**
             * start modification HKweb
             * changing value meta tag robots on category pages 2 and further
             */
            $url = Mage::helper('core/url')->getCurrentUrl();
			$parsedUrl = parse_url($url);
			if(isset($parsedUrl["query"]) AND (preg_match("/p=/i", $parsedUrl["query"])) AND (!preg_match("/p=1/i", $parsedUrl["query"]))){
				$headBlock->setRobots('NOINDEX,FOLLOW');
			}			
			
			// end modification HKweb Internet
			
            if ($description = $category->getMetaDescription()) {
                $headBlock->setDescription($description);
            }
            if ($keywords = $category->getMetaKeywords()) {
                $headBlock->setKeywords($keywords);
            }
            if ($this->helper('catalog/category')->canUseCanonicalTag()) {
                $headBlock->addLinkRel('canonical', $category->getUrl());
            }
            
            /*
            want to show rss feed in the url
            */
            if ($this->IsRssCatalogEnable() && $this->IsTopCategory()) {
                $title = $this->helper('rss')->__('%s RSS Feed',$this->getCurrentCategory()->getName());
                $headBlock->addItem('rss', $this->getRssLink(), 'title="'.$title.'"');
            }
        }

        return $this;
    }
}
