<?php
	namespace Admin\Controller;
    use Admin\Controller\CommonController;
    
	Class CategoryController extends CommonController{
		
		//添分类列表视图
		Public function index(){
			import('Class.Category', APP_PATH);
			$Category = new \Category();

			$cate = M('cate')->order('sort')->select();
		
			$cate = $Category::catesort($cate);
		
			$this->cate = $cate;
			$this->display();
		}

		//添加分类视图
		Public function addCate(){
			$this->pid = I('pid', 0, 'intval');
			$this->display();
		}

		/*
			自己增加的删除方法（不用理会）
		*/
		Public function cateDel(){
			$db = M('cate');
			$cate = $db->select();
			import('Class.Category', APP_PATH);
			$Category = new \Category();
			
			$cate = $Category::getChildsId($cate,I('id', '', 'intval'));
			if(count($cate) == 0){
				if($db->delete(I('id', '', 'intval'))){
				$this->success('删除成功', U(MODULE_NAME . '/Category/index'));
				}else{
					$this->error('删除失败');
				}
			}else{
				$this->error('当前栏目有子栏目不能删除');
			}
			
		}

		//添加分类表单处理
		Public function runAddCate(){
			if (M('cate')->add($_POST)){
				$this->success('添加成功', U(MODULE_NAME . '/Category/index'));
			}else{
				$this->error('添加失败');
			}
		}

		Public function cateSort(){
			$db = M('cate');
			foreach($_POST as $id => $sort){
				$db->where(array('id' => $id))->setField('sort', $sort);
			}
			$this->redirect(MODULE_NAME . '/Category/index');
		}
	}
?>