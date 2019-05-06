<?php

namespace app\admin\controller;

use think\Db;
use think\Request;
use think\Validate;

class Huati extends Base
{
    protected $paginate = 10;
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '广场');
    }


    public function index()
    {
        $where = [];
        $start_time = input('start_time');
        $end_time = input('end_time');
        $content = input('content');
        if($start_time && $end_time){
            $where['dateline']  = ['between',[strtotime($start_time),strtotime($end_time)]];
        }
        if(!empty($content)){
            $where['content'] = ['like',['%'.$content.'%']];
        }
        $data = Db::name('square')->where($where)->paginate($this->paginate);
        $list = $data->items();
        $page = $data->render();
        foreach ($list as $k => &$v){
            $v['nickname'] = Db::name('member')->where('uid',$v['uid'])->value('nickname');
        }
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('content', $content);
        $this->assign('start_time', $start_time);
        $this->assign('end_time', $end_time);
        return view();
    }

    /**
     * 编辑项目信息
     */
    public function edit()
    {
        $id = input('id');
        $where = ['id' => $id];
        $data = Db::name('square')->where($where)->find();
        if (empty($data)) {
            $this->error('话题不存在或已删除');
        }
        $data['res_url'] = json_decode($data['res_url'],true);
        $this->assign([
            'data' => $data,
        ]);
        return $this->fetch('add');

    }


    //设置帖子推荐
    public function tuijian()
    {
        $id = input('id');
        $v = Db::name('square')->where('id', $id)->value('is_rem');
        $tuijian = $v ? 0 : 1;
        $re = Db::name('square')->where('id', $id)->update(['is_rem' => $tuijian]);
        if ($re) {
            return json(['code' => 0, 'msg' => '操作成功']);
        }
        return json(['code' => 1, 'msg' => '操作失败']);
    }


    /**
     * 删除项目在线
     */
    public function del()
    {
        $ids = input('id');
        Db::name('topic')->where('id', 'in', $ids)->delete();
        $this->success("操作成功");
    }

    /**
     * 设置首页推荐
     */
    /*public function indexShow()
    {
        if ($this->admin['cat'] != 1) {
            $this->error('暂无权限');
        }
        $ids = input('id');
        Db::startTrans();
        try {
            $idArr = explode(',', $ids);
            foreach ($idArr as $id) {
                $re = Db::name('article')->where('id', $id)->update(['index_show' => 1]);
                if ($re) {
                    Db::name('index_show')->insert(['type' => $this->type, 'itemid' => $id]);
                }
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->error("系统异常");
        }
        $this->success("操作成功");
    }*/

    /**
     * 设置首页推荐
     */
    /*public function indexShowDel()
    {
        if ($this->admin['cat'] != 1) {
            $this->error('暂无权限');
        }
        $ids = input('id');
        Db::startTrans();
        try {
            Db::name('article')->where('id', 'in', $ids)->update(['index_show' => 0]);
            $idArr = explode(',', $ids);
            foreach ($idArr as $id) {
                if ($id) {
                    Db::name('index_show')->where(['type' => $this->type, 'itemid' => $id])->delete();
                }
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->error("系统异常");
        }
        $this->success("操作成功");
    }*/

    /**
     * 检测是否有编辑权限
     */
    private function checkPermission($admin, $idStr)
    {
        $ids = array_filter(explode(',', $idStr));

        //判断是否是管理员组
        if ($admin['cat'] == 1) {
            return true;
        } else {
            foreach ($ids as $id) {
                $re = Db::name('article')
                    ->where('adminid', $admin['id'])
                    ->find();
                if ($re) {
                    continue;
                } else {
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * 话题列表
     */
    public function huatiList()
    {

        $data = Db::name('huati')->paginate($this->paginate);
        $list = $data->items();
        $page = $data->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        return view('huatiList');
    }

    /**
     * 添加话题分类
     */
    public function addHuati()
    {

        if (Request::instance()->isPost()) {
            $validate = new Validate([
                ['ht_title', 'require', '话题名称必须填写'],
            ]);
            $params = Request::instance()->param();
            if (!$validate->check($params)) {
                $this->error($validate->getError());
            }

            $params['addtime'] = time();
            Db::name('huati')->insert($params, true);
            return $this->success('添加成功');
        }
        return view('addHuati');
    }

    /**
     * 编辑话题分类
     */
    public function editHuati()
    {

        $htid = input('id');
        $where = ['htid' => $htid];
        $data = Db::name('huati')->where($where)->find();
        if (empty($data)) {
            $this->error('话题不存在或已删除');
        }

        $this->assign([
            'data' => $data,
        ]);
        return $this->fetch('addHuati');
    }

    /**
     * 编辑话题分类
     */
    public function delHuati()
    {

        $ids = input('id');
        if (!self::checkPermission($this->admin, $ids)) {
            $this->error('项目不存在或已删除');
        }
        Db::name('huati')->where('htid', 'in', $ids)->delete();
        $this->success("操作成功");
    }

}
