<?php
 	namespace Home\Model;
	use Think\Model\ViewModel;

	Class BlogViewModel extends ViewModel{
		Protected $viewFields = array(
			'blog' => array('id', 'title', 'cover_img','time', 'click', 'summary', '_type' =>'LEFT'),
			'cate' => array('name', '_on' => 'blog.cid = cate.id'),

		);

		Public function getAll($where, $limit){
			return $this->where($where)->limit($limit)->order('time DESC')->select();
		}
	}
?>