<?php
/* 
 * This file is part of the isicsSitemapXMLPlugin package.
 * 
 * Copyright (C) 2007-2009 ISICS.fr <contact@isics.fr>
 * 
 * isicsSitemapXMLPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * isicsSitemapXMLPlugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with isicsSitemapXMLPlugin.  If not, see <http://www.gnu.org/licenses/>.
 */

class BaseisicsSitemapXMLActions extends sfActions
{
  
  /**
   * Define this function to add default urls
   *
   */
  protected function addDefaultUrls()
  {
  }
  
  /**
   * Display sitemap XML (see: www.sitemap.org)
   *
   */
  public function executeIndex()
  {
    $this->urls = array();
    
    // Adding defaults urls
    foreach (sfConfig::get('app_isicsSitemapXML_default_urls', array()) as $url)
    {
      $lastmod    = array_key_exists('lastmod', $url) ? $url['lastmod'] : null;
      $changefreq = array_key_exists('changefreq', $url) ? $url['changefreq'] : null;
      $priority   = array_key_exists('priority', $url) ? $url['priority'] : null;
      
      $this->urls[] = new isicsSitemapURL($url['loc'], $lastmod, $changefreq, $priority);
    }
    $this->addDefaultUrls();    

    // Adding other urls
    $dispatcher = sfContext::getInstance()->getEventDispatcher();
    $this->urls = $dispatcher->filter(new sfEvent($this, 'isicsSitemapXML.filter_urls'), $this->urls)->getReturnValue();
  }
  
}