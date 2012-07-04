<?php
/**
 * Dizkus
 *
 * @copyright (c) 2001-now, Dizkus Development Team
 * @link https://github.com/zikula-modules/Dizkus
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Dizkus
 */

class Dizkus_Controller_Admin extends Zikula_AbstractController
{

    public function postInitialize()
    {
        $this->view->setCaching(false)->add_core_data();
    }
    /**
     * the main administration function
     *
     */
    public function main()
    {
        if (!SecurityUtil::checkPermission('Dizkus::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        
        return $this->view->fetch('admin/main.tpl');
    }
    
    /**
     * preferences
     *
     */
    public function preferences()
    {
        if (!SecurityUtil::checkPermission('Dizkus::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
    
        // Create output object
        $form = FormUtil::newForm('Dizkus', $this);
    
        // Return the output that has been generated by this function
        return $form->execute('admin/preferences.tpl', new Dizkus_Form_Handler_Admin_Prefs());
    }
    
    /**
     * syncforums
     */
    public function syncforums()
    {
        $showstatus = !($this->request->request->get('silent', 0));
        
        if (!SecurityUtil::checkPermission('Dizkus::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $succesful = ModUtil::apiFunc('Dizkus', 'admin', 'sync',
                     array('type' => 'all forums'));
        if ($showstatus && $succesful) {
            LogUtil::registerStatus($this->__('Done! Synchronized forum index.') );
        } else {
            return LogUtil::registerError($this->__("Error synchronizing forum index"));
        }
    
        $succesful = ModUtil::apiFunc('Dizkus', 'admin', 'sync',
                     array('type' => 'all topics'));
        if ($showstatus && $succesful) {
            LogUtil::registerStatus($this->__('Done! Synchronized topics.') );
        } else {
            return LogUtil::registerError($this->__("Error synchronizing topics."));
        }
    
        $succesful = ModUtil::apiFunc('Dizkus', 'admin', 'sync',
                     array('type' => 'all posts'));
        if ($showstatus && $succesful) {
            LogUtil::registerStatus($this->__('Done! Synchronized posts counter.') );
        } else {
            return LogUtil::registerError($this->__("Error synchronizing posts counter."));
        }

        return System::redirect(ModUtil::url('Dizkus', 'admin', 'main'));
    }
    
    /**
     * ranks
     */
    public function ranks()
    {
    
        if (!SecurityUtil::checkPermission('Dizkus::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
    	
        $submit = $this->request->getPost()->filter('submit',2);
        $ranktype = $this->request->getGet()->filter('ranktype', 0, FILTER_SANITIZE_NUMBER_INT);
              
        if ($submit == 2) {
            list($rankimages, $ranks) = ModUtil::apiFunc($this->name, 'admin', 'readranks',
                                                      array('ranktype' => $ranktype));
    
            $this->view->assign('ranks', $ranks);
            $this->view->assign('ranktype', $ranktype);
            $this->view->assign('rankimages', $rankimages);
    
            if ($ranktype == 0) {
                return $this->view->fetch('admin/ranks.tpl');
            } else {
                return $this->view->fetch('admin/honoraryranks.tpl');
            }
        } else {
        	$ranks = $this->request->getPost()->filter('ranks', '', FILTER_SANITIZE_STRING);
            //$ranks = FormUtil::getPassedValue('ranks');
            ModUtil::apiFunc($this->name, 'admin', 'saverank', array('ranks' => $ranks));
        }
    
        return System::redirect(ModUtil::url($this->name,'admin', 'ranks', array('ranktype' => $ranktype)));
    }
    
    /**
     * ranks
     */
    public function assignranks()
    {
        if (!SecurityUtil::checkPermission('Dizkus::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $submit     = $this->request->query->get('submit');
        $letter     = $this->request->query->get('letter');
        $lastletter = $this->request->query->get('lastletter');
        $page       = (int)$this->request->query->get('page', 1);

        // check for a letter parameter
        if (!empty($lastletter)) {
            $letter = $lastletter;
        }
    
        // count users and forbid '*' if more than 1000 users are present
        if (empty($letter) || strlen($letter) != 1) {
            $letter = 'a';
        }
        $letter = strtolower($letter);
    
        if (is_null($submit)) {
            list($rankimages, $ranks) = ModUtil::apiFunc('Dizkus', 'admin', 'readranks',
                                                     array('ranktype' => 1));
            $perpage = 20;
            
            /*$inlinecss = '<style type="text/css">' ."\n";
            $rankpath = ModUtil::getVar('Dizkus', 'url_ranks_images') .'/';
            foreach ($ranks as $rank) {
                $inlinecss .= '#dizkus_admin option[value='.$rank['rank_id'].']:before { content:url("'.System::getBaseUrl() . $rankpath . $rank['rank_image'].'"); }' . "\n";
            }
            $inlinecss .= '</style>' . "\n";
            PageUtil::addVar('rawtext', $inlinecss);*/
            
            $em = $this->getService('doctrine.entitymanager');
            $query = $em->createQueryBuilder();
            $query->select('u.uid, u.uname, a.value as rank_id')
                  ->from('Dizkus_Entity_Users', 'u')
                  ->leftJoin('u.attributes', 'a')
                  ->where("a.attribute_name = 'dizkus_user_rank'")
                  ->orderBy("u.uname");
            
            
            if (!empty($letter) and $letter != '*') {
                $query->andWhere("u.uname LIKE :letter")
                      ->setParameter('letter', DataUtil::formatForStore($letter).'%');
            }
            
            $query = $query->getQuery();
            
            
            // Paginator
            $startnum = ($page-1)*$perpage;
            $count = \DoctrineExtensions\Paginate\Paginate::getTotalQueryResults($query);
            $paginateQuery = \DoctrineExtensions\Paginate\Paginate::getPaginateQuery($query, $startnum, $perpage); // Step 2 and 3
            $allusers = $paginateQuery->getArrayResult();
            
                        
    
            $this->view->assign('ranks', $ranks);
            $this->view->assign('rankimages', $rankimages);
            $this->view->assign('allusers', $allusers);
            $this->view->assign('letter', $letter);
            $this->view->assign('page', $page);
            $this->view->assign('perpage', $perpage);
            $this->view->assign('usercount', $count);
    
            return $this->view->fetch('admin/assignranks.tpl');
    
        } else {
            // avoid some vars in the url of the pager
            unset($_GET['submit']);
            unset($_POST['submit']);
            unset($_REQUEST['submit']);
            $setrank = $this->request->request->get('setrank');
            ModUtil::apiFunc('Dizkus', 'admin', 'assignranksave', 
                         array('setrank' => $setrank));
        }
    
        return System::redirect(ModUtil::url('Dizkus','admin', 'assignranks',
                                   array('letter' => $letter,
                                         'page'   => $page)));
    }
    
    
    /** 
     * reordertree
     *
     */
    public function reordertree()
    {
        if (!SecurityUtil::checkPermission('Dizkus::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
    
        $categorytree = ModUtil::apiFunc('Dizkus', 'user', 'readcategorytree');

        $this->view->assign('categorytree', $categorytree);
        $this->view->assign('newcategory', false);
        $this->view->assign('newforum', false);
    
        return $this->view->fetch('admin/reordertree.tpl');
    }



    /**
     * tree
     *
     * Tree.
     *
     * @return string
     */
    public function tree()
    {
        if (!SecurityUtil::checkPermission('Dizkus::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        return $this->view->assign('tree', ModUtil::apiFunc($this->name, 'Forum', 'getTree'))
            ->fetch('admin/tree.tpl');

    }


    /**
     * changeCatagoryOrder
     *
     * @return string
     */
    public function changeCatagoryOrder() {

        $url = ModUtil::url($this->name, 'admin', 'tree');

        $id = $this->request->query->get('id', null);
        $action = $this->request->query->get('action');

        if (is_null($id) || is_null($action)) {
            LogUtil::registerArgsError();
            return $this->redirect($url);
        }

        $category = $this->entityManager->find('Dizkus_Entity_Categories', $id);
        $cat_order = $category->getcat_order();

        // get lower/higher category
        if ($action == 'increase') {
            $order = 'DESC';
            $operator = '<';
        } else {
            $order = 'ASC';
            $operator = '>';
        }
        $em = $this->getService('doctrine.entitymanager');
        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from('Dizkus_Entity_Categories', 'c')
            ->where('c.cat_order '.$operator.' :order')
            ->setParameter('order', $category->getcat_order())
            ->orderBy('c.cat_order', $order)
            ->setMaxResults(1);
        $category2 = $qb->getQuery()->getArrayResult();
        if ($category2) {
            $category2 = $category2[0];
        } else {
            return LogUtil::registerError($this->__('No higher category!'));
        }

        $category->setcat_order($category2['cat_order']);
        $higerCategory = $this->entityManager->find('Dizkus_Entity_Categories', $category2['cat_id']);
        $higerCategory->setcat_order($cat_order);

        $this->entityManager->flush();


        return $this->redirect($url);
    }



    /**
     * changeCatagoryOrder
     *
     * @return string
     */
    public function changeForumOrder() {

        $url = ModUtil::url($this->name, 'admin', 'tree');

        $id = $this->request->query->get('id', null);
        $action = $this->request->query->get('action');

        if (is_null($id) || is_null($action)) {
            LogUtil::registerArgsError();
            return $this->redirect($url);
        }

        $forum = $this->entityManager->find('Dizkus_Entity_Forums', $id);
        $forum_order = $forum->getforum_order();

        // get lower/higher forum
        if ($action == 'increase') {
            $order = 'DESC';
            $operator = '<';
        } else {
            $order = 'ASC';
            $operator = '>';
        }
        $em = $this->getService('doctrine.entitymanager');
        $qb = $em->createQueryBuilder();
        $qb->select('f')
            ->from('Dizkus_Entity_Forums', 'f')
            ->where('f.forum_order '.$operator.' :order')
            ->setParameter('order', $forum->getforum_order())
            ->andWhere('f.parent_id = :parentId')
            ->setParameter('parentId', $forum->getparent_id())
            ->orderBy('f.forum_order', $order)
            ->setMaxResults(1);
        $forum2 = $qb->getQuery()->getArrayResult();
        if ($forum2) {
            $forum2 = $forum2[0];
        } else {
            return LogUtil::registerError($this->__('No higher forum!'));
        }

        $forum->setforum_order($forum2['forum_order']);
        $higerForum = $this->entityManager->find('Dizkus_Entity_Forums', $forum2['forum_id']);
        $higerForum->setforum_order($forum_order);

        $this->entityManager->flush();


        return $this->redirect($url);
    }


    /**
     *
     */
    public function modifycategory()
    {
        $form = FormUtil::newForm('Dizkus', $this);
        return $form->execute('admin/modifycategory.tpl', new Dizkus_Form_Handler_Admin_ModifyCategory());
    }


    /**
     *
     */
    public function modifyforum()
    {
        $form = FormUtil::newForm('Dizkus', $this);
        return $form->execute('admin/modifyforum.tpl', new Dizkus_Form_Handler_Admin_ModifyForum());
    }
                    
    /**
     * managesubscriptions
     *
     */
    public function managesubscriptions()
    {   
        $form = FormUtil::newForm('Dizkus', $this);
        return $form->execute('admin/managesubscriptions.tpl', new Dizkus_Form_Handler_Admin_ManageSubscriptions());   
    }

}