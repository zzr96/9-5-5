<?php
namespace app\client\model;
use think\Model;
class IntegrateGoods extends Model
{
    /**
     * 获取用户积分和积分商品
     * @param int $id 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return $arr
     */
    public function index($id,$p){
        $p=$p?$p:1;
        $limit=15;
        $count=db('integrate_goods')->where("status=1")->count();
        $count_page = ceil($count/$limit);
        $arr=db('integrate_goods')->where("status=1")->field('id,goods_name,amount,img1,sales')->page($p,$limit)->select();
        $integrate=db('user')->where('id',$id)->find();
        if($arr && $integrate){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$arr,'page'=>$count_page,'integrate'=>$integrate['integrate']]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }
     /**
     *根据积分商品id获取积分详情
     * @param int $id 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return $arr
     */
    public function detail($id){
        $arr=db('integrate_goods')->where('id',$id)->find();
        return $arr;
    }
}
