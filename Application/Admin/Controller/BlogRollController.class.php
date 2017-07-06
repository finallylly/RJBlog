<?php
	namespace Admin\Controller;
    use Admin\Controller\CommonController;
	
	/*自己整了个很烂的友情链接管理*/

	Class BlogRollController extends CommonController{
		Public function index(){
			$this->data = M('url')->order('id')->select();
			$this->display();
		}

		Public function add(){
			$id = I('get.id');
			$id && $this->data = M('url')->where(array('id'=>$id))->find();

			$this->display();
		}

		Public function del(){
			if(M('url')->delete($_GET['id'])){
				$this->success('删除成功', U(MODULE_NAME . '/BlogRoll/index'));
			}else{
				$this->error('删除失败');
			}
		}

		Public function runAdd(){
			$data = array(
				'name' => $_POST['name'],
				'url' => $_POST['url'],
			);

			if ($id = $_POST['save']) {
				$data['id'] = $id;
				M('url')->save($data);
				$this->success('修改成功', U(MODULE_NAME . '/BlogRoll/index'));
			}elseif($bid = M('url')->add($data)){
				$this->success('添加成功', U(MODULE_NAME . '/BlogRoll/index'));
			}else{
				$this->error('添加失败');
			}
		}
	}
?>