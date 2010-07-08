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

/**
 * Renderer plugin
 * 
 * This file is a plugin for Renderer, the Zikula implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Renderer
 * @version      $Id$
 * @author       The Zikula development team
 * @link         http://www.zikula.org  The Zikula Home Page
 * @copyright    Copyright (C) 2002 by the Zikula Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty modifier to apply the bbsmile transform hooks
 * 
 * Available parameters:

 * Example
 * 
 *   {$MyVar|dzkbbsmile}
 * 
 * 
 * @author       Frank Schummertz
 * @author       The Dizkus team
 * @since        16. Sept. 2003
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_dzkbbsmile($string)
{
	$extrainfo = array($string);

    if (ModUtil::available('bbsmile')) {
        list($string) = ModUtil::apiFunc('bbsmile', 'user', 'transform', array('objectid' => '', 'extrainfo' => $extrainfo));
    }

    return $string;                      
}
