<?php
namespace app\admin\controller;
use think\Db;
use think\Request;

class Index extends Base
{

    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '网站概况');
    }

    /**
     * author 付鹏
     * createDay: 2019/5/6
     * createTime: 14:38
     */
    public function index()
    {
        $shop_id = $this->get_shop_id();
        if(!empty($shop_id)){
            $where['shop_id'] = $shop_id;
            $wheres['shop_id'] = $shop_id;
        }
        $start = strtotime(date('Y-m-d'));
        $end = time();
        $where['dateline'] = ['between',[$start,$end]];
        $where['status'] = ['neq',1];
        $wheres['status'] = ['neq',1];
        //服务器信息
        $server['os'] = php_uname("s");
        $server['os_version'] = php_uname("r");
        $server['php_version'] = PHP_VERSION;
        $server['apache_version'] = $_SERVER['SERVER_SOFTWARE'];
        $server['host'] = $_SERVER["HTTP_HOST"];
        $server['ip'] = GetHostByName($_SERVER['SERVER_NAME']);
        $server['Zend_Version'] = Zend_Version();
        $server['sapi'] = php_sapi_name();
        $this->assign('server', $server);
        //访问情况

        $data = [];
        $todayOrderCount = Db::name('order')->where($where)->count();
        $todayAmountCount = Db::name('order')->where($where)->sum('price');
        $totalOrderCount = Db::name('order')->where($wheres)->count();
        $totalAmountCount = Db::name('order')->where($wheres)->sum('price');
        $this->assign('todayOrderCount',$todayOrderCount);
        $this->assign('totalOrderCount',$totalOrderCount);
        $this->assign('todayAmountCount',$todayAmountCount);
        $this->assign('totalAmountCount',$totalAmountCount);
        return view('index');
    }
    //今日接单信息
    public function tAccOrder(){
        $shop_id = $this->get_shop_id();
        if(!empty($shop_id)){
            $where['shop_id'] = $shop_id;
        }
        $where['status'] = ['neq',1];
        $satrt = strtotime(date('Y-m-d'));
        $end = time();
        $where['dateline'] = ['between',[$satrt,$end]];
        $data = Db::name('order')->where($where)->paginate(15);
        $list = $data->items();
        $page = $data->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return view('viewOrder');
    }
    //历史接单信息
    public function hAccOrder(){
        $start_time = input('start_time');
        $end_time = input('end_time');
        $order_sn = input('order_sn');
        $shop_id = $this->get_shop_id();
        if(!empty($shop_id)){
            $where['shop_id'] = $shop_id;
        }
        if($start_time && $end_time){
            $where['dateline']  = ['between',[strtotime($start_time),strtotime($end_time)]];
        }
        if(!empty($order_sn)){
            $where['order_sn'] = ['like',['%'.$order_sn.'%']];
        }
        $where['status'] = ['neq',1];
        $data = Db::name('order')->where($where)->paginate(15);
        $list = $data->items();
        $page = $data->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('start_time',$start_time);
        $this->assign('end_time',$end_time);
        $this->assign('order_sn',$order_sn);
        return view('viewOrders');
    }

    public function viewOrderDetail(){
        $id = input('id');
        $order = Db::name('order')->where(['id'=>$id])->find();
        $order['goodsInfo'] = Db::name('order_goods')->where(['order_id'=>$order['id']])->select();
        $this->assign('order',$order);
//        dump($order);die;
        return view('orderDetail');
    }

}