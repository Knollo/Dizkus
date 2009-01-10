<?php
// $Id$
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

class Dizkus_user_ignorelistmanagementHandler
{
    function initialize(&$render)
    {   
	  	// prepare list    
	  	$ignorelist_handling = pnModGetVar('Dizkus','ignorelist_handling');
	  	$ignorelist_options = array();
	  	switch ($ignorelist_handling) {
		    case 'strict':
		    	$ignorelist_options[] = array('text' => _DZK_PREFS_STRICT, 'value' => 'strict');
		    case 'medium':
		    	$ignorelist_options[] = array('text' => _DZK_PREFS_MEDIUM, 'value' => 'medium');
		    default:
		    	$ignorelist_options[] = array('text' => _DZK_PREFS_NONE, 'value' => 'none');
		}
        // get user's configuration
		$attr = pnUserGetVar('__ATTRIBUTES__');
		if ($attr['dzk_ignorelist_myhandling'] == '') $ignorelist_myhandling = $ignorelist_handling;
		else $ignorelist_myhandling = $attr['dzk_ignorelist_myhandling'];
        $render->caching = false;
        $render->add_core_data('PNConfig');
        // assign data
        $render->assign('ignorelist_options',    $ignorelist_options);
        $render->assign('ignorelist_myhandling', $ignorelist_myhandling);
        return true;
    }
    function handleCommand(&$render, &$args)
    {
        if ($args['commandName']=='update') {
            // Security check 
            if (!SecurityUtil::checkPermission('Dizkus::', '::', ACCESS_COMMENT)) return LogUtil::registerPermissionError();

            // get the pnForm data and do a validation check
            $obj = $render->pnFormGetValues();          
            if (!$render->pnFormIsValid()) return false;

			// update user's attributes
		    $obj['uid'] = pnUserGetVar('uid');
		   
		    // Get parameters from environment 
		    // ic_note: email notifiaction yes/no
		    // ic_ar  : autoreply yes/no
		    // ic_art  : autoreply text
		    $obj['__ATTRIBUTES__']['dzk_ignorelist_myhandling'] = $obj['ignorelist_myhandling']; 
		
		    // store attributes 
		    DBUtil::updateObject($obj, 'users', '', 'uid');

            LogUtil::registerStatus(_DZK_IGNORELISTSETTINGSUPDATED);
            
            return $render->pnFormRedirect(pnModURL('Dizkus','user','prefs'));
        }
        return true;
    }
}