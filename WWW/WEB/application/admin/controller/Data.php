<?php
namespace app\admin\controller;

use \tp5er\Backup;

class Data extends Base
{


    protected $beforeActionList = [
        'first',
    ];


    public $config = array(
        'path' => './Data/',//数据库备份路径
        'part' => 20971520,//数据库备份卷大小
        'compress' => 0,//数据库备份文件是否启用压缩 0不压缩 1 压缩
        'level' => 9 //数据库备份文件压缩级别 1普通 4 一般  9最高
    );

    public $file;


    public function first()
    {
        $this->assign("title", "数据备份");
    }


    public function index()
    {

        $db = new Backup($this->config);

        $list = $db->dataList();


        $this->assign("list", $list);


        return view();
    }


    public function backup()
    {

        $db = new Backup($this->config);

        $list = $db->fileList();

        foreach ($list as $k => $v) {
            $list[$k]['name'] = str_replace(array("-", ":", " "), "", $k);
        }

        $list = array_reverse($list);


        //dump($list);
        $this->assign("list", $list);

        return view();
    }

    public function del()
    {

        $id = trim(input("id"), ",");

        $ids = explode(",", $id);

        $db = new Backup($this->config);

        foreach ($ids as $v) {
            $db->delFile($v);
        }


        $this->success("删除成功！");


        //delFile($time)
    }


    public function backup_do()
    {

        $db = new Backup($this->config);
        $file = ['name' => date('Ymd-His'), 'part' => 1];

        // $file= $db->getFile();

        $list = $db->dataList();

        // dump($list);

        foreach ($list as $k => $v) {
            $start = $db->setFile($file)->backup($v['name'], 0);
        }
        $this->success("备份成功！");

    }


    public function huanyuan()
    {


        $time = input('time');

        if (!$time) {
            $this->error('参数错误');
        }

        $db = new Backup($this->config);
        $list = $db->getFile('timeverif', $time);

        //	dump($list);

        $db->setFile($list);


        $re = $db->import(0);

        //dump($re);


        $this->success("数据恢复成功！");


    }


}
