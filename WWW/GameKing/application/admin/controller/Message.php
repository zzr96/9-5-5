<?php

namespace app\admin\controller;

use think\Db;
use think\Request;
use think\Validate;

class Message extends Base
{
    protected $paginate = 10;
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '系统消息');
    }


    public function index()
    {
        $recentContacts = db('chat_history')
            ->alias('ch')
            ->join('member m', 'ch.other_uid=m.uid')
            ->field('nickname,m.uid,avatar')
            ->where(['ch.uid' => $this->admin['uid']])->select();
        $my_avatar = db('member')->where('uid', $this->admin['uid'])->value('avatar');
        if ($recentContacts) {
            $messageList = Db::name('chat_message')->where(['from_uid' => $this->admin['uid'], 'to_uid' => $recentContacts[0]['uid']])->whereOr(['from_uid' => $recentContacts[0]['uid'], 'to_uid' => $this->admin['uid']])->order('id desc')->paginate(10)->each(function ($item, $key) {
                $nowDay = date('d');
                $msgDay = date('d', $item['sendtime']);
                $weekarray = ["日", "一", "二", "三", "四", "五", "六"];
                if ($msgDay == $nowDay) {
                    $item['sendtime'] = date('H:i', $item['sendtime']);
                } elseif ($msgDay >= ($nowDay - 7)) {
                    $item['sendtime'] = "星期" . $weekarray[date("w", $item['sendtime'])];
                } else {
                    $item['sendtime'] = date('y/m/d', $item['sendtime']);
                }
                return $item;
            });
            $other_avatar = Db::name('member')->where('uid', $recentContacts[0]['uid'])->value('avatar');
            $chatList = $messageList->items();
            krsort($chatList);
            $this->assign('chatList', $chatList);
            $this->assign('other_avatar', $other_avatar);
        }

        $this->assign('user', $this->admin);

        $this->assign('recentContacts', $recentContacts);
        $this->assign('my_avatar', $my_avatar);


        return view();
    }

    public function chatContent()
    {
        $tid = input('tid');
        $chatList = Db::name('chat_message')->where(['from_uid' => $this->admin['uid'], 'to_uid' => $tid])->whereOr(['from_uid' => $tid, 'to_uid' => $this->admin['uid']])->order('id desc')->paginate(10)->each(function ($item, $key) {
            $nowDay = date('d');
            $msgDay = date('d', $item['sendtime']);
            $weekarray = ["日", "一", "二", "三", "四", "五", "六"];
            if ($msgDay == $nowDay) {
                $item['sendtime'] = date('H:i', $item['sendtime']);
            } elseif ($msgDay >= ($nowDay - 7)) {
                $item['sendtime'] = "星期" . $weekarray[date("w", $item['sendtime'])];
            } else {
                $item['sendtime'] = date('y/m/d', $item['sendtime']);
            }
            return $item;
        });
        return json(['code' => 0, 'data' => $chatList]);
    }
    //删除历史消息
    public function delHistory()
    {
        $other_uid = input('other_uid');
        Db::name('chat_history')->where(['uid' => $this->admin['uid'], 'other_uid' => $other_uid])->delete();
        return json(['code' => 0, 'msg' => 'SUCCESS']);
    }
    //获取用户头像
    public function getAvatar()
    {
        $uid = input('uid');
        $userInfo = Db::name('member')->where('uid', $uid)->field('avatar,nickname')->find();
        return json(['code' => 0, 'msg' => 'SUCCESS', 'avatar' => $userInfo['avatar'], 'nickname' => $userInfo['nickname']]);

    }

    /**
     * 消息通知
     */
    public function letter()
    {
        $where = [];
        $data = Db::name('system_message')->where($where)->paginate(10);
        $list = $data->items();
        $page = $data->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        return view();
    }

    //删除历史系统消息
    public function del(){
        $id = input('id');
        if(Db::name('system_message')->where(['id'=>['in',$id]])->delete()){
            return json(['code'=>200,'msg'=>'删除成功']);
        }
        return json(['code'=>400,'msg'=>'删除失败']);

    }


    /**
     * 发送系统通知
     */
    public function sendLetter()
    {

        if ($this->request->isPost()) {
            $param = $this->request->param();
            $param['createtime'] = date('Y-m-d H:i:s');
            db('system_message')->insert($param);
            return json(['code' => 0, 'msg' => '发送成功']);

        }
        return view('sendLetter');

    }

}
