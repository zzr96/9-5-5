<?php

namespace app\apis\controller;

use think\Controller;

/**

 * swagger: 聊天相关

 */

class Apimsg extends Controller{
	 // public $baseurl='http://fengchao.tainongnongzi.com/public_html';		
	 public $baseurl='';		

	 public function _initialize(){
     }

	  /**

	 * post: 显示聊天详情

	 * path: msgView

	 * method: msgView

	 * param: uid - {int} 发送人Id

	 * param: touid - {int} 接收人Id

	 */	

	 //  public function msgView(){
  // 	  $uid=input("uid");
	 //  $touid=input("touid");
	 //  $list=db("msg")->where("(uid=".$uid." and touid=".$touid.") or (uid=".$touid." and touid=".$uid.")")->order("id desc")->limit(100)->select();
	 //  foreach($list as $k=>$v){
	 //  		if($v['uid']==$uid){
		// 		$id=$v['touid'];
		// 	}else{
		// 		$id=$v['uid'];
		// 	}
	 //  		$list[$k]['u']=db("user")->where("id=".$uid)->field("id,nick,headimg")->find();	
		// 	if(!$list[$k]['u']){
		// 		$list[$k]['u']['headimg']='/uploads/headimg/head.png';
		// 		$list[$k]['u']['nick']='游客';
		// 	}
		// 	$list[$k]['u']['headimg']=$this->baseurl . $list[$k]['u']['headimg'];
		// 	if($v['looked']==0){
		// 		if($uid==$v['touid']){
		// 			db("msg")->where("id=".$v['id'])->setField("looked",1);
		// 		}
		// 	}
	 //  }	
  // 	  $ret['code']=0;
	 //  $ret['data']=array_reverse($list);
	 //  $ret['count']=count($list);	
  // 	  return json($ret);
  // }
 	public function msgView(){
  	  $uid=input("uid");
	  $touid=input("touid");
	  $list=db("msg")->where("(uid=".$uid." and touid=".$touid.") or (uid=".$touid." and touid=".$uid.")")->order("id desc")->limit(100)->select();
	  foreach($list as $k=>$v){
	  // 		if($v['uid']==$uid){
			// 	$id=$v['touid'];
			// }else{
			// 	$id=$v['uid'];
			// }
			$list[$k]['date']=date('Y-m-d H:i',$v['date']);
	  		$list[$k]['u']=db("user")->where("id=".$v['uid'])->field("id,nickname,logo")->find();	
			if(!$list[$k]['u']){
				$list[$k]['u']['logo']=$list[$k]['u']['logo'];
				$list[$k]['u']['nickname']=$list[$k]['u']['nickname'];
				
			}

	        // $img =ltrim($list[$k]['u']['logo'],".");
			// $list[$k]['u']['logo']=$this->baseurl . $img;
			if($v['looked']==0){
				if($uid==$v['touid']){
					db("msg")->where("id=".$v['id'])->setField("looked",1);
				}
			}
	  }	
  	  $ret['code']=0;
	  $ret['data']=array_reverse($list);
	  // $ret['count']=count($list);	
  	  return json($ret);
  	  // return json_encode($ret,JSON_UNESCAPED_SLASHES);
  }

  

   /**

	 * post: 显示聊天记录列表

	 * path: msgList

	 * method: msgList

	 * param: uid - {int} 获取谁的消息列表uid

	 */	

  // public function msgList(){

  // 	  $uid=input("uid");

	 //  $list=db("msg_list")->where("uid=".$uid." or touid=".$uid)->order("date desc")->limit(100)->select();

	 //  foreach($list as $k=>$v){

	 //  		if($v['uid']==$uid){

		// 		$id=$v['touid'];

		// 	}else{

		// 		$id=$v['uid'];

		// 	}

		// 	$list[$k]['num']=db("msg")->where("touid=".$uid." and uid=".$id."  and looked=0")->count();

	 //  		$list[$k]['u']=db("user")->where("id=".$id)->field("id,nickname,logo")->find();	

		// 	$list[$k]['u']['logo']=$this->baseurl . $list[$k]['u']['logo'];

		// 	$list[$k]['date']=date('Y-m-d H:i',$v['date']);
	 //  }	
	 //  $ret['kefu_id']=1;//默认客服

  // 	  $ret['code']=0;

	 //  $ret['data']=$list;

	 //  $ret['count']=count($list);	

  // 	  return json($ret);

  // }
	function msgList(){
			 $uid=input("uid");
			 // where("(uid=".$uid." and touid=".$touid.") or (uid=".$touid." and touid=".$uid.")")
			 // $map['id'] = array(array('gt',3),array('lt',10), 'or');
			 // $list=db("msg_list")->where("uid=".$uid." or touid=".$uid)->order("date desc")->limit(100)->select();
			 $list=db("msg_list")->where("(uid=".$uid.") or (touid=".$uid.")")->order("date desc")->limit(100)->select();
			 foreach($list as $k=>$v){
	  			if($v['uid']==$uid){
					$id=$v['touid'];
				}else{
					$id=$v['uid'];
				}
				$list[$k]['num']=db("msg")->where("touid=".$uid." and uid=".$id."  and looked=0")->count();

		  		$list[$k]['u']=db("user")->where("id=".$id)->field("id,nickname,logo")->find();	
		  		// if(empty($list[$k]['u'])){
		  		// 	$shop=db('shop')->where('id',$id)->field('uid')->find();
		  		// 	$ids=$shop['uid'];
		  		// 	$list[$k]['u']=db("user")->where("id=".$ids)->field("nickname,logo")->find();
		  		// 	$list[$k]['u']['id']=$id;
		  		// }
				$list[$k]['u']['logo']=$list[$k]['u']['logo'];
				// $list[$k]['u']['logo']=$this->baseurl . $list[$k]['u']['logo'];

				$list[$k]['date']=date('Y-m-d H:i',$v['date']);
		  }	
		  $ret['kefu_id']=1;//默认客服

	  	  $ret['code']=0;

		  $ret['data']=$list;

		  $ret['count']=count($list);	

	  	  return json($ret);
	}

  

   /**

	 * post: 添加聊天内容

	 * path: msgAdd

	 * method: msgAdd

	 * param: uid - {int} 消息发送者

	 * param: touid - {int} 消息接收者

	 * param: content - {strinb} 消息内容

	 * param: type - {int} = [1|2|3] 类型(1: 文字, 2: 图片, 3: 语音)

	 */	

  public function msgAdd(){
  	$uid=input("uid");
	$data['touid']=input("touid",1);
	$content=input("content");
	$data['uid']=$uid;
	$type=input("type");
	$data['content']=$content;
	$data['type']=$type;
	$data['date']=time();
	if($uid == $data['touid']){
		$ret['code']=1;
		$ret['msg']='不可以与自己聊天哦！';
		return json($ret);
	}
	$do=db("msg")->insert($data);
 	if($do){
		$ch=db("msg_list")->where("(uid=".$uid ." and touid=".$data['touid'].") or (uid=".$data['touid'] ." and touid=".$uid.")")->find();
		if($type==2){
			$content='[图片]';
		}
		if($type==3){
			$content='[语音]';
		}
		if($ch){
			$da['id']=$ch['id'];
			$da['content']=$content;
			$da['date']=time();
			db("msg_list")->update($da);
		}else{
			$da['uid']=$data['uid'];
			$da['touid']=$data['touid'];
			$da['content']=$content;
			$da['date']=time();
			db("msg_list")->insert($da);
		}

		$ret['code']=0;
		$ret['msg']='ok';
		return json($ret);
	} 
		$ret['code']=1;
		$ret['msg']='error';

		return json($ret);
  }

  

    /**

	 * post: 添加聊天图片

	 * path: upload

	 * method: upload

	 * param: uploadkey - {file} 消息发送者

	 */	

  public function upload(){

			$image = \think\Image::open(request()->file('uploadkey'));

			

			$image->thumb(300,300, \think\Image::THUMB_SCALING);

			$savePath =  './uploads/image/'. date("Ymd")."/";

			$saveName = uniqid() . '.jpg';

			if (!is_dir($savePath)){  

				mkdir($savePath,0777,true);

			}

			$image->save( $savePath . $saveName);

			$url=	$savePath.	$saveName;

			

			$ret['pic']=$this->baseurl . str_replace("./","/",$url);

			$ret['code']=0;

		

		  return json($ret);

	}

	

	/**

	 * post: 文件上传

	 * path: upfile

	 * method: upfile

	 * param: uploadkey - {file} 消息发送者

	 */	

     public function upfile(){

         // 获取表单上传文件

         $file = request()->file('uploadkey');

 

        if (empty($file)) {

             $this->error('请选择上传文件');

         }

         // 移动到框架应用根目录/public/uploads/ 目录下

		 $savePath =  './uploads/xinxi/';

        $info = $file->move($savePath);

       if ($info) {

           // $this->success('文件上传成功');

			

			$url= $savePath . $info->getSaveName();

			

			$ret['code']=0;

			$ret['url']=$this->baseurl . str_replace("./","/",$url);

			return json($ret);

           

         } else {

             // 上传失败获取错误信息

             $this->error($file->getError());

         }

 

     }

	







}

	