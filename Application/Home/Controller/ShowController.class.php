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
			
			if ($params['token']!=md5(date("Y-m-d"))) {
				$this->error('数据异常');
			}
			
			$data = array(
				'username' => isset($params['inpName'])?$params['inpName']:'visitor',
				'mail' => $params['inpEmail'],
				'site' => $params['inpHomePage'],
				'content' => htmlspecialchars($params['txaArticle']),  //防止script注入
				'time' => date("Y-m-d H:i:s"),
				'pid' => intval($params['inpId']),
				'bid' => $params['bid'],
			);

			if($id=M('comment')->add($data)){
				$this->asyncTask($id); //异步->伪多线程
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}

		//异步线程发送邮件
		function asyncTask($id){
			$fp=fsockopen($_SERVER['HTTP_HOST'],80,$errno,$errstr,5);
			if(!$fp){
				echo "$errstr ($errno)<br />/n"; 
			}

			$out = "GET ".U('Show/sendMail')."?id=".$id." HTTP/1.1\r\n"; //注意HTTP/1.1不能少
            $out .= "Host: ".$_SERVER['HTTP_HOST']."\r\n";
            $out .= "Connection: close\r\n\r\n";

			fputs($fp,$out);  //访问请求, 用fwrite也可以
            
            //打印请求结果
            // while (!feof($fp)) {
            //     echo fgets($fp, 128);
            // }
            
            usleep(1000); //延迟关闭$fp，让nginx将该请求代理给上游服务（FastCGI PHP进程）
            fclose($fp);
		}

		//有新评论发送邮件提示
		function sendMail(){
			$id = I('get.id','','urldecode');
			$data=M('comment')->where(array('id'=>$id))->find();
			// fopen("C:/".time(),"w"); //window测试

			require_once "./Extends/PHPMailer/PHPMailerAutoload.php";
			$mail = new \PHPMailer;                               //新建mail类, 否则发送邮箱名会叠加
			            
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->CharSet='UTF-8';                               //设置邮件的字符编码，这很重要，不然中文乱码 
			$mail->Host = 'smtp.163.com';                   // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication

			$mail->Username = C('MAIL_USER');      // SMTP username
			$mail->Password = C('MAIL_PWD');               // SMTP password
			$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;                                     // TCP port to connect to
			$mail->setFrom(C('MAIL_USER'), 'RJ博客评论系统');

			$mail->addAddress("347881230@qq.com");      // Name is optional
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'RJ博客新评论提示';


			$content = '<pre>'.print_r($data, true).'</pre>';
			$mail->Body    = $content;

			$mail->AltBody = '为了查看该邮件，请切换到支持 HTML 的邮件客户端';
			$mail->send();
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