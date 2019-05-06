<?php

namespace app\apis\controller;

use app\common\library\Email;
use think\Controller;
use think\Db;
use think\Request;

/**
 * swagger: 消息相关接口
 */
class Message extends Controller
{

    /**
     * get: 获取头像
     * path: getAvatar
     * method: getAvatar
     */
    public function getAvatar()
    {
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $userInfo = Db::name('member')->where('uid', $uid)->field('avatar,nickname')->find();
        if($userInfo){
            //return json(['code' => 200, 'msg' => 'SUCCESS', 'avatar' => add_prefix($userInfo['avatar'],$_SERVER['HTTP_HOST']), 'nickname' => $userInfo['nickname']]);
            return json(['code' => 200, 'msg' => 'SUCCESS', 'avatar' => $userInfo['avatar'], 'nickname' => $userInfo['nickname']]);

        }
        return json(['code'=>400,'msg'=>'暂无信息']);

    }
    /**
     * post: 上传
     * path: upload
     * method: upload
     * param: file - {string} 附件
     */
    public function upload()
    {
        $file = $this->request->file('file');
        $info = $file->move(ROOT_PATH . 'public_html' . DS . 'uploads' . DS . 'image');
        if ($info) {
            $url = 'uploads' . DS . 'image' . DS . $info->getSaveName();
            return json(['code' => 200, 'msg' => '上传成功', 'url' => $url]);
        } else {
            //上传失败获取错误信息
            return json(['code' => 400, 'msg' => $file->getError()]);
        }
    }

    /**
     * post: 消息列表
     * path: msgList
     * method: msgList
     * param: page - {int} 分页页码[选填]
     */
    public function msgList()
    {
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $cid = Db::name('member')->where(['is_customer'=>1])->value('uid');
        $lists = Db::name('chat_history')->where(['uid'=>$uid,'other_uid'=>['neq',$cid]])->order('addtime desc')->paginate(10)->each(function ($item, $key) {
            $userInfo = Db::name('member')->where('uid', $item['other_uid'])->field('uid,avatar,nickname')->find();
            $lastMessage = Db::name('chat_message')
                ->where('(from_uid = '.$item['uid'] . ' AND to_uid = '. $item['other_uid'] . ') OR (from_uid = '. $item['other_uid'] . ' AND to_uid = '. $item['uid'] .  ' )')
                ->order('id desc')->find();
            $lastMessage['sendtime'] = date('Y-m-d H:i', $lastMessage['sendtime']);
            $userInfo['lastMessage'] = $lastMessage;
            $userInfo['unread'] = Db::name('chat_message')
                ->where(['to_uid' => $item['uid'], 'from_uid' => $item['other_uid'], 'readtime' => 0])
                ->count();
            return $userInfo;
        });
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $lists]);

    }


    /**
     * post: 我的消息
     * path: myMsg
     * method: myMsg
     */
    public function myMsg()
    {
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $sq_time = Db::name('sq_comment')->where(['to_uid'=>$uid])->value('createtime');
        $order_time = Db::name('order')->where(['uid'=>$uid])->value('dateline');
        if(empty($sq_time) && empty($order_time)){
            $msg = '暂无消息！' ;
        }elseif($sq_time <= $order_time){
            $msg = '您的订单有最新消息...' ;
        }else{
            $msg = '您的评论有最新消息...' ;
        }
        return json(['code' => 200, 'msg' => 'SUCCESS', 'type' => $msg]);
    }

    /**
     * post: 订单和评论消息列表
     * path: squareAndOrderMsg
     * method: squareAndOrderMsg
     */
    public function squareAndOrderMsg()
    {
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $lists['sq_msg']['content'] = '暂无评论消息';
        $lists['order_msg']['content'] = '暂无订单消息';
        $sqMsg = Db::name('sq_comment')->where(['to_uid'=>$uid])->order('createtime desc')->find();
        $orderMsg = Db::name('order')->where(['uid'=>$uid])->order('dateline desc')->find();
//        if(!empty($orderMsg)){
//            $lists['order_msg']['content'] = '您的订单有最新消息...';
//        }
        if(!empty($sqMsg)){
            $nickname = Db::name('member')->where('uid', $sqMsg['uid'])->value('nickname');
            $lists['sq_msg']['content'] = $nickname . '评论了您的发帖...';
            $lists['sq_msg']['tid'] = $sqMsg['tid'];
        }
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $lists]);

    }

    /**
     * post: 评论消息列表
     * path: squareMsgList
     * method: squareMsgList
     */
    public function squareMsgList()
    {
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $data = Db::name('sq_comment')->where(['to_uid'=>$uid])->order('createtime desc')->select();
        foreach ($data as $k => &$v){
            $v['createtime'] = time_tran($v['createtime']);
            $v['userinfo'] = Db::name('member')->where(['uid'=>$v['uid']])->field('uid,avatar,nickname')->find();
            $v['userinfo']['avatar'] = add_prefix($v['userinfo']['avatar'],$_SERVER['HTTP_HOST']);
            $v['squareinfo'] = Db::name('square')->where(['uid'=>$v['to_uid'],'id'=>$v['tid']])->field('id,content,res_url')->find();
            $v['squareinfo']['res_url'] = add_prefix(json_decode($v['squareinfo']['res_url'],true),$_SERVER['HTTP_HOST']);
        }
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $data]);

    }

    /**
     * get: 平台客服
     * path: platform
     * method: platform
     */
//ALTER TABLE `kg_member`
//ADD COLUMN `is_customer`  tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否为平台客服' AFTER `invite_code`;
    public function platform(){
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $cid = Db::name('member')->where(['is_customer'=>1])->value('uid');
        if(empty($cid)){
            return json(['code'=>400,'msg'=>'暂无客服人员']);
        }
        $data = Db::name('chat_message')->field('from_uid,to_uid,msg_type,sendtime,content')->where('(from_uid = '.$uid . ' AND to_uid = '. $cid . ') OR (from_uid = '. $cid . ' AND to_uid = '. $uid .  ' )')->order('id desc')->find();
        if($data){
            $data['sendtime'] = date('Y-m-d H:i',$data['sendtime']);
            $data['unread'] = Db::name('chat_message')
                ->where(['to_uid' => $uid, 'from_uid' => $cid, 'readtime' => 0])
                ->count();
        }else{
            $data['from_uid'] = $uid;
            $data['to_uid'] = $cid;
            $data['content'] = '';
            $data['sendtime'] = 0;
            $data['unread'] = 0;
        }
        return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
    }


    /**
     * post: 系统通知
     * path: systemMsg
     * method: systemMsg
     */
    public function systemMsg()
    {

        $data = Db::name('system_message')->order('id desc')->find();
        if($data){
            return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $data]);
        }
        return json(['code' => 400, 'msg' => '暂无系统通知']);
    }

    /**
     * post: 系统通知列表
     * path: systemMsgList
     * method: systemMsgList
     */
    public function systemMsgList()
    {
        $data = Db::name('system_message')->order('id desc')->select();
        if($data){
            return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $data]);
        }
        return json(['code' => 400, 'msg' => '暂无系统通知']);
    }



    /**
     * post: 消息详情
     * path: chatDetails
     * method: chatDetails
     * param: page - {int} 分页页码[选填]
     * param: tid - {int} 聊天对方UID
     */
    public function chatDetails()
    {
        $tid = input('tid');
        if(empty($tid)){
            return json(['code'=>400,'msg'=>'缺少参数tid']);
        }
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        //['from_uid' => $uid, 'to_uid' => $tid])->whereOr(['from_uid' => $tid, 'to_uid' => $uid]
        $chatList = Db::name('chat_message')->where('(from_uid = '.$uid . ' AND to_uid = '. $tid . ') OR (from_uid = '. $tid . ' AND to_uid = '. $uid .  ' )')->order('id desc')->paginate(10)->each(function ($item, $key) {
            $nowDay = date('d');
            $msgDay = date('d', $item['sendtime']);
            $nowHour = date('H', $item['sendtime']);
            if ($msgDay == $nowDay) {
                if($nowHour <= 12){
                    $item['sendtime'] = '上午' . date('H:i', $item['sendtime']);
                }else{
                    $item['sendtime'] = '下午' . date('H:i', $item['sendtime']);
                }
            } else {
                if($nowHour <= 12){
                    $item['sendtime'] = date('m-d', $item['sendtime']) . ' 上午' . date('H:i', $item['sendtime']);
                }else{
                    $item['sendtime'] = date('m-d', $item['sendtime']).' 下午' . date('H:i', $item['sendtime']);

                }
            }
            return $item;
        });
        return json(['code' => 200, 'data' => $chatList]);
    }
    /**
     * post: 发送消息通知
     * path: sendNotify
     * method: sendNotify
     * param: uid - {int} 用户UID
     * param: id - {int} 消息ID
     */
    public function sendNotify()
    {
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $id = input('id');
        if(empty($id)){
            return json(['code'=>400,'msg'=>'缺少参数消息id']);
        }
        $title = Db::name('member')->where('uid',$uid)->value('nickname');
        $message = Db::name('chat_message')->find($id);
        if($message['msg_type'] == 1){
            $content = $message['content'];
        }elseif($message['msg_type'] == 2){
            $content = '[图片]';
        }else{
            $content = '[语音]';
        }
        $custom = ['chat'=>'message','id' => $message['from_uid']];
        xingePushSingleDeviceNotification($uid, $title, $content, $custom);
    }
}
