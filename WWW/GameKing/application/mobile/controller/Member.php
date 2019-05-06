<?php

namespace app\mobile\controller;

use think\Db;
use think\Controller;
use think\Request;
use think\Exception;
use think\Cookie;
use think\Validate;

class Member extends Controller
{
    protected $uid;

    public function _initialize()
    {
        $uid = cookie('userId');
        $token = cookie('token');

        if (!$uid) {
            $this->redirect('mobile/login/login');
        }
        if (!$token) {
            $this->redirect('mobile/login/login');
        }
        $user = Db::name('member')->find($uid);

        if (empty($user)) {
            $this->redirect('mobile/login/login');
        }

        if (md5($user['uid'] . md5($user['password'])) != $token) {
            $this->redirect('mobile/login/login');
        }
        $this->uid = $uid;
    }

    public function buy()
    {
        $userInfo = Db::name('member')->where('uid', $this->uid)->find();
        if ($userInfo['vip']) {
            $this->redirect('mobile/member/index');
        }
        return view();
    }

    public function index()
    {
        $userInfo = Db::name('member')->where('uid', $this->uid)->find();
        $this->assign('userInfo', $userInfo);
        return view();
    }

    public function gongying()
    {
        $zhuanyeData = Db::name('ruzhu_setting')->field('title text,id value')->where('level', 1)->select();
        foreach ($zhuanyeData as $k => &$v) {
            $children = Db::name('ruzhu_setting')->field('title text,id value')->where(['pid' => $v['value'], 'level' => 2])->select();
            $v['children'] = $children;
        }
        $this->assign('zhuanye', json_encode($zhuanyeData));
        $userInfo = Db::name('member')->where('uid', $this->uid)->find();
        if ($this->request->isGet()) {
            $renzheng = Db::name('renzheng')->where('uid', $this->uid)->find();
            $zySelect = Db::name('ruzhu_setting')->where('id', $renzheng['supply_specialty'])->value('title');
            $lbSelect = Db::name('ruzhu_setting')->where('id', $renzheng['supply_type'])->value('title');
            $leibieText = Db::name('ruzhu_setting')->where('id', 'in', $renzheng['leibie3'])->column('title', 'id');

            $areaText = Db::name('area')->where('code', 'in', [$renzheng['pro_code'], $renzheng['city_code'], $renzheng['area_code']])->field('areaname')->select();
            $areaArr = array_column($areaText, 'areaname');
            $renzheng['addressText'] = implode(' ', $areaArr);
            $this->assign('leibieText', implode(',', $leibieText));
            $this->assign('renzheng', $renzheng);
            $this->assign('zyText', $zySelect);
            $this->assign('lbText', $lbSelect);
            return view();
        }
        if ($this->request->isPost()) {
            $param = $this->request->param();
            $validate = new Validate([
                ['company_name', 'require', '请输入公司名称'],
                ['contact', ['regex' => config('phoneReg')], '联系电话格式不对'],
                ['address', 'require', '请选择公司地址'],
                ['legal_person', 'require', '请输入法人姓名'],
                ['qq_email', 'require|email', '请输入QQ邮箱|邮箱格式不对'],
                ['supply_address', 'require', '请选择供应地址'],
                ['supply_specialty', 'require', '请选择专业'],
                ['supply_type', 'require', '请选择类别'],
                ['brand', 'require', '请填写品牌'],
            ]);

            if (!$validate->check($param)) {
                return json(['code' => 1, 'msg' => $validate->getError()]);
            }
            $leibie = explode(',', $param['leibie3']);
            if (count($leibie) > 5) {
                return json(['code' => 1, 'msg' => '最多添加5个类别']);
            }
            $param['uid'] = $this->uid;
            $param['type'] = $userInfo['vip'];
            $param['adminid'] = Db::name('admin')->where('uid', $this->uid)->value('id');
            $param['addtime'] = time();
            try {
                $itemid = Db::name('renzheng')->insertGetId($param);
                return json(['code' => 0, 'msg' => '成功提交']);
            } catch (\Exception $e) {
                return json(['code' => 1, 'msg' => $e->getMessage()]);
            }
        }
    }

    public function gongying2()
    {
        $userInfo = Db::name('member')->where('uid', $this->uid)->find();
        if ($this->request->isGet()) {
            $renzheng = Db::name('renzheng')->where('uid', $this->uid)->find();
            $this->assign('renzheng', $renzheng);
            return view();
        }
        if ($this->request->isPost()) {
            $param = $this->request->param();
            $validate = new Validate([
                ['idcard_front', 'require', '请上传正面照片'],
                ['idcard_back', 'require', '请上传反面照片'],
                ['license', 'require', '请上传营业执照'],
            ]);

            if (!$validate->check($param)) {
                return json(['code' => 1, 'msg' => $validate->getError()]);
            }
            try {
                $re = Db::name('renzheng')->where('uid', $this->uid)->update($param);
                return json(['code' => 0, 'msg' => '成功提交']);
            } catch (\Exception $e) {
                return json(['code' => 1, 'msg' => $e->getMessage()]);
            }
        }
    }

    public function school()
    {
        $userInfo = Db::name('member')->where('uid', $this->uid)->find();
        if ($this->request->isGet()) {
            $renzheng = Db::name('renzheng')->where('uid', $this->uid)->find();
            $this->assign('renzheng', $renzheng);
            return view();
        }
        if ($this->request->isPost()) {
            $param = $this->request->param();
            $validate = new Validate([
                ['company_name', 'require', '请输入公司名称'],
                ['contact', ['regex' => config('phoneReg')], '联系电话格式不对'],
                ['legal_person', 'require', '请输入法人姓名'],
                ['qq_email', 'require|email', '请输入QQ邮箱|邮箱格式不对'],
            ]);

            if (!$validate->check($param)) {
                return json(['code' => 1, 'msg' => $validate->getError()]);
            }
            $param['uid'] = $this->uid;
            $param['type'] = $userInfo['vip'];
            $param['addtime'] = time();
            $param['adminid'] = Db::name('admin')->where('uid', $this->uid)->value('id');
            try {
                $itemid = Db::name('renzheng')->insertGetId($param);
                return json(['code' => 0, 'msg' => '成功提交']);
            } catch (\Exception $e) {
                return json(['code' => 1, 'msg' => $e->getMessage()]);
            }
        }
    }

    public function school2()
    {
        $userInfo = Db::name('member')->where('uid', $this->uid)->find();
        if ($this->request->isGet()) {
            $renzheng = Db::name('renzheng')->where('uid', $this->uid)->find();
            $this->assign('renzheng', $renzheng);
            return view();
        }
        if ($this->request->isPost()) {
            $param = $this->request->param();
            $validate = new Validate([
                ['idcard_front', 'require', '请上传正面照片'],
                ['idcard_back', 'require', '请上传反面照片'],
                ['license', 'require', '请上传营业执照'],
            ]);

            if (!$validate->check($param)) {
                return json(['code' => 1, 'msg' => $validate->getError()]);
            }
            try {
                $re = Db::name('renzheng')->where('uid', $this->uid)->update($param);
                return json(['code' => 0, 'msg' => '成功提交']);
            } catch (\Exception $e) {
                return json(['code' => 1, 'msg' => $e->getMessage()]);
            }
        }
    }

    public function qiye()
    {
        $userInfo = Db::name('member')->where('uid', $this->uid)->find();
        if ($this->request->isGet()) {
            $renzheng = Db::name('renzheng')->where('uid', $this->uid)->find();
            $areaText = Db::name('area')->where('code', 'in', [$renzheng['pro_code'], $renzheng['city_code'], $renzheng['area_code']])->field('areaname')->select();
            $areaArr = array_column($areaText, 'areaname');
            $renzheng['addressText'] = implode(' ', $areaArr);
            $this->assign('renzheng', $renzheng);
            return view();
        }
        if ($this->request->isPost()) {
            $param = $this->request->param();
            $validate = new Validate([
                ['company_name', 'require', '请输入公司名称'],
                ['contact', ['regex' => config('phoneReg')], '联系电话格式不对'],
                ['legal_person', 'require', '请输入法人姓名'],
                ['qq_email', 'require|email', '请输入QQ邮箱|邮箱格式不对'],
            ]);

            if (!$validate->check($param)) {
                return json(['code' => 1, 'msg' => $validate->getError()]);
            }
            $param['uid'] = $this->uid;
            $param['type'] = $userInfo['vip'];
            $param['addtime'] = time();
            $param['adminid'] = Db::name('admin')->where('uid', $this->uid)->value('id');
            try {
                $itemid = Db::name('renzheng')->insertGetId($param);
                return json(['code' => 0, 'msg' => '成功提交']);
            } catch (\Exception $e) {
                return json(['code' => 1, 'msg' => $e->getMessage()]);
            }
        }
    }

    public function qiye2()
    {
        $userInfo = Db::name('member')->where('uid', $this->uid)->find();
        if ($this->request->isGet()) {
            $renzheng = Db::name('renzheng')->where('uid', $this->uid)->find();
            $this->assign('renzheng', $renzheng);
            return view();
        }
        if ($this->request->isPost()) {
            $param = $this->request->param();
            $validate = new Validate([
                ['idcard_front', 'require', '请上传正面照片'],
                ['idcard_back', 'require', '请上传反面照片'],
                ['license', 'require', '请上传营业执照'],
            ]);

            if (!$validate->check($param)) {
                return json(['code' => 1, 'msg' => $validate->getError()]);
            }
            try {
                $re = Db::name('renzheng')->where('uid', $this->uid)->update($param);
                return json(['code' => 0, 'msg' => '成功提交']);
            } catch (\Exception $e) {
                return json(['code' => 1, 'msg' => $e->getMessage()]);
            }
        }
    }

    public function upload()
    {
        $file = $this->request->file('image');
        $info = $file->move(ROOT_PATH . 'public_html' . DS . 'uploads' . DS . 'image');
        if ($info) {
            $url = 'uploads' . DS . 'image' . DS . $info->getSaveName();
            return json(['code' => 0, 'msg' => '上传成功', 'url' => $url]);
        } else {
            //上传失败获取错误信息
            return json(['code' => 1, 'msg' => $file->getError()]);
        }
    }

    //ajax获取类别
    public function getLb()
    {
        $lbid = input('lbid');
        $leibieData = Db::name('ruzhu_setting')->where(['level' => 3, 'pid' => $lbid, 'type' => 1])->column('title', 'id');
        return json(['code' => 0, 'data' => $leibieData]);
    }
    //专业类别联动
    /*  public function zylb()
      {
          $zyid = input('zyid');
          $leibieData = Db::name('ruzhu_setting')->where(['level'=>2,'pid'=>$zyid])->column('title', 'id');
          foreach ($leibieData as $k => $v) {
              $leibie[] = [
                  'value' => $k,
                  'text' => $v
              ];
          }
          return json(['code'=>0,'data'=>$leibie]);
      }*/


}