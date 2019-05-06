<?php
namespace app\admin\controller;

class Index extends Base
{

    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '网站概况');
    }

    public function index()
    {

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

        //销售与订单统计
        $data['today_sales'] = 1200;
        $data['today_order_nums'] = 12;
        $data['month_sales'] = 10;
        $data['month_order_nums'] =10;
        
        $this->assign('data', $data);

        return view('index');
    }

}