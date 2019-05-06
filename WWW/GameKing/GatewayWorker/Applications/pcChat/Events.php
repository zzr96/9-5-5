<?php

use \GatewayWorker\Lib\Gateway;

class Events
{
    public static $db = null;

    /**
     * 进程启动后初始化数据库连接
     */
    public static function onWorkerStart($worker)
    {
        self::$db = new \GatewayWorker\Lib\DbConnection('127.0.0.1', '3306', 'root', '123456', 'gongcheng');
    }

    public static function onMessage($client_id, $message)
    {
        $message_data = json_decode($message, true);
        if (!$message_data) {
            return;
        }
        switch ($message_data['type']) {
            case 'bind':
                Gateway::bindUid($client_id, $message_data['uid']);
                return;
            case 'bindRead':
                self::$db->update('gc_chat_message')->cols(['readtime' => time()+8*3600])
                    ->where('to_uid= :to_uid and from_uid=:from_uid and readtime=0')->bindValues(['to_uid' => $message_data['uid'], 'from_uid' => $message_data['from_uid']])
                    ->query();
                Gateway::bindUid($client_id, $message_data['uid']);
                return;
            case 'read':
                self::$db->update('gc_chat_message')->cols(['readtime' => time()+8*3600])
                    ->where('id= :id')->bindValues(['id' => $message_data['id']])
                    ->query();
                return;
            case 'say':
                if (empty($message_data['to_uid'])) {
                    return;
                }
                $new_message = array(
                    'type' => 'say',
                    'from_uid' => $message_data['from_uid'],
                    'to_uid' => $message_data['to_uid'],
                    'msg_type' => $message_data['msg_type'],
                    'content' => htmlspecialchars($message_data['content']),
                    'sendtime' => date('H:i',time()+8*3600),
                );
                $new_message['duration'] = isset($message_data['duration']) ? $message_data['duration'] : 0;
                //获取发送者历史记录
                $re = self::$db->select('*')->from('gc_chat_history')->where('uid= :uid and other_uid=:to_uid')->bindValues(['uid' => $message_data['from_uid'], 'to_uid' => $message_data['to_uid']])->row();
                if (empty($re)) {
                    self::$db->insert('gc_chat_history')->cols([
                        'uid' => $message_data['from_uid'],
                        'other_uid' => $message_data['to_uid'],
                        'addtime' => time()+8*3600,
                    ])->query();
                } else {
                    self::$db->update('gc_chat_history')->cols(['addtime' => time()+8*3600])
                        ->where('uid= :uid and other_uid=:to_uid')->bindValues(['uid' => $message_data['from_uid'], 'to_uid' => $message_data['to_uid']])
                        ->query();
                }
                //获取接受者历史记录
                $res = self::$db->select('*')->from('gc_chat_history')->where('uid= :uid and other_uid=:to_uid')->bindValues(['uid' => $message_data['to_uid'], 'to_uid' => $message_data['from_uid']])->row();
                if (empty($res)) {
                    self::$db->insert('gc_chat_history')->cols([
                        'uid' => $message_data['to_uid'],
                        'other_uid' => $message_data['from_uid'],
                        'addtime' => time()+8*3600,
                    ])->query();
                } else {
                    self::$db->update('gc_chat_history')->cols(['addtime' => time()+8*3600])
                        ->where('uid= :uid and other_uid=:to_uid')->bindValues(['uid' => $message_data['to_uid'], 'to_uid' => $message_data['from_uid']])
                        ->query();
                }
                //获取上一条记录的发送时间
                $lastTime = self::$db->select('sendtime')->from('gc_chat_message')
                    ->where('from_uid= :from_uid and to_uid=:to_uid')
                    ->orWhere('from_uid= :to_uids and to_uid=:from_uids')
                    ->bindValues([
                        'from_uid' => $message_data['from_uid'],
                        'to_uid' => $message_data['to_uid'],
                        'to_uids' => $message_data['to_uid'],
                        'from_uids' => $message_data['from_uid'],
                    ])
                    ->orderByDESC(['id'])
                    ->limit(1)->single();
                if (time()+8*3600 > ($lastTime + 60 * 5)) {
                    $show_time = 1;
                } else {
                    $show_time = 0;
                }

                $insert_id = self::$db->insert('gc_chat_message')->cols([
                    'from_uid' => $message_data['from_uid'],
                    'to_uid' => $message_data['to_uid'],
                    'duration' => $new_message['duration'],
                    'show_time' => $show_time,
                    'msg_type' => $message_data['msg_type'],
                    'content' => $message_data['content'],
                    'sendtime' => time()+8*3600,
                ])->query();
                $new_message['id'] = $insert_id;
                $new_message['show_time'] = $show_time;
                $new_message['self'] = 0;
                Gateway::sendToUid($message_data['to_uid'], json_encode($new_message));
                $new_message['self'] = 1;
                Gateway::sendToUid($message_data['from_uid'], json_encode($new_message));
//            default:
//                GateWay::sendToAll("$client_id login\r\n");
//                return;
        }
    }
    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id)
    {
//        GateWay::sendToAll("$client_id logout!");
    }
}
