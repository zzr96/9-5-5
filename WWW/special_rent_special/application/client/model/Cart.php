<?php
namespace app\client\model;
use think\Model;

class Cart extends Model
{


    /**
     * 我的购物车
     * @param int $uid 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.26
     * @editor
     * @updated
     * @return arr
     */
    public function myCart($uid){
        $arr=db('cart')->where('uid',$uid)->field('id,goods_size_id,goods_name,goods_img,spec,color,num,amount,goods_id')->select();
        if($arr){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$arr]);
        }else{
            return json(['code'=>200,'msg'=>'获取失败','data'=>'']);
        }
    }
     /**
     * 删除购物车
     * @param int $ids 购物车ids  12,13,14
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.26
     * @editor
     * @updated
     * @return
     */
    public  function delCar($ids){
        $res=db('cart')->where('id','in',$ids)->delete();
        if($res){
            return json(['code'=>200,'msg'=>'成功']);
        }else{
            return json(['code'=>200,'msg'=>'失败']);
        }
    }
     /**
     * 删除购物车
     * @param int $id 购物车id
     * @param int $type  1：加 2：减
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.26
     * @editor
     * @updated
     * @return num 购物车商品数量
     */
    public function change_num($id,$type){
        if($type==1){
            $data=db('cart')->where('id',$id)->setInc('num',1);
            if($data){
                $arr=db('cart')->where('id',$id)->find();
                if($arr){
                    return json(['code'=>200,'msg'=>'成功','num'=>$arr['num']]);
                }else{
                    return json(['code'=>200,'msg'=>'失败']);
                }
            }else{
                 return json(['code'=>200,'msg'=>'失败']);
            }
        }else{
            $data=db('cart')->where('id',$id)->setDec('num',1);
            if($data){
                $arr=db('cart')->where('id',$id)->find();
                if($arr){
                    return json(['code'=>200,'msg'=>'成功','num'=>$arr['num']]);
                }else{
                    return json(['code'=>200,'msg'=>'失败']);
                }
            }else{
                 return json(['code'=>200,'msg'=>'失败']);
            }
        }
    }
     /**
     *加入购物车
     * @param int $num  商品数量
     * @param int $uid  用户id
     * @param int $goods_size_id  规格值id
     * @param int $good_id  规格值id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.26
     * @editor
     * @updated
     * @return msg
     */
    public function addCart($uid,$num,$goods_size_id,$goods_id){
        $find=db('cart')->where("goods_size_id=$goods_size_id")->field('num')->find();
        if($find){
            $new_num=$find['num']+$num;
            $res=db('cart')->where('goods_size_id',$goods_size_id)->update(['num'=>$new_num]);
        }else{
            $data['uid']=$uid;
            $data['num']=$num;
            $data['goods_size_id']=$goods_size_id;
            $data['goods_id']=$goods_id;
            $goods_size=db('goods_size')->where('id',$goods_size_id)->field('color,spec,fprice,total')->find();
            $data['color']=$goods_size['color'];
            $data['spec']=$goods_size['spec'];
            if($goods_size){
                $goods=db('goods')->where('id',$goods_id)->field('img1,goods_name,amount')->find();
                if($goods_size['fprice']==0){
                    $data['amount']=$goods['amount'];
                }else{
                    $data['amount']=$goods_size['fprice'];
                }
                $data['goods_name']=$goods['goods_name'];
                $data['goods_img']=$goods['img1'];
            }else{
                return json(['code'=>200,'msg'=>'获取商品信息失败']);
            }
            $data['date']=date('Y-m-d H:i:s');
            $res=db('cart')->insert($data);
        }
        if($res){
            return json(['code'=>200,'msg'=>'购物车添加成功']);
        }else{
            return json(['code'=>400,'msg'=>'购物车添加失败']);
        }
    }
}