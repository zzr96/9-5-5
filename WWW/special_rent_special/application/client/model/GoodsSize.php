<?php
namespace app\client\model;

use think\Model;
class GoodsSize extends Model
{
    //获取商品的全部规格
    public function getSize($goods_id){
        $finds=db('goods_size')->where('goods_id',$goods_id)->field('color,spec')->select();
        $color=[];
        $spec=[];
        foreach ($finds as $key => $value){
            $color[]=$value['color'];
            if(!empty($value['spec'])){
                $spec[]=$value['spec'];
            }
        }
        $color=array_unique($color, SORT_REGULAR);
        $spec=array_unique($spec, SORT_REGULAR);
        if(!empty($color)){
                $data['color']=$color;
        }
        if(!empty($spec)){
                $data['spec']=$spec;
        }
        $data['goods_id']=$goods_id;
        $arr=db('goods')->where('id',$goods_id)->find();
        $data['sn']=$arr['sn'];
        $data['img']=$arr['img1'];
        return $data;
    }
     /**
     * 根据规格获取商品的价格和库存
     * @param array $data 条件
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.26
     * @editor
     * @updated
     * @return arr
     */
    public function goodsSize($data){
        $finds=db('goods_size')->where($data)->field('id,fprice,total,goods_id')->find();
        if($finds['fprice']==0){
            $find=db('goods')->where('id',$finds['goods_id'])->field('amount')->find();
            $price=$find['amount'];
        }else{
           $price=$finds['fprice'];
        }
        $datas['fprice']=$price;
        $datas['total']=$finds['total'];
        $datas['goods_size_id']=$finds['id'];
       if($finds){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$datas]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败','data'=>'']);
        }
    }
}
