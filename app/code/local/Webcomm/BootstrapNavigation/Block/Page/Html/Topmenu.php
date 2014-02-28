<?php

class Webcomm_BootstrapNavigation_Block_Page_Html_Topmenu extends JR_CleverCms_Block_Page_Html_Topmenu
{
    /**
     * {@inheritDoc}
     */
    public function getHtml($outermostClass = '', $childrenWrapClass = '')
    {
        // MERCATOR - Modified to work alongside JR_CleverCms
        $categoriesIsFirst = !$this->showHomepageLink();
        $categoriesIsLast = !$this->hasCmsNavigationMenuPages();
        $cmsIsFirst = !$this->hasCategoriesNavigationMenuPages();

        $html = $this->renderHomepageLinkHtml(0, false, true, 'level-top', '', $this->__("Home"));
        $html.= $this->renderCategoriesMenuHtmlClever(0, $categoriesIsLast, $categoriesIsFirst, 'level-top');
        $html.= $this->renderCmsMenuHtml(0, true, $cmsIsFirst, 'level-top');
        $html = str_replace("\n", '', $html);

        if (Mage::getStoreConfig('catalog/navigation/top_in_dropdown')) {
            $html = $this->_addDropdownLink($html);
        }

        $html = $this->_addToggle($html);
        $html = $this->_addCaret($html);
        $html = $this->_addDropdown($html);

        return $html;
    }

    /**
     * {@inheritDoc}
     */
    protected function _getMenuItemClasses(Varien_Data_Tree_Node $item)
    {
        return parent::_getMenuItemClasses($item);
    }

    /**
     * Takes a html string and appends a modified top level link inside the dropdown.
     *
     * @param  string  $html
     * @return string
     */
    protected function _addDropdownLink($html)
    {
        return preg_replace_callback('/<li\s+class="level0.*?<a\s+href="(.*?)".*?>.*?<span>(.*?)<\/span>.*?<ul\s+class="level0.*?>/', array($this, '_addDropdownLinkCallback'), $html);
    }

    /**
     * Callback executed to add a modified top level link inside the dropdown. We're
     * doing this just to ensure we're supporting PHP 5.2 as Magento does.
     *
     * @param  array  $matches
     * @return string
     */
    protected function _addDropdownLinkCallback(array $matches)
    {
        $prefix = Mage::getStoreConfig('catalog/navigation/top_in_dropdown_prefix');
        $suffix = Mage::getStoreConfig('catalog/navigation/top_in_dropdown_suffix');

        $html = '<li class="level1 level-top-in-dropdown"><a href="'.$matches[1].'"><span>'.$prefix.' '.$matches[2].' '.$suffix.'</span></a>';
        $html .= '<li class="divider"></li>';

        return $matches[0].$html;
    }

    /**
     * Adds a dropdown HTML5 attribute to top level links.
     *
     * @param  string  $html
     * @return string
     */
    protected function _addToggle($html)
    {
        return preg_replace('/(<li\s+class="level0.*?<a.*?)(>)/', '$1 data-toggle="dropdown"$2', $html);
    }

    /**
     * Adds a caret HTML symbol to top level links.
     *
     * @param  string  $html
     */
    protected function _addCaret($html)
    {
        return preg_replace('/(<li\s+class="level0.*?)(<\/a>)/', '$1 <b class="caret"></b> $2', $html);
    }

    /**
     * Adds a CSS class to top level dropdowns.
     *
     * @param  string  $html
     * @return string
     */
    protected function _addDropdown($html)
    {
        return preg_replace('/<ul\s+class="level0/', '$0 dropdown-menu', $html);
    }
}
