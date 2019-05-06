<?php
namespace app\admin\controller;

use think\Db;

class Goods extends Base
{
    protected $beforeActionList = [
        'first',
    ];

    function first(){
        $this->assign("title", "商品管理");
    }

    //商品显示
    function index(){
        $status = input('status');
        $top = input('top');
        $search = input('search');
        $map=[];
        $maps=[];
        if ($status) {
            $map['status'] = $status;
        }
        if ($top) {
            $map['top'] = $top;
        }
         if ($search) {
            $map['goods_name'] = ['like', '%' . $search . '%'];
        }
        $adminid = cookie("adminid");
        if($adminid!=1){
            $shop_id=$this->get_shop_id();
            $map['shop_id']=$shop_id;
        }
        $goods=db('goods')->where($map)->paginate(18, false, ['query' => input()]);
        $list = $goods->items();
        $page = $goods->render();

        //统计商品
        if($adminid==1){
            $total['all']=db('goods')->count();
            $total['on'] = db('goods') ->where('status',1)->count();
            $total['off'] = db('goods')->where('status',2)->count();
            $total['top'] = db('goods')->where('top',2)->count();
        }else{
            $shop_ida=$this->get_shop_id();
            $total['all']=db('goods')->where('shop_id',$shop_ida)->count();
            $total['on'] = db('goods') ->where(['status'=>1,'shop_id'=>$shop_ida])->count();
            $total['off'] = db('goods')->where(['status'=>2,'shop_id'=>$shop_ida])->count();
            $total['top'] = db('goods')->where(['top'=>2,'shop_id'=>$shop_ida])->count();
        }
        $this->assign('total',$total);
        $this->assign('list', $list);
        $this->assign('page', $page);
        return view();
    }

    //商品类别显示
    function cate(){
        $shop_id=$this->get_shop_id();
        $adminid = cookie("adminid");
        if($adminid==1){
            $list = db("good_cate")->where(['fid'=>0])->order("datetime asc")->select();
            foreach ($list as $k => $v){
                $list[$k]['item'] = db("good_cate")->where(["fid"=>$v['id']])->order("datetime asc")->select();
            }
        }else{
            $list = db("good_cate")->where(['fid'=>0,'shop_id'=>$shop_id])->order("datetime asc")->select();
            foreach ($list as $k => $v){
                $list[$k]['item'] = db("good_cate")->where(["fid"=>$v['id'],"shop_id"=>$shop_id])->order("datetime asc")->select();
            }
        }
        $this->assign("list", $list);
        return view();
    }

    //添加类别显示
    function cate_add(){
        $shop_id=$this->get_shop_id();
        $adminid = cookie("adminid");
        if($adminid==1){
            $list = db("good_cate")->where(["fid"=>0])->order("datetime  asc")->select();
        }else{
            $list = db("good_cate")->where(["fid"=>0,"shop_id"=>$shop_id])->order("datetime  asc")->select();
        }
        $this->assign("cat", $list);
        return view();
    }

    //商品分类添加操作
    function cate_add_do(){
        $data = input();
        $shop_id=$this->get_shop_id();
        $data['shop_id']=$shop_id;
        //print_r($data);die;
        if (!$data['name']) {
            $this->error('请填写分类名称！');
        }
        $data['datetime'] =date('Y-m-d h:i:s', time());
        $data['shop_id']=$shop_id;
        $find=db('good_cate')->where(['name'=>$data['name'],'shop_id'=>$shop_id])->find();
        if($find){
            $this->error('分类名称已存在，请稍后更换！');
        }
        $do = db("good_cate")->insert($data);
        if ($do) {
            $this->success("分类添加成功");
        } else {
            $this->error('添加失败，请稍后重试！');
        }
    }
    //编辑商品分类
    function cate_edit(){
         $id=input('id');
        $find=db('good_cate')->where('id',$id)->find();
        $this->assign('find',$find);
        return view();
    }
    //修改分类方法
    function cate_edit_save(){
        $data=input();
        $id=$data['id'];
        $shop_id=$this->get_shop_id();
        $data['shop_id']=$shop_id;
        unset($data['id']);
        if($data['name']==''){
            return json(['code'=>0,'msg'=>'请填写名称','data'=>'']);
        }
        if($data['img']==''){
            return json(['code'=>0,'msg'=>'请上传图片','data'=>'']);
        }
        $data['datetime']=date('Y-m-d H:i:s',time());
        $find=db('good_cate')->where('name',$data['name'])->field('id')->find();
        if(!empty($find)){
            if($find['id']!=$id){
                return json(['code'=>0,'msg'=>'名称不可重复','data'=>'']);
            }
        }
        //print_r($data);die;
        $res=db('good_cate')->where('id',$id)->update($data);
        if($res){
            return json(['code'=>1,'msg'=>'修改成功','data'=>'']);
        }else{
            return json(['code'=>0,'msg'=>'修改失败','data'=>'']);
        }
    }
    //商品分类删除
    function cate_del(){
        $id = input("id");
        $id=ltrim($id,',');
        $arr=explode(',',$id);
        $count=0;
        foreach ($arr as $key => $value){
            $find=db('good_cate')->where('id',$value)->find();
            if($find['fid']==0){
                $f=db('good_cate')->where('fid',$value)->count();
                if($f>0){
                    $count++;
                }
            }
        }
        if($count>0){
             return json(['code'=>0,'msg'=>'请先将主分类下子分类删除!']);
        }
        $map['id'] = array("in", $id);
        $res=db("good_cate")->where($map)->delete();
        if($res){
             return json(['code'=>1,'msg'=>'删除成功!']);
        }else{
             return json(['code'=>1,'msg'=>'删除失败!']);
        }
    }

    //商品添加页面
    function add(){
        $adminid = cookie("adminid");
        $shop_id=$this->get_shop_id();
        if($adminid==1){
            $shop_list=db('shop')->where(['status'=>2])->field('id,shop_name')->select();
        }else{
            $shop_list=db('shop')->where(['status'=>2,'id'=>$shop_id])->field('id,shop_name')->select();
        }
        $shop_cat=db('shop_cat')->where('fid',0)->field('id,name')->select();
        if($adminid==1){
            $goods_cate=db('good_cate')->where(['fid'=>0])->field('id,name')->select();
            // $goods_cate=db('goods_cate')->where('fid',0)->field('id,name')->select();
        }else{
            $goods_cate=db('good_cate')->where(['fid'=>0,'shop_id'=>$shop_id])->field('id,name')->select();
        }
        $this->assign('shop_list',$shop_list);
        $this->assign('shop_cat',$shop_cat);
        $this->assign('goods_cate',$goods_cate);
        return view();
    }

    //商品添加方法
    function add_do(){
        $data = input();
        $find = db('goods')->where("goods_name='" . $data['goods_name'] . "'")->find();
        if($find){
            return json(['code'=>0,'msg'=>'商品名已存在!']);
        }
        $color = $data['color'];
        $spec = $data['spec'];
        $fprice = $data['fprice'];
        $total = $data['total'];
        unset($data['spec']);
        unset($data['color']);
        unset($data['fprice']);
        unset($data['total']);
        $data['dateline']=time();
        $isin = in_array("无",$color);
        if($isin){
            $data['is_xn']=2;
        }
        // if($data['shop_id']!=0){
        $shop=db('shop')->where('id',$data['shop_id'])->field('shop_name')->find();
        $data['shop_name']=$shop['shop_name'];
        $goods_id = db('goods')->insertGetId($data);//插入goods数据表
        foreach ($color as $key => $value) {
            $gsize['spec'] = $spec[$key];
            $gsize['color'] = $color[$key];
            $gsize['fprice'] = $fprice[$key];
            $gsize['total'] = $total[$key];
            $gsize['good_id'] = $goods_id;
            $gsize['dateline'] = date("Y-m-d H:i:s",time());
            $res = db('goods_size')->insert($gsize);//插入goods_size 数据表
        }
        if($res){
            return json(['code'=>1,'msg'=>'添加成功！']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败！']);
        }
    }

    //获取商城子分类
    function shop_cat(){
        $id=input('id');
        $info = db('shop_cat')->where('fid',$id)->field('id,name')->select();
        $data = '';
        $data .=  '<option value="">请选择</option>';
        foreach ($info as $v){
          $data .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
        }
        exit($data);
    }

    //获取商品子分类
    function goods_cat(){
        $id=input('id');
        $shop_id=$this->get_shop_id();
        $adminid = cookie("adminid");
        if($adminid==1){
            $info = db('good_cate')->where('fid',$id)->field('id,name')->select();
        }else{
            $info = db('good_cate')->where(['fid'=>$id,'shop_id'=>$shop_id])->field('id,name')->select();
        }
        $data = '';
        $data .=  '<option value="">请选择</option>';
        foreach ($info as $v){
          $data .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
        }
        exit($data);
    }


     //修改商品页面
    function edit(){
        $id=input('id');
        $find=db('goods')->where('id',$id)->find();
        $res=db('goods_size')->where('good_id',$id)->select();
        $shop_cat=db('shop_cat')->where('fid',0)->field('id,name')->select();
        $shop_cat1=db('shop_cat')->where('id',$find['zcate_id'])->field('id,name')->find();
        $shop_id=$this->get_shop_id();
        $adminid = cookie("adminid");
        if($adminid==1){
            $goods_cate=db('good_cate')->where('fid',0)->field('id,name')->select();
            $goods_cate1=db('good_cate')->where('id',$find['zsize_id'])->field('id,name')->find();
            $shop_list=db('shop')->where('status',2)->field('id,shop_name')->select();
        }else{
            $goods_cate=db('good_cate')->where(['fid'=>0,'shop_id'=>$shop_id])->field('id,name')->select();
            $goods_cate1=db('good_cate')->where(['id'=>$find['zsize_id'],'shop_id'=>$shop_id])->field('id,name')->find();
            $shop_list=db('shop')->where(['status'=>2,'id'=>$shop_id])->field('id,shop_name')->select();
        }
        $this->assign('shop_list',$shop_list);
        $this->assign('res',$res);
        $this->assign('find',$find);
        $this->assign('shop_cat',$shop_cat);
        $this->assign('shop_cat1',$shop_cat1);
        $this->assign('goods_cate1',$goods_cate1);
        $this->assign('goods_cate',$goods_cate);
        return view();
    }

    //修改商品
    function edit_do(){
        $data = input();
        $id=$data['id'];
        unset($data['id']);
        $find = db('goods')->where("goods_name='" . $data['goods_name'] . "'")->field('id')->find();
        if($find){
            if($find['id']!=$id){
                return json(['code'=>0,'msg'=>'商品名已存在!']);
            }
        }
        $color = $data['color'];
        $spec = $data['spec'];
        $fprice = $data['fprice'];
        $total = $data['total'];
        unset($data['spec']);
        unset($data['color']);
        unset($data['fprice']);
        unset($data['total']);
        $data['dateline']=time();
        $shop=db('shop')->where('id',$data['shop_id'])->field('shop_name')->find();
        $data['shop_name']=$shop['shop_name'];
        db('goods_size')->where('good_id',$id)->delete();
        $goods_id = db('goods')->where('id',$id)->update($data);//插入goods数据表
        foreach ($color as $key => $value){
            $gsize['spec'] = $spec[$key];
            $gsize['color'] = $color[$key];
            $gsize['fprice'] = $fprice[$key];
            $gsize['total'] = $total[$key];
            $gsize['good_id'] = $id;
            $gsize['dateline'] = date("Y-m-d H:i:s",time());
            $res = db('goods_size')->insert($gsize);//插入goods_size 数据表
        }
        if($res){
            return json(['code'=>1,'msg'=>'修改成功！']);
        }else{
            return json(['code'=>0,'msg'=>'修改失败！']);
        }
    }

    //商品删除
    function del(){
        $id = input("id");
        $map['id'] = array("in", $id);
        $maps['good_id']=array("in",$id);
        $res = db('goods')->where($map)->delete();
        db('goods_size')->where($maps)->delete();
        if ($res) {
            $this->result('', 1, "操作成功");
        } else {
            $this->result('', 0, "操作失败");
        }
    }

    //商品上架
    function upper_shelf(){
        $id = input("id");
        $map['id'] = array("in", $id);
        $data['status'] = 1;
        db('goods')->where($map)->update($data);
        $this->result('',1,'操作成功');
    }

    //商品下架
    function lower_shelf(){
        $id = input("id");
        $map['id'] = array("in", $id);
        $data['status'] = 2;
        db('goods')->where($map)->update($data);
        $this->result('',1,'操作成功');
    }

    //商品置顶
    public function topon(){
        $id = input("id");
        $map['id'] = array("in", $id);
        db('goods')->where($map)->setField('top', 2);
        $this->result('',1,'操作成功');
    }

    //取消置顶
    public function topoff(){
        $id = input("id");
        $map['id'] = array("in", $id);
        db('goods')->where($map)->setField('top', 1);
        $this->result('',1,'操作成功');
    }
}
