<?php
	namespace Home\Controller;
	use Home\Controller\CommonController;
	// use Think\Controller;
	
	Class ShowController extends CommonController{
		Public function index(){
			$id = (int)$_GET['id'];
			M('blog')->where(array('id' => $id))->setInc('click');
			$field = array('id','title', 'time', 'click', 'content', 'cid');
			$this->blog = M('blog')->field($field)->find($id);
			
			$cid = $this->blog['cid'];
			import('Class.Category', APP_PATH);
			$Category = new \Category();

			$cate = M('cate')->order('sort')->select();
			$this->parent = $Category::getParents($cate, $cid);
			//p($parent);
			$this->assign(array(
                    'inc_ueditor' => true,
                ));
                    
			$this->display();
		}

		Public function myblog(){
			$id = (int)$_GET['id'];
			M('blog')->where(array('id' => $id))->setInc('click');
			$field = array('id','title', 'time', 'click', 'comment_count', 'content', 'cid');
			$list = M('blog')->field($field)->find($id);
			$list['comment_count'] += 1;
			$this->blog = $list;
			// $comment_count = M('comment')->field("id")->where("bid={$id}")->select();
			// $this->comment_count = count($comment_count)+1;

			$map = array();
			$map['bid'] = $id;
			$map['status'] = 1;
			$comment = M('comment')->where($map)->order("id DESC")->select();
			foreach ($comment as $k => $v) {
				global $temp_arr;
				$temp_arr = array($v);
				//这里去查找当前评论下的所有引用的评论，并格式化为html字符串
				if ($v['pid']!=0) {
		         	$this->addCommentNode($comment, $v);
				}
				$temp[] = $temp_arr;
			}
			
			foreach ($temp as $k => $v) {
				$temp_str='';
				foreach ($v as $k2 => $v2) {
					$str = $k2!=count($v)-1 ? "<ul class='children'>" : "";
					$str.="<label id='cmt{$k}_{$v2['id']}'></label><li class='msgname' id='cmt-{$k}_{$v2['id']}'> <div class='msgarticle'> <div class='avatar'> <img src='./Public/myblog_files/f719871e81e868a52f8cb1fe0b9c28c6.png'> </div> <div class='comment-body'> <div class='comment-header'> <a href='http://www.webexp.cn' rel='nofollow' target='_blank'>{$v2['username']}</a> </div> <p>{$v2['content']}<label id='AjaxComment603'></label></p> <div class='comment-footer'> <span class='comment-time'>{$v2['time']}</span> <a class='post-reply' href='#comment' onclick=\"RevertComment('{$k}_{$v2['id']}')\" myid='$v2[id]'>回复该评论</a> </div> </div> </div> </li>";
					$str.=$temp_str;
					$k2!=count($v)-1 && $str.="</ul>";
					$temp_str = $str;
				}
				$temp_str = $str;
				$temp[$k]=$temp_str;
			}
			
			// p($temp);
			$this->comment = $temp;
			
			$cid = $this->blog['cid'];
			import('Class.Category', APP_PATH);
			$Category = new \Category();

			$cate = M('cate')->order('sort')->select();
			$this->parent = $Category::getParents($cate, $cid);
			//p($parent);
			$this->assign(array(
                    'inc_ueditor' => true,
                ));
                    
			$this->display();
		}

		//添加评论
		Public function addComment(){
			$params = I('post.','','urldecode');
			// p($params);
			// p(intval($params['inpId']));
			// die();
			
			$data = array(
				'username' => $params['inpName'],
				'mail' => $params['inpEmail'],
				'site' => $params['inpHomePage'],
				'content' => htmlspecialchars($params['txaArticle']),  //防止script注入
				'time' => date("Y-m-d H:i:s"),
				'pid' => intval($params['inpId']),
				'bid' => $params['bid'],
			);
			if(M('comment')->add($data)){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}
		

		/*
			list代表某一文章下的全部评论列表
			cmt代表当前要显示的评论
		 */
		function addCommentNode($list, $cmt){
			if(isset($cmt['pid']) && $cmt['pid'] != '0'){
				$find = $this->findParentCmt($list, $cmt['pid']);//找寻id等于当前评论的pid的评论，返回数组。
				// 递归调用，只要pid不为零，就加入到引用评论列表
				$this->addCommentNode($list, $find);
			}else{
				return;
			}

		}

		/**
		 * 查找list中找寻id等于pid的数组项，并返回
		 * @param  [type] $list  [description]
		 * @param  [type] $cmtpid [description]
		 * @return [type]        [description]
		 */
		function findParentCmt($list, $cmtpid){
			foreach ($list as $key => $value) {
				if($value['id'] == $cmtpid){
					/* 用数组的方式来保存所有引用的评论 */
					global $temp_arr;
					$temp_arr[] = $list[$key];
					//p($list[$key]);echo "<hr>";
					return $list[$key];
				}
			}
			return false;
		}
	}
?>