<?php
	namespace Home\Controller;
	use Home\Controller\CommonController;
	// use Think\Controller;

	Class IndexController extends CommonController{
		
		Public function index(){
			if(!$list = S('index_list')){
				$topCate = M('cate')->where(array('pid' => 0))->field(array('id', 'name'))->order('sort')->select();

				//p($topCate);
				import('Class.Category', APP_PATH);
				$Category = new \Category();
				$cate = M('cate')->order('sort')->select();
				$db = M('blog');
				$field = array('id', 'title', 'cover_img', 'click', 'summary', 'time');

				foreach ($topCate as $k =>$v){
					//p($v['id']);
					$cids = $Category::getChildsId($cate, $v['id']);
					$cids[] = $v['id'];
					//p($cids);
					$where = array('cid' => array('IN', $cids), 'del' => 0);
					$blog = $db->field($field)->where($where)->order('time DESC')->select();
					foreach ($blog as $b => $v) {
						$blog[$b]['cover_img'] || $blog[$b]['cover_img']="./Public/myblog_files/201605241464093846615542.jpg";
						$comment = M('comment')->field("id")->where("bid={$v['id']}")->select();
						$blog[$b]['comment'] = count($comment)+1;
					}
					$topCate[$k]['blog'] = $blog;
				}

				S('index_list', $topCate, 10);
			}
			$topCate = S('index_list');
			
			// p($topCate);
			$this->topCate = $topCate;
			$this->display();
		}

		Public function myblog(){
			if(!$list = S('index_list')){
				$topCate = M('cate')->where(array('pid' => 0))->field(array('id', 'name'))->order('sort')->select();

				//p($topCate);
				import('Class.Category', APP_PATH);
				$Category = new \Category();
				$cate = M('cate')->order('sort')->select();
				$db = M('blog');
				$field = array('id', 'title', 'time', 'summary','click');

				foreach ($topCate as $k =>$v){
					//p($v['id']);
					$cids = $Category::getChildsId($cate, $v['id']);
					$cids[] = $v['id'];
					//p($cids);
					$where = array('cid' => array('IN', $cids), 'del' => 0);
					$topCate[$k]['blog'] = $db->field($field)->where($where)->order('time DESC')->select();
				}

				S('index_list', $topCate, 10);
			}
			$topCate = S('index_list');
			
			//p($topCate);
			$this->topCate = $topCate;
			// print_r($topCate);
			$this->display();
		}

	}
?>