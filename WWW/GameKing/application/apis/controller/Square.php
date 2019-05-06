<?php

namespace app\apis\controller;

use think\Db;
use think\Controller;
use think\Request;
use think\Validate;

/**
 * swagger: 广场模块接口
 */
class Square extends Controller
{
    /**
     * get: 获取广场推荐列表
     * path: recommended_list
     * method: recommended_list
     * param: pageSize - {int} 每页显示数量[选填]，默认10
     * param: page - {int} 分页页码[选填]
     */
    public function recommended_list()
    {
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $params = Request::instance()->param();
        $where['is_rem'] = 1;
        if (isset($params['keywords'])) {
            $where['content'] = ['like','%'.$params['keywords'].'%'];
        }
        $params['pageSize'] = isset($params['pageSize']) ? $params['pageSize'] : 10;
        $lists = Db::name('square')->field(['is_rem','pltime'],true)->where($where)->order('is_rem desc,views desc,praises desc,collects desc,createtime desc')->paginate($params['pageSize'])->each(function ($item, $key) use($uid){
            $item['res_url'] = json_decode($item['res_url'],true);
            $item['createtime'] = time_tran($item['createtime']);
            $item['userInfo'] = Db::name('member')->where('uid', $item['uid'])->field('sex,nickname,avatar')->find();
            $item['is_focus'] = Db::name('favorite')->where(['uid'=>$uid,'itemid'=>$item['uid'],'type'=>1])->count();
            $item['is_zan'] = Db::name('praise')->where(['uid' => $uid, 'tid' => $item['id']])->count();
            $item['is_collect'] = Db::name('collect')->where(['uid' => $uid, 'itemid' => $item['id']])->count();
            return $item;
        });
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $lists]);
    }
    /**
     * get: 获取广场关注列表
     * path: focus_list
     * method: focus_list
     * param: pageSize - {int} 每页显示数量[选填]，默认10
     * param: page - {int} 分页页码[选填]
     */
    public function focus_list()
    {
        $params = Request::instance()->param();
        $params['pageSize'] = isset($params['pageSize']) ? $params['pageSize'] : 10;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }

        $rem = Db::name('favorite')->where(['uid'=>$uid,'type'=>1])->column('itemid');
        //我关注的用户列表
            if (isset($params['keywords'])) {
                $where['content'] = ['like','%'.$params['keywords'].'%'];
            }
            $where['uid'] = ['in',$rem];
//            dump($where);die;
            $lists = Db::name('square')->where($where)->order('is_rem desc,praises desc,pltime desc,createtime desc')->paginate($params['pageSize'])->each(function ($item, $key) use($uid) {
                $item['res_url'] = json_decode($item['res_url'],true);
                $item['createtime'] = time_tran($item['createtime']);
                $item['userInfo'] = Db::name('member')->where('uid', $item['uid'])->field('nickname,avatar,sex')->find();
                $item['is_focus'] = 1;
                $item['is_zan'] = Db::name('praise')->where(['uid' =>$uid, 'tid' => $item['id']])->count();

                $item['is_collect'] = Db::name('collect')->where(['uid' => $uid, 'itemid' => $item['id']])->count();
                return $item;
            });

        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $lists]);
    }

    /**
     * get: 获取广场最新列表
     * path: new_list
     * method: new_list
     * param: pageSize - {int} 每页显示数量[选填]，默认10
     * param: page - {int} 分页页码[选填]
     */
    public function new_list()
    {

        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $params = Request::instance()->param();
        $params['pageSize'] = isset($params['pageSize']) ? $params['pageSize'] : 10;
        $where = [];
        if (isset($params['keywords'])) {
            $where['content'] = ['like','%'.$params['keywords'].'%'];
        }
        $lists = Db::name('square')->order('createtime desc,views desc,praises desc,collects desc')->where($where)->paginate($params['pageSize'])->each(function ($item, $key) use($uid) {
            $item['res_url'] = json_decode($item['res_url'],true);
            $item['createtime'] = time_tran($item['createtime']);
            $item['userInfo'] = Db::name('member')->where('uid', $item['uid'])->field('nickname,avatar,sex')->find();
            $item['is_focus'] = Db::name('favorite')->where(['uid'=>$uid,'itemid'=>$item['uid'],'type'=>1])->count();
            $item['is_zan'] = Db::name('praise')->where(['uid' => $uid, 'tid' => $item['id']])->count();
            $item['is_collect'] = Db::name('collect')->where(['uid' => $uid, 'itemid' => $item['id']])->count();
            return $item;
        });
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $lists]);
    }


    /**
     * get: 获取帖子详情
     * path: getSquareDetail
     * method: getSquareDetail
     * param: id - {int} 帖子ID
     */
    public function getSquareDetail()
    {
        $id = input('id', 0);
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        if (!$id) {
            return json(['code' => 400, 'msg' => '帖子ID不存在']);
        }
        $square = Db::name('square')->where('id', $id)->find();
        if (!$square) {
            return json(['code' => 400, 'msg' => '帖子不存在']);
        }
        Db::name('square')->where(['id'=>$id])->setInc('views',1);
        $square['res_url'] = json_decode($square['res_url'],true);
        $square['createtime'] = time_tran($square['createtime']);
        $square['userInfo'] = Db::name('member')->where('uid', $square['uid'])->field('sex,nickname,avatar')->find();
        $square['is_focus'] = Db::name('favorite')->where(['uid'=>$uid,'itemid'=>$square['uid'],'type'=>1])->count();
        $square['is_zan'] = Db::name('praise')->where(['uid' => $uid, 'tid' => $id])->count();
        $square['is_collect'] = Db::name('collect')->where(['uid' => $uid, 'itemid' => $id])->count();
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $square]);
    }


    /**
     * get: 获取帖子分享链接
     * path: getSquareShare
     * method: getSquareShare
     * param: id - {int} 帖子ID
     */
    public function getSquareShare()
    {

        $id = input('id', 0);
        if (!$id) {
            return json(['code' => 400, 'msg' => '帖子ID不存在']);
        }
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $square = Db::name('square')->where('id', $id)->find();
        if (!$square) {
            return json(['code' => 400, 'msg' => '帖子不存在']);
        }
        Db::name('square')->where(['id'=>$id])->setInc('forwarding',1);
        $url = $_SERVER['HTTP_HOST'] . url('apis/Square/getSquareDetail',['id'=>$id,'shareid'=>$uid]);
        return json(['code' => 200, 'msg' => 'SUCCESS', 'url' =>$url
        ]);
    }



    /**
     * get: 获取话题回复
     * path: getSquareComment
     * method: getSquareComment
     * param: id - {int} 帖子ID
     */
    public function getSquareComment()
    {

        $id = input('id/d', 0);
        $uid = input('uid/d', 0);
        if (!$id) {
            return json(['code' => 400, 'msg' => '帖子ID不存在']);
        }
        $square = Db::name('square')->where('id', $id)->find();
        if (!$square) {
            return json(['code' => 400, 'msg' => '帖子不存在']);
        }
        $list = Db::name('sq_comment')->where(['tid' => $id,'pid'=>0])->order('id asc')->select();
        foreach ($list as &$v) {
            $v['comments'] = Db::name('sq_comment')->where(['fid'=>$v['id']])->count();
            $v['praises'] = Db::name('praise_comment')->where(['tid'=>$v['id']])->count();
            $v['createtime'] = date('m-d H:i',$v['createtime']);
            $v['is_zan'] = Db::name('praise_comment')->where(['tid'=>$v['id'],'uid'=>$uid])->count();
            $v['user'] = Db::name('member')->field('nickname,avatar')->where('uid', $v['uid'])->find();
            $v['reply_list'] = Db::name('sq_comment')->where(['tid'=>$v['tid'],'fid'=>$v['id']])->select();
            foreach ($v['reply_list'] as $k => &$vv){
                $vv['to_user'] = Db::name('member')->field('nickname,avatar')->where('uid', $vv['to_uid'])->find();
                $vv['user'] = Db::name('member')->field('nickname,avatar')->where('uid', $vv['uid'])->find();
                $vv['createtime'] = date('m-d H:i',$vv['createtime']);
            }
        }
//        dump($list);die;
        return json(['code' => 200,'msg'=> 'SUCCESS','data' => $list]);
    }

    /**
     * get: 获取单个评论下全部回复
     * path: getCommentReply
     * method: getCommentReply
     * param: id - {int} 评论ID
     */
    public function getCommentReply()
    {

        $id = input('id', 0);
        if (!$id) {
            return json(['code' => 400, 'msg' => '评论ID不存在']);
        }
        $list = Db::name('sq_comment')->field('id,uid,content,createtime')->where('id', $id)->find();
        if (!$list) {
            return json(['code' => 400, 'msg' => '评论不存在']);
        }
        $list['comments'] = Db::name('sq_comment')->where(['fid'=>$list['id']])->count();
        $list['praises'] = Db::name('praise_comment')->where(['tid'=>$list['id']])->count();
        $list['user'] = Db::name('member')->field('nickname,avatar')->where('uid', $list['uid'])->find();
        $list['createtime'] = date('m-d H:i',$list['createtime']);
        $list['reply_list'] = Db::name('sq_comment')->field('id,uid,content,createtime')->where(['fid'=>$list['id']])->select();
            foreach ($list['reply_list'] as $k => &$vv){
                $vv['praises'] = Db::name('praise_comment')->where(['tid'=>$vv['id']])->count();
                $vv['user'] = Db::name('member')->field('nickname,avatar')->where('uid', $vv['uid'])->find();
                $vv['createtime'] = date('m-d H:i',$vv['createtime']);
            }
        return json(['code' => 200,'msg'=> 'SUCCESS','data' => $list]);
    }

    /**
     * post: 发布帖子
     * path: addSquare
     * method: addSquare
     * table square
     * param: content - {string} 帖子详情
     * param: res_url - {string} 话图片或视频地址
     * param: type    - {int} 帖子类型1图片2视频

     */
    public function addSquare()
    {
        if(!Request::instance()->isPost()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $params = Request::instance()->param();
        if(!isset($params['type'])){
            return json(['code'=>400,'msg'=>'缺少参数type']);
        }

        if(!isset($params['content'])){
            return json(['code'=>400,'msg'=>'缺少参数content']);
        }
        $res_url = '';
        if(!empty($params['res_url'])){
            $res_url = json_encode(explode(',',$params['res_url']));
        }
        $data = [
            'uid' => $uid,
            'type'=>$params['type'],
            'content' => $params['content'],
            'res_url' => $res_url,
            'createtime' => time(),
        ];
        $re = Db::name('square')->insertGetId($data);
        if ($re) {
            return json(['code' => 200, 'msg' => '发布成功']);
        }
        return json(['code' => 400, 'msg' => '发布失败']);
    }

    /**
     * get: 删除帖子
     * path: delSquare
     * method: delSquare、
     * table square
     * param: id - {int} 话题ID
     */
    public function delSquare()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if (!$uid) {
            return json(['code' => 400, 'msg' => '缺少参数uid']);
        }
        $id = input('id', 0);
        if (!$id) {
            return json(['code' => 400, 'msg' => '帖子ID不存在']);
        }
        $re = Db::name('square')->where(['id' => $id, 'uid' => $uid])->delete();
        if ($re) {
            return json(['code' => 200, 'msg' => '删除成功']);
        }
        return json(['code' => 400, 'msg' => '删除失败']);
    }

    /**
     * post: 发表评论及回复
     * path: addComment
     * method: addComment
     * param: tid - {int} 帖子ID
     * param: to_uid - {int} ID
     * param: pid - {int} 回复ID
     * param: fid - {int} 评论fid
     */
    public function addComment()
    {
        if(!Request::instance()->isPost()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if (!$uid) {
            return json(['code' => 400, 'msg' => '缺少参数uid']);
        }
        $tid = input('tid', 0);
        $fid = input('fid', 0);
        $to_uid = input('to_uid', 0);
        $content = input('content');
        if (!$tid) {
            return json(['code' => 400, 'msg' => '缺少参数帖子tid']);
        }
        if (!$to_uid) {
            return json(['code' => 400, 'msg' => '缺少参数to_uid']);
        }
        if (!$content) {
            return json(['code' => 400, 'msg' => '缺少参数content']);
        }
        $re = Db::name('square')->where(['id'=>$tid])->find();
        if(empty($re)){
            return json(['code' => 400, 'msg' => '帖子不存在']);
        }
        $pid = input('pid', 0);
        Db::name('square')->where('id', $tid)->setInc('comments', 1);
        Db::name('sq_comment')->insert(['tid' => $tid, 'uid' => $uid,'to_uid'=>$to_uid, 'content' => $content, 'pid' => $pid, 'fid'=>$fid,'createtime' => time()]);
        //发送APP消息通知
        $acceptUid = Db::name('square')->where('id', $tid)->value('uid');
        $title = '帖子回复';
        $content = '您发表的帖子有人回复，赶快去看看吧~~';
        $custom = ['tid' => $tid,'from_uid'=>$uid,'to_uid'=>$acceptUid];
        xingePushSingleDeviceNotification($acceptUid, $title, $content, $custom);
        return json(['code' => 200, 'msg' => 'SUCCESS']);
    }

    /**
     * get: 删除评论及其回复
     * path: delComment
     * method: delComment
     * param: id - {int} 评论ID
     */
    public function delComment()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if (!$uid) {
            return json(['code' => 400, 'msg' => '缺少参数uid']);
        }
        $id = input('id', 0);
        if (!$id) {
            return json(['code' => 400, 'msg' => '评论ID不存在']);
        }
        Db::startTrans();
        try{
            Db::name('sq_comment')->where(['uid' => $uid, 'id' => $id])->delete();
            Db::name('sq_comment')->where(['fid' => $id])->delete();
            Db::commit();
            return json(['code' => 200, 'msg' => '删除成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code' => 400, 'msg' => $e->getMessage()]);
        }

    }

    /**
     * get: 根据评论id删除
     * path: delOnlyId
     * method: delOnlyId
     * param: id - {int} 评论ID
     */
    public function delOnlyId()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if (!$uid) {
            return json(['code' => 400, 'msg' => '缺少参数uid']);
        }
        $id = input('id', 0);
        if (!$id) {
            return json(['code' => 400, 'msg' => '评论不存在']);
        }
        Db::name('sq_comment')->where(['uid' => $uid, 'id' => $id])->delete();
        return json(['code' => 200, 'msg' => '删除成功']);
    }


    /**
     * get: 点赞
     * path: praise
     * method: praise
     * table:praise
     * param: tid - {int} 贴子ID
     */
    public function praise()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if (!$uid) {
            return json(['code' => 400, 'msg' => '缺少参数uid']);
        }
        $tid = input('tid', 0);
        if (!$tid) {
            return json(['code' => 400, 'msg' => '帖子ID不存在']);
        }
        $square = Db::name('square')->where(['id' => $tid])->find();
        if(!$square){
            return json(['code' => 400, 'msg' => '帖子不存在']);
        }
        $has = Db::name('praise')->where(['tid' => $tid, 'uid' => $uid])->find();
        if ($has) {
            return json(['code' => 400, 'msg' => '不能重复点赞']);
        }
        // 启动事务
        Db::startTrans();
        try{
            Db::name('praise')->insertGetId(['tid' => $tid, 'uid' => $uid, 'createtime' => time()]);
            Db::name('square')->where('id', $tid)->setInc('praises', 1);
            // 提交事务
            Db::commit();
            return json(['code' => 200, 'msg' => '点赞成功']);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code' => 400, 'msg' =>$e->getMessage()]);
        }
    }


    /**
     * get: 评论点赞
     * path: sqPraise
     * method: sqPraise
     * table:praise
     * param: 被点赞的评论id - {int}
     * param: uid - {int}
     */
    public function sqPraise()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        $type = input('type',0);
        if (!$uid) {
            return json(['code' => 400, 'msg' => '缺少参数uid']);
        }
        if (!$type) {
            return json(['code' => 400, 'msg' => '缺少参数type']);
        }
        $id = input('id', 0);
        if (!$id) {
            return json(['code' => 400, 'msg' => '评论ID不存在']);
        }
        $comment = Db::name('sq_comment')->where(['id' => $id])->find();
        if(!$comment){
            return json(['code' => 400, 'msg' => '评论不存在']);
        }
        if($type == 2){
            Db::name('praise_comment')->where(['tid' => $id, 'uid' => $uid])->delete();
            return json(['code' => 200, 'msg' => '取消成功']);
        }
        if(Db::name('praise_comment')->where(['tid' => $id, 'uid' => $uid])->find()){
            return json(['code'=>400,'msg'=>'不能重复点赞哦']);
        }
        $res = Db::name('praise_comment')->insertGetId(['tid' => $id, 'uid' => $uid, 'createtime' => time()]);
        if($res){
            return json(['code' => 200, 'msg' => '点赞成功']);
        }
        return json(['code' => 400, 'msg' =>'点赞失败']);
    }



    /**
     * get: 取消点赞
     * path: unPraise
     * method: unPraise
     * table: praise、square
     * param: tid - {int} 话题ID
     */
    public function unPraise()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if (!$uid) {
            return json(['code' => 400, 'msg' => '缺少参数uid']);
        }
        $tid = input('tid', 0);
        if (!$tid) {
            return json(['code' => 400, 'msg' => '帖子ID不存在']);
        }
        // 启动事务
        Db::startTrans();
        try{
            Db::name('praise')->where(['tid' => $tid, 'uid' => $uid])->delete();
            Db::name('square')->where('id', $tid)->setDec('praises', 1);
            Db::commit();
            return json(['code' => 200, 'msg' => '取消成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code' => 400, 'msg' =>$e->getMessage()]);
        }
    }




    /**
     * get: 帖子搜索
     * path: sqSearch
     * method: sqSearch
     * param: pageSize - {int} 每页显示数量[选填]，默认10
     * param: page - {int} 分页页码[选填]
     * param: keywords - {void} 搜索关键词
     */
    public function sqSearch()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if (!$uid) {
            return json(['code' => 400, 'msg' => '缺少参数uid']);
        }
        $params = Request::instance()->param();
        $params['pageSize'] = isset($params['pageSize']) ? $params['pageSize'] : 10;
        $where = [];
        if (isset($params['keywords'])) {
            $where['content'] = ['like','%'.$params['keywords'].'%'];
        }
        $lists = Db::name('square')->where($where)->order('createtime desc')->paginate($params['pageSize'])->each(function ($item,$key) use($uid){
            $item['res_url'] = json_decode($item['res_url'],true);
            $item['createtime'] = time_tran($item['createtime']);
            $item['userInfo'] = Db::name('member')->where('uid', $item['uid'])->field('sex,nickname,avatar')->find();
            $item['length'] = mb_strlen($item['content']);
            $item['is_focus'] = Db::name('favorite')->where(['uid'=>$uid,'itemid'=>$item['uid'],'type'=>1])->count();
            $item['is_zan'] = Db::name('praise')->where(['uid' => $uid, 'tid' => $item['id']])->count();
            $item['is_collect'] = Db::name('collect')->where(['uid' => $uid, 'itemid' => $item['id']])->count();
            return $item;
        });
        return json(['code' => 200, 'msg'=>'SUCCESS','data' => $lists]);
    }


}
