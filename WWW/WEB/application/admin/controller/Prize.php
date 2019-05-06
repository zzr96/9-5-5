<?php
namespace app\admin\controller;

use think\Db;

class Prize extends Base
{
	protected $beforeActionList = [
        'first',
    ];

     public function first()
    {
        $this->assign("title", "奖品管理");
    }

    //奖品列表
    function index(){
        $res=db('gift')->select();
        $this->assign('res',$res);
        return view();
    }

    //添加奖品页面
    function add(){
        return view();
    }

    //添加方法
    function addsave(){
        $data=input('');
        $res=db('gift')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'添加成功']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败']);
        }
    }
    //修改奖品列表
    function edit(){
        $data = db('gift')->where('id=' . input('id'))->find();
        $this->assign("data", $data);
        return view();
    }

    //修改方法
    function editsave(){
        $data = input('post.', '', 'trim');
        if (!$data['img']) {
            $this->result('', 0, '图片路径不能为空');
        }
        $id = $data['id'];
        unset($data['id']);
        $res = db('gift')->where('id=' . $id)->update($data);
        if ($res) {
            $this->result('', 1, '修改成功');
        } else {
            $this->result('', 0, '修改失败');
        }
    }

    //删除奖品列表
    function del(){
        $id=input('id');
        $find=db('prize')->where('gift_id',$id)->find();
        if($find){
            return json(['code'=>0,'msg'=>'此奖品正在使用无法删除']);
        }
        $res=db('gift')->where('id',$id)->delete();
        if($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }

    //转盘列表
    function turntable(){
        $res=db('prize')->select();
        foreach($res as $key=>$value){
            if($value['gift_id']==0){
                $res[$key]['name']='无奖品';
            }else{
                $find=db('gift')->where('id',$value['gift_id'])->field('name')->find();
                $res[$key]['name']=$find['name'];
            }
        }
        $this->assign('res',$res);
        return view();
    }

    //删除转盘
    function prize_del(){
        $id=input('id');
        $res=db('prize')->where('id',$id)->delete();
        if($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }

    //添加转盘列表
    function prize_add(){
        $res=db('gift')->field('id,name')->select();
        $this->assign('res',$res);
        return view();
    }

    //添加转盘
    function prize_add_save(){
        $data=input('');
        $res=db('prize')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'添加成功']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败']);
        }
    }

    //转盘修改
    function prize_edit(){
        $data = db('prize')->where('id=' . input('id'))->find();
        $res=db('gift')->field('id,name')->select();
        $this->assign('res',$res);
        $this->assign("data", $data);
        return view();
    }

    //转盘修改方法
    function prize_edit_save(){
        $data = input('post.', '', 'trim');
        $id = $data['id'];
        unset($data['id']);
        $res = db('prize')->where('id=' . $id)->update($data);
        if ($res) {
            $this->result('', 1, '修改成功');
        } else {
            $this->result('', 0, '修改失败');
        }
    }

    //排行奖励
    function ranking(){
        $res=db('ranking')->select();
        foreach($res as $key=>$value){
            if($value['uid']){
                $find=db('user')->where('id',$value['uid'])->field('mobile')->find();
                $res[$key]['mobile']=$find['mobile'];
            }else{
                $res[$key]['mobile']='暂无人中奖';
            }
        }
        $this->assign('res',$res);
        return view();
    }

    //排行奖励添加
    function ranking_add(){
        return view();
    }

    //添加操作
    function ranking_addsave(){
        $data=input('');
        $find=db('ranking')->where(['type'=>$data['type'],'uid'=>['<>',null]])->find();
        if($find){
            return json(['code'=>0,'msg'=>'尚有奖品未结束']);
        }
        $data['dateline']=date('Y-m-d H:i:s',time());
        $res=db('ranking')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'添加成功']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败']);
        }
    }

    
    //删除排行奖品
    function ranking_del(){
        $id=input('id');
        $res=db('ranking')->where('id',$id)->delete();
        if($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }

    //排行奖品修改
    function ranking_edit(){
        $data = db('ranking')->where('id=' . input('id'))->find();
        $this->assign("data", $data);
        return view();
    }

    //排行奖品修改方法
    function ranking_editsave(){
        $data = input('post.', '', 'trim');
        $id = $data['id'];
        unset($data['id']);
        $res = db('ranking')->where('id=' . $id)->update($data);
        if ($res) {
            $this->result('', 1, '修改成功');
        } else {
            $this->result('', 0, '修改失败');
        }
    }

}