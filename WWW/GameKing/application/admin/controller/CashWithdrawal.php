<?php

namespace app\admin\controller;

use think\Request;
use think\Db;

class CashWithdrawal extends Base
{
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '提现记录');
    }

    public function index()
    {
        $data = Db::name('user_deposit')->paginate(10)->each(function ($item, $key) {
            $item['mobile'] = Db::name('member')->where('uid', $item['uid'])->value('phone');
            $item['nickname'] = Db::name('member')->where('uid', $item['uid'])->value('nickname');
            return $item;
        });
        $list = $data->items();
        $page = $data->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        return view();
    }
    //提现审核通过
    public function auditPass()
    {
        $id = input('id');
        $data = Db::name('user_deposit')->find($id);
        if ($data['status'] == 0) {
            Db::name('member')->where('uid', $data['uid'])->setDec('lock_amount', $data['money']);
            Db::name('user_deposit')->where('id', $id)->update(['status' => 1, 'update_time' => date('Y-m-d H:i:s')]);
            Db::name('balance_log')->insert([
                'uid' => $data['uid'],
                'amount' => '-' . $data['money'],
                'mark' => '余额提现',
                'type' => 2,
                'order_no' => $data['order_no'],
                'createtime' => date('Y-m-d H:i:s'),
            ]);
            return json(['code' => 0, 'msg' => '审核通过']);
        } else {
            return json(['code' => 1, 'msg' => '该提现已处理']);
        }
    }


    //提现审核拒绝
    public function auditReject()
    {
        $id = input('id');
        $data = Db::name('user_deposit')->find($id);
        if ($data['status'] == 0) {
            Db::startTrans();
            try{
                Db::name('member')->where('uid', $data['uid'])->setInc('balance', $data['money']);
                Db::name('user_deposit')->where('id', $id)->update(['status' => 2, 'update_time' => date('Y-m-d H:i:s')]);
                Db::commit();
                return json(['code' => 0, 'msg' => '操作成功']);
            }catch (\Exception $e){
                Db::rollback();
                return json(['code' => 0, 'msg' => $e->getMessage()]);
            }
        } else {
            return json(['code' => 1, 'msg' => '该提现已处理']);
        }
    }


}
