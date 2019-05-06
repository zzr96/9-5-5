<?php

namespace app\apis\controller;

use think\Controller;

/**
 * 早起打卡
 */
class Clock extends Controller
{
    //支付成功后加入打卡表 $type  1 滴水穿石 2 如履薄冰 3 歃血为盟
    function addClock($type)
    {
        $uid = input('uid');
        if (empty($uid)) {
            return json(['code' => 101, 'msg' => '缺少参数uid', 'data' => '']);
        }
        $where['uid'] = $uid;
        $where['type'] = $type;
        $where['status'] = 1;
        $res = db('user_clock_pay')->where($where)->find();
        if ($res) {
            return json(['code' => 2, 'msg' => '已支付', 'data' => '']);
        } else {
            $data['uid'] = $uid;
            $data['addtime'] = time();
            $data['status'] = 1;
            $data['type'] = $type;
            $data['times'] = strtotime(date('Y-m-d', time()));
            $re = db('user_clock_pay')->insertGetId($data);
            if ($re) {
                return json(['code' => 1, 'msg' => '支付成功', 'data' => '']);
            } else {
                return json(['code' => 2, 'msg' => '失败', 'data' => '']);
            }
        }

    }

    //早起打卡首页接口
    function clocks()
    {
        $uid = input('uid');
        $type = input('type');  // $type 1 滴水穿石 2 如履薄冰 3 歃血为盟
        // if (empty($uid)) {
        //     return json(['code' => 101, 'msg' => '缺少参数uid', 'data' => '']);
        // }
        $this->upClocks($uid);
        $data = $this->clock($uid,$type);
        if($type==1){
            $data['pay_money']=10;
        }elseif($type==2){
            $data['pay_money']=100;
        }else{
            $data['pay_money']=1000;
        }
        return json(['code' => 200, 'msg' => '请求成功', 'data' => $data]);
    }


    function clock($uid,$type)
    {   
        $data = array();//页面数组数据
        $time = $this->istime(); // 1 未到签到时间 2 签到时间已过 3 可以签到
        $list = $this->iClockd($uid, $type, 1);
        if ($list){
            if ($time == 1) {
                $data['qstatus'] = 1;//显示打卡倒计时
                $data['qtimes'] = $this->mtimes(1);  //倒计时时间戳
            } elseif ($time == 2) {
                $data['qstatus'] = 2;//显示可以打卡
            } elseif ($time == 3) {
                $data['qstatus'] = 1;//显示打卡倒计时 此状态为  如履薄冰  歃血为盟 打卡进行中状态
                $data['qtimes'] = $this->mtimes(2);  //倒计时时间戳
            }
        }else{ // 未参与今天打卡活动或今天打卡超时
            $lists = $this->iClockd($uid,$type,2);// 参与明天打卡活动
            if ($lists) {
                $data['qstatus'] = 1;//显示打卡倒计时
                $data['qtimes'] = $this->mtimes(2);  //倒计时时间戳
            }else{
                $data['qstatus'] = 3;//显示参与活动按钮
            }
        }
        $data['type'] = $type; //1 滴水穿石 2 如履薄冰 3 歃血为盟
        $datas = $this->getMoney($type, $data['qstatus'], $uid); //获取参与活动金额,活动人数，昨天人数
        $data['money'] = $datas['money']; //参与活动金额
        // $data['pnums'] = $datas['pnums']; //参与活动人数
        $data['pnums'] = 0; //参与活动人数
        $data['ypnums'] = $datas['ypnums'];//昨天人数
        $data['dnums'] = $this->getDnums($uid, $type); //获取连续签到天数
        return $data;
    }

    //获取参与活动金额,活动人数,昨天人数
    function getMoney($type, $qstatus, $uid)
    {
        $where['type'] = $type;
        $where['status'] = 1;
        $num = db('clock')->where($where)->find();
        $nums = '';
        if ($num){
            $nums = $num['sunum'] + $num['finum'];
        }
        if ($type == 1) {
            $wheres['times'] = strtotime(date('Y-m-d', time()));
        } else {
            if ($qstatus == 3) {
                $wheres['times'] = strtotime(date('Y-m-d', time()));
            } else {
                $where['uid'] = $uid;
                $times = db('user_clock_pay')->where($where)->value('times');
                $wheres['times'] = $times;
            }
        }

        $wheres['type'] = $type;
        $count = db('user_clock_pay')->where($wheres)->count();
        $counts = $count + $nums;
        if ($type == 1) {
            $moneys = 10;
        } elseif ($type == 2) {
            $moneys = 100;
        } else {
            $moneys = 1000;
        }
        $money = $counts * $moneys;
        $data['money'] = $money;
        $data['pnums'] = $counts;
        $wheres['times'] = strtotime(date('Y-m-d', time())) - 3600 * 24;
        $countd = db('user_clock_pay')->where($wheres)->count();
        $data['ypnums'] = $countd + $nums;
        return $data;
    }

    //获取参与活动金额,活动人数,昨天人数
    function getDnums($uid, $type)
    {
        $ntoday = strtotime(date('Y-m-d', time()));
        if ($type == 1) {
            $where['uid'] = $uid;
            $where['nday'] = $ntoday;
            $list = db('user_clock')->where($where)->find();
            if (empty($list)) {
                $where['nday'] = $ntoday - 3600 * 24;
                $list = db('user_clock')->where($where)->find();
            }
        } else {
            $where['uid'] = $uid;
            $where['type'] = $type;
            $where['status'] = 1;
            $list = db('user_clock_pay')->where($where)->find();
        }
        if ($list) {
            $data = $list['cnums'];
        } else {
            $data = 0;
        }
        return $data;
    }

    //更改签到状态
    function upClocks($uid)
    {
        $re = $this->istime();
        if ($re == 2) {
            $res = $this->isClockd($uid, 1);
            if ($res == 2) {
                $where['uid'] = $uid;
                $where['status'] = 1;
                $ntoday = strtotime(date('Y-m-d', time()));
                $where['addtime'] = array('lt', $ntoday);

                $data['status'] = 3;
                $data['uptime'] = time();
                db('user_clock_pay')->where($where)->setField($data);
            }
        }
    }

    //判断时间
    function istime()
    {
        $ntime = time();
        $times = db('clock')->where('type', 4)->find();
        $ntoday = date('Y-m-d', $ntime);
        $starttime = date('H:i', $times['starttime']);
        $endtime = date('H:i', $times['endtime']);
        $starttime = strtotime($ntoday . $starttime . ":00");//今天签到开始时间戳
        $endtime = strtotime($ntoday . $endtime . ":00");//今天签到结束时间戳
        if ($ntime < $starttime) {
            return 1;
        } else if ($ntime > $endtime) {
            return 2;
        } else {
            return 3;
        }
    }


    //时间差 $type 1 到今天打卡时间差 2 到明天打卡时间差
    function mtimes($type)
    {
        $ntime = time();
        $times = db('clock')->where('type', 4)->find();
        $ntoday = date('Y-m-d', $ntime);
        $starttime = date('H:i', $times['starttime']);
        $starttime = strtotime($ntoday . $starttime . ":00");//今天签到开始时间戳
        if ($type == 1) {
            $times = $starttime - $ntime;
        } else {
            $times = $starttime + 3600 * 24 - $ntime;
        }
        return $times;
    }

    //$types 1 检测今天是否签到 2 检测昨天是否签到
    function isClockd($uid, $types)
    {
        $ntime = time();
        $ntoday = strtotime(date('Y-m-d', $ntime));
        if ($types == 1) {
            $where['nday'] = $ntoday;
        } else {
            $where['nday'] = $ntoday - 3600 * 24;
        }
        $where['uid'] = $uid;

        $re = db('user_clock')->where($where)->find();
        if ($re) {
            if ($types == 2) {
                return $re;
            } else {
                return 1;
            }
        } else {
            return 2;
        }
    }

    //$types 1 检测昨天是否参加签到 2 检测今天是否参加签到
    function iClockd($uid, $type, $types, $typed = '')
    {
        $ntoday = strtotime(date('Y-m-d', time()));
        if ($types == 1) {
            $where['addtime'] = array('lt', $ntoday);
        } else {
            $where['addtime'] = array('egt', $ntoday);
        }
        $where['uid'] = $uid;
        $where['status'] = 1;
        if (empty($typed)) {
            $where['type'] = $type;
        }
        $list = db('user_clock_pay')->where($where)->find();// 参与今天打卡活动
        return $list;
    }

    //签到打卡操作
    function hanClockd()
    {
        $uid = input('uid');
        if (empty($uid)) {
            return json(['code' => 101, 'msg' => '缺少参数uid', 'data' => '']);
        }
        $rtime = $this->istime(); //检测签到时间
        if ($rtime == 1) {
            return json(['code' => 102, 'msg' => '打卡未开始', 'data' => '']);
        } elseif ($rtime == 2) {
            return json(['code' => 103, 'msg' => '打卡时间已过', 'data' => '']);
        }
        $rclock = $this->isClockd($uid, 1);//检测今天是否打卡

        if ($rclock == 1) {
            return json(['code' => 104, 'msg' => '今日已打卡', 'data' => '']);
        }

        //检测昨天是否参加活动
        $where['uid'] = $uid;
        $where['status'] = 1;
        $ntoday = strtotime(date('Y-m-d', time()));
        $where['addtime'] = array('lt', $ntoday);
        $list = db('user_clock_pay')->where($where)->select();
        if (empty($list)) {
            return json(['code' => 105, 'msg' => '昨日未参与今天打卡活动', 'data' => '']);
        } else {
            $data['uid'] = $uid;
            $data['addtime'] = time();
            $data['nday'] = $ntoday;
            $days = $this->isClockd($uid, 2);//检测昨天是否签到 并获取签到天数
            if ($days == 2) {
                $data['cnums'] = 1;
            } else {
                $data['cnums'] = $days['cnums'] + 1;
            }
            $re = db('user_clock')->insertGetId($data);
            if ($re) {
                $resp = '';
                foreach ($list as $k => $v) {
                    $nums = $v['nums'] + 1;
                    $dats['uptime'] = time();
                    $dats['nums'] = $nums;
                    if ($v['type'] == 1) {
                        $dats['status'] = 2;
                    } elseif ($v['type'] == 2) {
                        if ($nums == 7) {
                            $dats['status'] = 2;
                        } else {
                            $dats['status'] = 1;
                        }
                    } elseif ($v['type'] == 3) {
                        if ($nums == 21) {
                            $dats['status'] = 2;
                        } else {
                            $dats['status'] = 1;
                        }
                    }
                    $red = db('user_clock_pay')->where('id', $v['id'])->setField($dats);
                    if ($red) {
                        $datsd['uid'] = $uid;
                        $datsd['addtime'] = time();
                        $datsd['ucid'] = $re;
                        $datsd['uhid'] = $v['id'];
                        $datsd['nums'] = $nums;
                        $datsd['type'] = $v['type'];
                        $resp = db('user_clock_log')->insertGetId($datsd);
                    }
                }
                if ($resp) {
                    return json(['code' => 200, 'msg' => '打卡成功', 'data' => '']);
                } else {
                    return json(['code' => 106, 'msg' => '打卡失败', 'data' => '']);
                }

            }
        }
    }

    //我的战绩 $type 1 滴水穿石 2 如履薄冰 3 歃血为盟
    public function mploits()
    {
        $uid = input('uid');
        $type = input('type');
        if(empty($uid)){
            return json(['code' => 101, 'msg' => '缺少参数uid', 'data' => '']);
        }
        $data = $this->getMploits($uid, $type);
        return json(['code' =>200, 'msg' => '请求成功', 'data' => $data]);
    }

    public function getMploits($uid, $type)
    {
        $data = array();
        $data['cmoney'] = $this->getCmoney($uid, $type);//获取累计投入金额
        $data['qmoney'] = 0;//获取累计赚钱暂缺
        $data['dnums'] = $this->getDnums($uid, $type); //获取连续签到天数
        $data['gmoney']=0;//今日瓜分金额
        $data['pay_money']=1;
        return $data;
    }

    //获取累计投入金额
    public function getCmoney($uid, $type)
    {
        $where['uid'] = $uid;
        $where['type'] = $type;
        $count = db('user_clock_pay')->where($where)->count();
        $money = pow(10, $type);
        $cmoney = $count * $money;
        return $cmoney;
    }

    //打卡记录  $type 1 滴水穿石 2 如履薄冰 3 歃血为盟
    public function clockLogs()
    {
        $uid = input('uid');
        $type = input('type');
        if (empty($uid)) {
            return json(['code' => 101, 'msg' => '缺少参数uid', 'data' => '']);
        }
        $data = $this->getClogs($uid, $type);
        return json(['code' => 200, 'msg' => '请求成功', 'data' => $data]);
    }

    public function getClogs($uid, $type)
    {
        $where['uid'] = $uid;
        $where['type'] = $type;
        $list = db('user_clock_pay')->where($where)->select();
        $data =array();
        foreach ($list as $k => $v){
            $data[$k]['times'] =date('Y.m.d',$v['times']);
            $data[$k]['status'] =$this->getStatus($v['status']);
            $data[$k]['status_y']=$v['status'];
            $data[$k]['money'] =2;   //奖励金额待定
        }
        return $data;
    }

    public function getStatus($status)
    {
        $data = [
            '1'    => '打卡中',
            '2'    => '打卡成功',
            '3' => '打卡失败',
        ];
        return $data[$status];
    }

    //活动规则
    function rule_activity(){
        $type=input('type');
        $res=db('rule_activity')->where('type',$type)->field('content')->find();
        if($res){
            return json(['code' =>200, 'msg' => '请求成功', 'data' => $res]);
        }else{
            return json(['code' =>100, 'msg' => '暂无数据', 'data' =>'']);
        }
    }

    //点击领取签到金额
    function receive_reward(){
        $uid=input('uid');
        if(empty($uid)){
            return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
        }
        $price=input('price');
        if(empty($peice) && $price<0){
            return json(['code'=>101,'msg'=>'系统错误','data'=>'']);
        }
        $user=db('user')->where('id',$uid)->field('money')->find();
        $new_money=$user['money']+$price;
        $new_money=sprintf("%.2f",$new_money);
        $res=db('user')->where('id',$uid)->update(['money'=>$new_money]);
        if($res){
            return json(['code'=>200,'msg'=>'金额领取成功','data'=>'']);
        }else{
            return json(['code'=>100,'msg'=>'领取失败','data'=>'']);
        }
    }

    //打卡战况
    function  war_situation(){
        $type=input('type');    //1.滴水穿石 2 如履薄冰 3 歃血为盟
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        //幸运之星
        $goods_luck=db('user_clock_pay')->where(['uptime'=>['between time',[$beginToday,$endToday]]])->order('reward_money desc')->field('uid,reward_money')->limit(10)->select();
        foreach($goods_luck as $key=>$value){
            $user=db('user')->where('id',$value['uid'])->field('logo,nickname')->find();
            $goods_luck[$key]['logo']=$user['logo'];
            $goods_luck[$key]['nickname']=$user['nickname'];
        }
        // //早起之星
        $goods_zq=db('user_clock_pay')->where(['uptime'=>['between time',[$beginToday,$endToday]]])->order('uptime asc')->field('uid,uptime')->limit(10)->select();
        foreach($goods_zq as $key=>$value){
            $user=db('user')->where('id',$value['uid'])->field('logo,nickname')->find();
            $goods_zq[$key]['logo']=$user['logo'];
            $goods_zq[$key]['nickname']=$user['nickname'];
            $goods_zq[$key]['uptime']=date('H:i:s',$value['uptime']);
        }
        $user_clock_nums=db('user_clock')->order('cnums desc')->field('uid,cnums')->limit(10)->select();
        foreach($user_clock_nums as $key=>$value){
            $user=db('user')->where('id',$value['uid'])->field('logo,nickname')->find();
            $user_clock_nums[$key]['logo']=$user['logo'];
            $user_clock_nums[$key]['nickname']=$user['nickname'];
        }
        $res=[
            'cg'=>0,
            'sb'=>0,
            'goods_luck'=>$goods_luck,
            'goods_zq'=>$goods_zq,
            'user_clock_nums'=>$user_clock_nums,
        ];
        return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);

    }


}

