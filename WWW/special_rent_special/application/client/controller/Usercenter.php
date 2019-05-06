<?php
namespace app\client\controller;
use app\client\validate\Address;
use app\common\controller\Base;
use app\client\model\Address as AddressModel;
use app\client\model\Collect;
use app\client\model\User;
use think\Exception;
use think\Request;
/**
 * 用户中心接口
 */
class Usercenter extends Base
{
     /**
     * 获取我的收藏
     * @param int $uid 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return
     */
    public function myCollect(){
        $uid=input('uid');
        if(empty($uid)){
             return json(['code'=>400,'msg'=>"缺少用户id"]);
        }
        $list=new Collect();
        $arr=$list->myCollect($uid);
        print_r($arr);
    }
    /**
     * 查看个人资料
     * @param int $uid 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return arr
     */
    public function showUser(){
        $uid=input('uid');
        if(empty($uid)){
             return json(['code'=>400,'msg'=>"缺少用户id"]);
        }
        $list=new User();
        $arr=$list->showUser($uid);
        return $arr;
    }
    /**
     * 修改个人资料
     * @param int $uid 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return arr
     */
    public function editUser(){
        $data = Request::instance()->param();
        $uid=$data['id'];
        $mobile=$data['mobile'];
        //如果手机号在数据库中有  1：别人已经注册了  2自己手机号没改
        $res=db('user')->where('mobile',$mobile)->find();
        if($res){
            if($res['id']==$data['id']){
                $arr=db('user')->where('id',$uid)->update($data);
            }else{
                return json(['code'=>400,'msg'=>"手机号码重复"]);
            }
        }else{
            $arr=db('user')->where('id',$uid)->update($data);
        }
        if($arr){
             return json(['code'=>200,'msg'=>"修改成功"]);
        }else{
            return json(['code'=>400,'msg'=>"修改失败"]);
        }
    }
    /**
     * 我的折扣劵
     * @param int $uid 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return arr
     */
    public function disVolume(){
        $uid=input('uid');
        $type=input('type');
        $time=time();
        if($type){
            if($type==1){
                $arr=db('userVolume')->where("uid=$uid && status=0")->select();
            }
            if($type==2){
                $arr=db('userVolume')->where("uid=$uid && status=1")->select();
            }
            if($type==3){
                $arr=db('userVolume')->where("uid=$uid && time<$time")->select();
            }
        }else{
            $arr=db('userVolume')->where('uid',$uid)->select();
        }
        foreach ($arr as $key => $value) {
            $arr[$key]['time']=date("Y-m-d",$value['time']);
            unset($arr[$key]['uid']);
            unset($arr[$key]['vid']);
            if($time>$value['time']){
                $arr[$key]['is_out']=1;
            }
        }
        if($arr){
             return json(['code'=>200,'msg'=>"成功",'data'=>$arr]);
        }else{
            return json(['code'=>400,'msg'=>"失败"]);
        }
    }


    /**
     * desc 新增地址
     * author 付鹏
     * createDay: 2019/4/28
     * createTime: 15:14
     * param uid int 用户uid
     * param name string 姓名
     * param mobile string 手机号
     * param province string 省
     * param city string 市
     * param area string 区
     * param street string 街道
     * param address string 地址
     * param is_default int 是否默认
     */
    public function addAddress(){
        if(Request::instance()->isPost()){
            $data = Request::instance()->param();
            $validate = new Address();
            if(!$validate->scene('add')->check($data)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            if($data['is_default']){
                AddressModel::startTrans();
                try{
                    AddressModel::where(['uid'=>$data['uid'],'is_default'=>1])->update(['is_default'=>0]);
                    AddressModel::create($data);
                    AddressModel::commit();
                    return json(['code'=>200,'msg'=>'新增成功']);
            }catch (\Exception $e){
                    AddressModel::rollback();
                    return json(['code'=>400,'msg'=>$e->getMessage()]);
                }
            }else{
                AddressModel::create($data);
                return json(['code'=>200,'msg'=>'新增成功']);
            }

        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * 我的地址展示
     * author 付鹏
     * createDay: 2019/4/28
     * createTime: 15:57
     * param uid int 用户uid
     * param id int 用户地址id
     */
    public function getAddress(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            $id = input('id/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            if($id){
                $data = AddressModel::get(['id'=>$id,'uid'=>$uid]);
            }else{
                $data = AddressModel::all(['uid'=>$uid]);
            }
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc 编辑地址
     * author 付鹏
     * createDay: 2019/4/28
     * createTime: 15:14
     * param uid int 用户uid
     * param name string 姓名
     * param mobile string 手机号
     * param province string 省
     * param city string 市
     * param area string 区
     * param street string 街道
     * param address string 地址
     * param is_default int 是否默认
     */
    public function saveAddress(){
        if(Request::instance()->isPost()){
            $data = Request::instance()->param();
            $validate = new Address();
            if(!$validate->check($data)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            if($data['is_default']){
                AddressModel::startTrans();
                try{
                    AddressModel::where(['uid'=>$data['uid'],'is_default'=>1])->update(['is_default'=>0]);
                    AddressModel::where(['id'=>$data['id'],'uid'=>$data['uid']])->update($data);
                    AddressModel::commit();
                    return json(['code'=>200,'msg'=>'修改成功']);
                }catch (\Exception $e){
                    AddressModel::rollback();
                    return json(['code'=>400,'msg'=>$e->getMessage()]);
                }
            }else{
                $addr = AddressModel::get(['id'=>$data['id'],'uid'=>$data['uid']]);
                if($addr->is_default){
                    $data['is_default'] = 1;
                    AddressModel::where(['id'=>$data['id'],'uid'=>$data['uid']])->update($data);
                    return json(['code'=>200,'msg'=>'修改成功']);
                }
                AddressModel::where(['id'=>$data['id'],'uid'=>$data['uid']])->update($data);
                return json(['code'=>200,'msg'=>'修改成功']);
            }

        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc 删除地址
     * author 付鹏
     * createDay: 2019/4/28
     * createTime: 17:22
     * param uid int 用户uid
     * param id int 地址id
     */
    public function delAddress(){
        if(Request::instance()->isPost()){
            $uid = input('uid/d',0);
            $id = input('id/d',0);
            AddressModel::destroy(['id'=>$id,'uid'=>$uid]);
            return json(['code'=>200,'msg'=>'删除成功']);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * 设置默认地址
     * author 付鹏
     * createDay: 2019/4/29
     * createTime: 9:50
     * param uid int 用户uid
     * param id int  地址id
     */
    public function setDefaultAddr(){
        if(Request::instance()->isPost()){
            $id = input('id/d',0);
            $uid = input('uid/d',0);
            try{
                AddressModel::startTrans();
                AddressModel::where(['uid'=>$uid,'is_default'=>1])->update(['is_default'=>0]);
                AddressModel::where(['id'=>$id,'uid'=>$uid])->update(['is_default'=>1]);
                AddressModel::commit();
                return json(['code'=>200,'msg'=>'设置成功']);
            }catch (Exception $e){
                AddressModel::rollback();
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }
}
