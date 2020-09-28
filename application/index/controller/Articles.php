<?php

/**
 * 健康资讯管理
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/1/18
 * Time: 下午5:10
 */
namespace app\index\controller;

use app\index\model\Article;
use app\index\model\ArticleGroup;
use think\Request;

class Articles extends Common
{

    /**
     * 健康资讯文章
     */
    public function index()
    {
        $model = new Article();
        $query = Request::instance()->get();
        $article = $model->getArticle($query);

        $this->assign('article',$article);
        return view();
    }

    /**
     * 添加文章
     * @return array|\think\response\View
     */
    public function addArticle()
    {
        $model = new Article();
        if (request()->isPost()){
            $post = Request::instance()->post();
            $article = $model->addArticle($post);
            return $article;
        }else{
            $model = new ArticleGroup();
            $list = $model->getGroup();
            $this->assign('list',$list);
            return view();
        }
    }

    /**
     * 修改文章
     * @return \think\response\View
     */
    public function editArticle()
    {
        $model = new Article();
        $Group = new ArticleGroup();
        $id = Request::instance()->get('id');
        $list = $Group->getGroup();
        $article = $model->getEditArticle($id);
        $this->assign('list',$list);
        $this->assign('article',$article);
        return view('add_article');
    }

    public function delArticle()
    {
        $model = new Article();
        $id = Request::instance()->post('id');
        $Article = $model->delArticle($id);

        return $Article;
    }

    /**
     * 健康资讯分类
     */
    public function group()
    {
        $model = new ArticleGroup();
        if (request()->isPost()){
            $group = $model->getGroup();
            $list = $model->getTopGroup();
            $arr = ['group'=>$group,'list'=>$list];
            return $arr;
        }else{
            return view();
        }
    }

    /**
     * 添加栏目
     * @return array
     */
    public function addGroup()
    {
        $model = new ArticleGroup();
        $post = Request::instance()->post();
        $group = $model->addGroup($post);

        return $group;
    }

    /**
     * 删除类型
     * @return array
     */
    public function delGroup()
    {
        $model = new ArticleGroup();
        $id = Request::instance()->post('id');
        $group = $model->delGroup($id);

        return $group;

    }

    /**
     * 获取顶级栏目
     * @return array|string
     */
    public function getTopGroup()
    {
        $model = new ArticleGroup();
        $post = Request::instance()->post('id');
        $list = $model->getTopGroupone($post);
        $group = $model->getEditGroup($post);
        $arr = ['list'=>$list,'group'=>$group];
        return $arr;
    }



}






