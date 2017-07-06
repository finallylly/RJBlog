<?php
    namespace Home\Controller;
    use Home\Controller\CommonController;
    use Think\Page;

    Class SearchController extends CommonController{
        
        Public function index(){
            import('ORG.Util.Page');
            
            $params = I('get.');
            $map = array();
            $map['del'] = 0;

            $kw = addslashes($params['seaword']);
            $map['_string'] = "`title` like '%{$kw}%' or `content` like '%{$kw}%' or `summary` like '%{$kw}%'";

            $count =  M('blog')->where($map)->count();
            $page = new Page($count, 10);
            $limit = $page->firstRow . ',' . $page->listRows;
            
            $blog = M('blog')->where($map)->limit($limit)->select();
            foreach ($blog as $b => $v) {
                $blog[$b]['cover_img'] || $blog[$b]['cover_img']="./Public/myblog_files/201605241464093846615542.jpg";
                $blog[$b]['comment_count'] += 1;
            }
            
            $this->assign(array(
                'params' => $params,
                'blog' => $blog,
                'page' => $page->show(),
                ));
            $this->display();
        }

    }
?>