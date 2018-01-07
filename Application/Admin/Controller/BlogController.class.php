<?php
	namespace Admin\Controller;
    use Admin\Controller\CommonController;
    use Think\Upload;

	Class BlogController extends CommonController{
		
		//博文列表
		Public function index(){
			//$field = array('del');
			//$where = array('del' => 0);
			//$this->blog = D('BlogRelation')->field($field, true)->where($where)->relation(true)->select();
			$this->blog = D('BlogRelation')->order('time desc')->getBlogs();
			$this->display();
		}

		//添加博文
		Public function blog(){
			
			//博文所属分类
			import('Class.Category', APP_PATH);
			$Category = new \Category();

			$cate = M('cate')->order('sort')->select();
			$this->cate = $Category::catesort($cate);

			//博文属性
			$this->attr = M('attr')->select();
			$this->display();
		}
		
		//修改博文
		Public function edit(){
				
			//博文所属分类
			import('Class.Category', APP_PATH);
			$Category = new \Category();

			$cate = M('cate')->order('sort')->select();
			$this->cate = $Category::catesort($cate);
			//print_r($Category::catesort($cate));

			//博文属性
			$this->attr = M('attr')->select();
			
			$where = array('id' => $_GET['id']);
			//$this->blog = D('BlogRelation')->field($field, true)->where($where)->relation(true)->select();
			$blog = D('BlogRelation')->where($where)->getBlogs();
			$this->blog = $blog;
			//p($blog);
			$this->id = $_GET['id'];
			$this->display();
		}

		Public function trash(){
			$this->blog = D('BlogRelation')->getBlogs(1);
			$this->display('index');
		}

		Public function toTrash(){
			$id = (int)$_GET['id'];
			$type = (int)$_GET['type'];
			$msg = $type ? '删除' : '还原';
			$update = array(
				'id' => $id,
				'del' => $type
			);
			if(M('blog')->save($update)){
				$this->success($msg . '成功', U(MODULE_NAME . '/Blog/index'));
			}else{
				$this->error($msg . '失败');
			}
		}

		Public function del(){
			$id = (int)$_GET['id'];
			if(M('blog')->delete($id)){
				M('blog_attr')->where(array('bid' => $id))->delete();
				$this->success('删除成功', U(MODULE_NAME . '/Blog/trash'));
			}else{
				$this->error('删除失败');
			}
		}
		/*
			自己写的很烂的清空回收站方法
		*/
		Public function delAll(){
			$blog = M('blog')->where(array('del' => 1))->select();
			foreach($blog as $v){
				if(M('blog')->delete($v['id'])){
					M('blog_attr')->where(array('bid' => $v['id']))->delete();
				}
			}
			$blog = M('blog')->where(array('del' => 1))->select();
			if(count($blog) == 0){
				$this->success('清空完毕', U(MODULE_NAME . '/Blog/trash'));
			}else{
				$this->error('清空失败');
			}
		}

		//添加博文表单处理
		Public function addBlog(){
			if($_FILES['cover_img']['name']){
			    $map['cover_img'] = $_FILES['cover_img']['name'];
			    $res = M('blog')->where($map)->find();
			    if($res){
			        $this->error('存在相同名称的封面');   
			    }
			    $config = array(
			        'maxSize' => 10485760, 
			        'rootPath' => './Uploads',
			        'savePath' => '/cover_img/',
			        'saveName' => '',  
			        'exts' => array('jpg', 'png'), 
			        'autoSub' => false,  
			        //'subName' => array('date','Ymd'),
			    );
			    $upload = new \Think\Upload($config);
			    $info = $upload->upload();
			    if(!$info) {
			        // 上传错误提示错误信息
			        $this->error($upload->getError());
			    }else{
			        // 上传成功 获取上传文件信息
			        $file = $info['cover_img'];
			        $filepath = $config['rootPath'].$file['savepath'].$file['savename'];
			        // rename($filepath, iconv('utf-8','gbk',$filepath)); //window 下防止中文文件名乱码
			    }   
			}

			$data = array(
				'title' => $_POST['title'],
				'cover_img' => $filepath,
				'content' => $_POST['content'],
				'summary' => $_POST['summary'],
				'time' => time(),
				'click' => (int)$_POST['click'],
				'cid' => (int)$_POST['cid'],
			);
			if($bid = M('blog')->add($data)){
				if(isset($_POST['aid'])){
					
					$sql = 'INSERT INTO ' . C('DB_PREFIX') . 'blog_attr (bid, aid) VALUES';
					foreach($_POST['aid'] as $v){
						$sql .= '(' . $bid . ',' . $v . '),';
					}
					$sql = rtrim($sql, ',');
					M()->execute($sql);
			        /*ThinkPHP中execute()和query()方法都可以在参数里直接输入SQL语句。
			        不同的是execute()通常用来执行insert或update等SQL语句，而query常用来执行select等语句。 
					*/
				}

				$this->success('添加成功', U(MODULE_NAME . '/Blog/index'));
			}else{
				$this->error('添加失败');
			}
	

		}
		
		//添加博文表单处理(修改)
		Public function editBlog(){

			if($_POST['cid']==""){
				$data = array (
				    'id' => $_POST ['bid'],
					'title' => $_POST ['title'],
					'content' => $_POST ['content'],
					'summary' => $_POST ['summary'],
					'time' => time (),
					'click' => ( int ) $_POST ['click'] 
				);
			}else{
				$data = array (
				    'id' => $_POST ['bid'],
					'title' => $_POST ['title'],
					'content' => $_POST ['content'],
					'summary' => $_POST ['summary'],
					'time' => time (),
					'click' => ( int ) $_POST ['click'],
					'cid' => ( int ) $_POST ['cid'] 
				);
			}

			//上传封面
			if($_FILES['cover_img']['name']){
			    $map['cover_img'] = $_FILES['cover_img']['name'];
			    $res = M('blog')->where($map)->find();
			    if($res){
			        $this->error('存在相同名称的封面');   
			    }
			    $config = array(
			        'maxSize' => 10485760, 
			        'rootPath' => './Uploads',
			        'savePath' => '/cover_img/',
			        'saveName' => '',  
			        'exts' => array('jpg', 'png'), 
			        'autoSub' => false,  
			        //'subName' => array('date','Ymd'),
			    );
			    $upload = new \Think\Upload($config);
			    $info = $upload->upload();
			    if(!$info) {
			        // 上传错误提示错误信息
			        $this->error($upload->getError());
			    }else{
			        // 上传成功 获取上传文件信息
			        $file = $info['cover_img'];
			        $filepath = $config['rootPath'].$file['savepath'].$file['savename'];
			        $data['cover_img'] = $filepath;
			        // rename($filepath, iconv('utf-8','gbk',$filepath)); //window 下防止中文文件名乱码
			    }   
			}

			
			
			// die(p($data). p($_POST['aid']));
			
			if($bid = M('blog')->save($data)){
				if(isset($_POST['aid'])){
					
					$where = array('bid' => $_POST['bid']);
					M('blog_attr')->where($where)->delete();
						
					$sql = 'INSERT INTO ' . C('DB_PREFIX') . 'blog_attr (bid, aid) VALUES';
					foreach($_POST['aid'] as $v){
						$sql .= '(' . $_POST['bid'] . ',' . $v . '),';
					}
					$sql = rtrim($sql, ',');
					M('blog_attr')->execute($sql);
					
				}
		
				$this->success('修改成功', U(MODULE_NAME . '/Blog/index'));
			}else{
				$this->error('修改失败');
			}
		
		
		}

		//图片上传
		Public function upload(){
			import('ORG.Net.UploadFile');
			$config = array(
			'maxSize'           =>  2000000,    // 上传文件的最大值
			'supportMulti'      =>  true,    // 是否支持多文件上传
			'allowExts'         =>  array('jpg','png','gif'),    // 允许上传的文件后缀 留空不作后缀检查
			'autoSub'           =>  true,// 启用子目录保存文件
			'subType'           =>  'date',// 子目录创建方式 可以使用hash date custom
			'dateFormat'        =>  'Ymd',
			'savePath'          =>  './Uploads/',// 上传文件保存路径
			 );
			 $upload = new UploadFile($config);
			 if($upload->upload()){
				$info = $upload->getUploadFileInfo();
				import('Class.Image', APP_PATH);
				Image::water('./Uploads/' . $info[0]['savename']);
				echo json_encode(
					array(
						'url' => $info[0]['savename'],
						'title' => htmlspecialchars($_POST['pictitle'], ENT_QUOTES),
						'original' => $info[0]['name'],
						'state' => 'SUCCESS'
					)
				);
			 }else{
				echo json_encode(array(
						'state' => $upload->getErrorMsg()
					));
			 }
		
		}


	/**
     * 向浏览器返回数据json数据
     * {
     *   'url'      :'a.jpg',   //保存后的文件路径
     *   'title'    :'hello',   //文件描述，对图片来说在前端会添加到title属性上
     *   'original' :'b.jpg',   //原始文件名
     *   'state'    :'SUCCESS'  //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
     * }
     */
	}
?>