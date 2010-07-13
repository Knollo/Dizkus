<?php
/**
 * Dizkus
 *
 * @copyright (c) 2001-now, Dizkus Development Team
 * @link http://code.zikula.org/dizkus
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Dizkus
 */

class Dizkus_Block_Center extends Zikula_Block
{
	/**
	 * init
	 */
	public function init()
	{
	    SecurityUtil::registerPermissionSchema('Dizkus_Centerblock::', 'Block ID::');
	}
	
	/**
	 * info
	 */
	public function info()
	{
	    return array('module'         => 'Dizkus',
	                 'text_type'      => $this->__('Dizkus recent'),
	                 'text_type_long' => $this->__('Dizkus recent posts'),
	                 'allow_multiple' => true,
	                 'form_content'   => false,
	                 'form_refresh'   => false,
	                 'show_preview'   => true);
	}
	
	/**
	 * display the center block
	 */
	public function display($blockinfo)
	{
	    if (!ModUtil::available('Dizkus')) {
	        return;
	    }
	
	    // check for Permission
	    if (!SecurityUtil::checkPermission('Dizkus_Centerblock::', "$blockinfo[bid]::", ACCESS_READ)){
	        return;
	    }
	
	    // check if forum is turned off
	    $disabled = dzk_available();
	    if (!is_bool($disabled)) {
	        $blockinfo['content'] = $disabled;
	        return BlockUtil::themesideblock($blockinfo);
	    }
	
	    // return immediately if no post exist
	    if (ModUtil::apiFunc('Dizkus', 'user', 'boardstats', array('type' => 'all')) == 0) {
	        return;
	    }
	
	    // Break out options from our content field
	    $vars = BlockUtil::varsFromContent($blockinfo['content']);
	
	    $render = Zikula_View::getInstance('Dizkus', false, null, true);
	
	    // check if cb_template is set, if not, use the default centerblock template
	    if (empty($vars['cb_template'])) {
	        $vars['cb_template'] = 'dizkus_centerblock_display.html';
	    }
	    if (empty($vars['cb_parameters'])) {
	        $vars['cb_parameters'] = 'maxposts=5';
	    }
	    $params = explode(',', $vars['cb_parameters']);
	
	    if (is_array($params) &&(count($params) > 0)) {
	        foreach($params as $param)
	        {
	            $paramdata = explode('=', $param);
	            $render->assign(trim($paramdata[0]), trim($paramdata[1]));
	        }
	    }
	
	    $blockinfo['content'] = $render->fetch(trim($vars['cb_template']));
	
	    return BlockUtil::themesideblock($blockinfo);
	}
	
	/**
	 * Update the block
	 */
	public function update($blockinfo)
	{
	    if (!SecurityUtil::checkPermission('Dizkus_Centerblock::', "$blockinfo[bid]::", ACCESS_ADMIN)) {
	        return false;
	    }
	    
	    $cb_template   = FormUtil::getPassedValue('cb_template', 'dizkus_centerblock_display.html', 'POST');
	    $cb_parameters = FormUtil::getPassedValue('cb_parameters', 'maxposts=5', 'POST');
	
	    $blockinfo['content'] = BlockUtil::varsToContent(compact('cb_template', 'cb_parameters'));
	
	    return($blockinfo);
	}
	
	/**
	 * Modify the block
	 */
	public function modify($blockinfo)
	{
	    if (!SecurityUtil::checkPermission('Dizkus_Centerblock::', "$blockinfo[bid]::", ACCESS_ADMIN)) {
	        return false;
	    }
	    
	    // Break out options from our content field
	    $vars = BlockUtil::varsFromContent($blockinfo['content']);
	
	    if (!isset($vars['cb_parameters']) || empty($vars['cb_parameters'])) {
	        $vars['cb_parameters'] = 'maxposts=5';
	    }
	    if (!isset($vars['cb_template']) || empty($vars['cb_template'])) {
	        $vars['cb_template']   = 'dizkus_centerblock_display.html';
	    }
	
	    $render = Zikula_View::getInstance('Dizkus', false, null, true);
	    $render->assign('vars', $vars);
	
	    return $render->fetch('dizkus_centerblock_config.html');
	}
}