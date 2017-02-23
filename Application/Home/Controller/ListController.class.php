<?php
	namespace Home\Controller;
	use Home\Controller\CommonController;
	// use Think\Controller;
	use Think\Page;  
	
	Class ListController extends CommonController{
		Public function index(){
			import('ORG.Util.Page');
			import('Class.Category', APP_PATH);
			$Category = new \Category();
			
			$id = (int)$_GET['id'];
			$cate = M('cate')->order('sort')->select();
			
			$cids = $Category::getChildsId($cate, $id);
			$cids[] = $id;
			
			$where = array('cid' => array('IN', $cids), 'del' => 0);
			$count = M('blog')->where($where)->count();
			//echo $count;
			$page = new Page($count, 10);
			$limit = $page->firstRow . ',' . $page->listRows;
			//p($limit);
			//$this->blog = D('BlogView')->where(array('cid' => array('IN', $cids), 'del' => 0))->limit($limit)->select();
			$this->blog = D('BlogView')->getAll($where, $limit);
			$this->page = $page->show();
			$this->display();
			
		}

		Public function myblog(){
			import('ORG.Util.Page');
			import('Class.Category', APP_PATH);
			$Category = new \Category();
			
			$id = (int)$_GET['id'];
			$cate = M('cate')->order('sort')->select();
			
			$cids = $Category::getChildsId($cate, $id);
			$cids[] = $id;
			
			$where = array('cid' => array('IN', $cids), 'del' => 0);
			$count = M('blog')->where($where)->count();
			//echo $count;
			$page = new Page($count, 10);
			$limit = $page->firstRow . ',' . $page->listRows;
			//p($limit);
			//$this->blog = D('BlogView')->where(array('cid' => array('IN', $cids), 'del' => 0))->limit($limit)->select();
			
			$blog = D('BlogView')->getAll($where, $limit);
			foreach ($blog as $b => $v) {
				$blog[$b]['cover_img'] || $blog[$b]['cover_img']="./Public/myblog_files/201605241464093846615542.jpg";
				$comment = M('comment')->field("id")->where("bid={$v['id']}")->select();
				$blog[$b]['comment'] = count($comment)+1;
			}
			$this->blog = $blog;

			import('Class.Category', APP_PATH);
			$Category = new \Category();
			$cate = M('cate')->order('sort')->select();
			$this->parent = $Category::getParents($cate, $id);

			$this->page = $page->show();
			$this->display();
			
		}
	}
?>