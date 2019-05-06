<?php
namespace app\apis\controller;

use think\Controller;
use app\apis\validate\AddressNew;
use think\Db;
use think\Request;

class Address extends Controller
{
	/**
     * get: 获取用户地址
     * path: getUserAddress
     * method: getUserAddress
     */
	function getUserAddress(){
//		$uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
		$res=Db::name('address')->where('uid',$uid)->select();
		if($res){
			return json(['code'=>200,'msg'=>'SUCCESS','data'=>$res]);
		}
		return json(['code'=>400,'msg'=>'暂无地址,请及时添加！']);
	}

    /**
     * post: 新增用户地址
     * path: createUserAddress
     * method: createUserAddress
     * param: uname - {string} 姓名
     * param: tel - {string} 手机号
     * param: province - {int} 省
     * param: city - {int} 市
     * param: area - {int} 区
     * param: address - {string} 详细地址
     * param: is_default - {int} 是否默认0否1是
     */
    public function createUserAddress()
    {
    	if(Request::instance()->isGet()){
            $uid = input('uid');
            if(empty($uid)){
                return json(['code'=>400,'msg'=>'缺少用户uid']);
            }
            $user = Db::name('member')->where(['uid'=>$uid])->find();
            if(empty($user)){
                return json(['code'=>400,'msg'=>'用户不存在']);
            }
	    	$params = Request::instance()->param();
	        $validate = new AddressNew();
	      	if(!$validate->check($params)) {
	        	return json(['code'=>400,'msg'=>$validate->getError()]);
	        }
	        $params['uid'] = $uid;
	        $params['createtime'] = date('Y-m-d H:i:s');
	        if($params['is_default'] && $params['is_default'] == 1){
	        	Db::name('address')->where(['uid'=>$uid,'is_default'=>1])->update(['is_default'=>0]);
	        }
			$res=Db::name('address')->insertGetId($params);
			if($res){
				return json(['code'=>200,'msg'=>'添加成功','data'=>$res]);
			}
			return json(['code'=>400,'msg'=>'添加失败，请重试']);
    	}
    	return json(['code'=>400,'msg'=>'请求类型错误']);

    }

    /**
     * post: 设置用户默认地址
     * path: setUserAddrDefault
     * method: setUserAddrDefault
     * param: id - {int} 地址id
     */
	function setUserAddrDefault(){
//		 $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
		 $id = input('id',0);
        Db::startTrans();
        try {
		 	Db::name('address')->where(['uid'=>$uid,'is_default'=>1])->update(['is_default'=>0]);
		 	Db::name('address')->where(['id'=>$id,'uid'=>$uid,'is_default'=>0])->update(['is_default'=>1]);
            Db::commit();
		 	return json(['code'=>200,'msg'=>'设置成功！']);
        } catch (\Exception $e) {
            Db::rollback();
            return json(['code' => 1, 'msg' => $e->getMessage()]);	
        }

	}
	/**
     * post: 根据id删除用户地址
     * path: delUserAddress
     * method: delUserAddress
     * param: id - {int} 地址id
     */
	function delUserAddress(){
//		$uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
		$id = input('id',0);
		$res=Db::name('address')->where(['id'=>$id,'uid'=>$uid])->delete();
		if($res){
			return json(['code'=>200,'msg'=>'删除成功']);
		}else{
			return json(['code'=>400,'msg'=>'删除失败']);
		}
	}
    /**
     * post: 更新户地址
     * path: updateUserAddress
     * method: updateUserAddress
     * param: id - {int} 地址id
     * param: uname - {string} 姓名
     * param: tel - {string} 手机号
     * param: province - {int} 省
     * param: city - {int} 市
     * param: area - {int} 区
     * param: address - {string} 详细地址
     * param: is_default - {int} 是否默认0否1是
     */
	function updateUserAddress(){
    	if(Request::instance()->isPost()){
            $uid = input('uid');
            if(empty($uid)){
                return json(['code'=>400,'msg'=>'缺少用户uid']);
            }
            $user = Db::name('member')->where(['uid'=>$uid])->find();
            if(empty($user)){
                return json(['code'=>400,'msg'=>'用户不存在']);
            }
	    	$params = Request::instance()->param();
	    	if(!isset($params['id'])){
	    		return json(['code'=>400,'msg'=>'缺少必要参数id']);
	    	}
	        $validate = new AddressNew();
	      	if(!$validate->check($params)) {
	        	return json(['code'=>400,'msg'=>$validate->getError()]);
	        }
//	        $uid = $this->uid;
	        $oldaddr = Db::name('address')->where(['id'=>$params['id'],'uid'=>$uid])->find();
	        if(empty($oldaddr)){
	        	return json(['code'=>400,'msg'=>'修改的地址不存在']);
	        }
	        if($params['is_default'] && $params['is_default'] == 1){
	        	Db::name('address')->where(['uid'=>$uid,'is_default'=>1])->update(['is_default'=>0]);
	        }
			$res=Db::name('address')->update($params);
			if($res !== false){
				return json(['code'=>200,'msg'=>'修改成功']);
			}
			return json(['code'=>400,'msg'=>'修改失败，请重试']);
    	}
    	return json(['code'=>400,'msg'=>'请求类型错误']);
	}

	/**
     * post: 根据id获取地址
     * path: getAddressById
     * method: getAddressById
     * param: id - {int} 地址id
     */	
	function getAddressById(){
		$id = input('id',0);
//		$uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
		if($id){
			$res = Db::name('address')->where(['id'=>$id,'uid'=>$uid])->find();
		}else{
			$res=Db::name('address')->where(['uid'=>$uid,'is_default'=>1])->find();
		}
		if($res){
			return json(['code'=>200,'msg'=>'SUCCESS','data'=>$res]);
		}else{
			return json(['code'=>400,'msg'=>'暂无数据']);
		}
	}

}	