<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 5.5.14
 * Time: 18:21
 */
class Chat_model extends CI_Model
{

    function lastMessagesForMe($limit)
    {
        $userId = $this->user_model->getUserId();

        $sql = $this->db->where(array('user_id_fk' => $userId))->get('rel_user_conversation');
        $arrayConversations = $sql->result_array();
        if (!empty($arrayConversations)) {
            for ($i = 0; $i < sizeof($arrayConversations); $i++) {
                $arrayConversations[$i] = $arrayConversations[$i]['conversation_id_fk'];
            }
            $this->db->join('users', 'users.user_id_pk = conversation_reply.user_id_fk');
            $sql = $this->db->where_in('conversation_id_fk', $arrayConversations)->where('user_id_fk !=', $userId)->order_by('conversation_reply_id_pk', 'desc')->get('conversation_reply', $limit);
            return $sql->result();
        } else {
            return;
        }
    }

    public function createConversation($targetUserId)
    {
        $sourceUserId = $this->user_model->getUserId();
        $conversationArr = $this->chat_model->getUserConversationsArray();

        $needToCreateNewConversation = false;
        if (!empty($conversationArr)) {
            $sql = $this->db->select('conversation_id_fk')->where_in('conversation_id_fk', $conversationArr)->where('user_id_fk', $targetUserId)->get('rel_user_conversation');
            $num_rows = $sql->num_rows();
            if ($num_rows == 0) {
                $needToCreateNewConversation = true;
            }
        } else {
            $needToCreateNewConversation = true;
        }
        if ($needToCreateNewConversation) {
            $this->db->trans_start();
            $this->db->insert('conversation', array('user_id_fk' => $sourceUserId));
            $conversationId = $this->db->insert_id();
            $this->db->insert('rel_user_conversation', array('user_id_fk' => $sourceUserId, 'conversation_id_fk' => $conversationId));
            $this->db->insert('rel_user_conversation', array('user_id_fk' => $targetUserId, 'conversation_id_fk' => $conversationId));
            $this->db->trans_complete();
            return $conversationId;
        } else {
            $result = $sql->result()[0]->conversation_id_fk;
            return $result;
        }
    }

    public function getUserConversationsArray()
    {
        $userId = $this->user_model->getUserId();
        $sql = $this->db->select('conversation_id_fk')->where('user_id_fk', $userId)->get('rel_user_conversation');
        $array = $sql->result_array();
        $conversationArr = array();
        for ($i = 0; $i < sizeof($array); $i++) {
            $conversationArr[$i] = $array[$i]['conversation_id_fk'];
        }
        return $conversationArr;
    }

    public function getUserConversations()
    {
        $userId = $this->user_model->getUserId();
        $query = $this->db->query("
        SELECT * FROM rel_user_conversation
        JOIN users ON users.user_id_pk = rel_user_conversation.user_id_fk
        WHERE conversation_id_fk IN
                        (SELECT conversation_id_fk FROM rel_user_conversation
                         WHERE user_id_fk = $userId)
        AND rel_user_conversation.user_id_fk != $userId
        ORDER BY timestamp DESC");
        return $query->result();
    }


    public function zeroOutMessageCount($conversationId)
    {
        $userId = $this->user_model->getUserId();
        if ($userId) {
            $this->db->where(array('conversation_id_fk' => $conversationId, 'user_id_fk' => $userId))->
                set('unread_replies_count', 0)->
                update('rel_user_conversation');
            $sql = $this->db->select('rel_user_conversation_id_pk')->
                where(array('user_id_fk' => $userId, 'unread_replies_count >' => 0))->
                get('rel_user_conversation');
            return $sql->num_rows();
        }
    }


    public function getMessageCount()
    {
        $userId = $this->user_model->getUserId();

        $sql = $this->db->select('conversation_id_fk,unread_replies_count')->
            where(array('user_id_fk' => $userId, 'unread_replies_count >' => 0))->
            get('rel_user_conversation');
        $conversations = $sql->result();
        $conversations[sizeof($conversations)]['total_count'] = $sql->num_rows();
        return $conversations;
    }


    public function getMessages($conversationId, $limit = null)
    {
        if ($this->user_model->verifyUserConversation($conversationId)) {
            $this->db->join('users', 'users.user_id_pk = conversation_reply.user_id_fk');
            $sql = $this->db->where('conversation_id_fk', $conversationId)->order_by('conversation_reply_id_pk', 'desc')->get('conversation_reply', $limit);
            $array = $sql->result();
            $now = time(date('Y-m-d H:i:s'));
            $timestampDay = 60 * 60 * 24;
            for ($i = 0; $i < sizeof($array); $i++) {
                $date = explode(' ', $array[$i]->time)[0];
                $day = explode('-', $date)[2];
                $month = explode('-', $date)[1];
                $time = explode(' ', $array[$i]->time)[1];
                $hour = explode(':', $time)[0];
                $minute = explode(':', $time)[1];

                if (strtotime($array[$i]->time) > $now - $timestampDay) {
                    $array[$i]->time = $hour . ':' . $minute;
                } else {
                    if (strtotime($array[$i]->time) > $now - 2 * $timestampDay) {
                        $array[$i]->time = 'včera' . $hour . ':' . $minute;;
                    } else {
                        $array[$i]->time = $day . '.' . $month . '.';
                    }
                }
            }
            return $array;
        } else {
            return false;
        }
    }

    public function sendMessage($targetUserId, $message)
    {
        $sourceUserId = $this->user_model->getUserId();
        if ($sourceUserId != $targetUserId) {
            $array = $this->db->select('conversation_id_fk')->where('user_id_fk', $sourceUserId)->get('rel_user_conversation')->result_array();

            $formatArray = array();
            for ($i = 0; $i < sizeof($array); $i++) {
                $formatArray[$i] = $array[$i]['conversation_id_fk'];
            }

            if (sizeof($formatArray) > 0) {
                $sql = $this->db->where_in('conversation_id_fk', $formatArray)->where('user_id_fk', $targetUserId)->get('rel_user_conversation');
                if ($sql->num_rows() == 0) {
                    $this->db->trans_start();
                    $this->db->insert('conversation', array('timestamp' => time(), 'user_id_fk' => $targetUserId));
                    $conversationId = $this->db->insert_id();

                    $this->db->insert('rel_user_conversation', array('user_id_fk' => $sourceUserId, 'conversation_id_fk' => $conversationId));
                    $this->db->insert('rel_user_conversation', array('user_id_fk' => $targetUserId, 'conversation_id_fk' => $conversationId));
                    $this->db->trans_complete();
                } else {
                    $conversationId = intval($sql->row('conversation_id_fk'));

                    $this->db->query("
                    UPDATE rel_user_conversation
                    SET unread_replies_count = unread_replies_count + 1
                    WHERE user_id_fk = $targetUserId AND conversation_id_fk = $conversationId");
                }
                $replyArr = array(
                    'reply' => $message,
                    'user_id_fk' => $sourceUserId,
                    'conversation_id_fk' => $conversationId,
                    'ip' => $this->input->ip_address(),
                    'time' => date('Y-m-d H:i:s')
                );
                $this->db->insert('conversation_reply', $replyArr);
                return $replyArr;
            } else {
                return false;
            }
//        return $conversationId;
        } else {
            return false;
        }
    }

    public function getConversationInfo($conversationId)
    {
        $userId = $this->user_model->getUserId();
        $this->db->join('users', 'users.user_id_pk = rel_user_conversation.user_id_fk');
        $sql = $this->db->select('conversation_id_fk, user_id_pk, firstname, lastname')->where('conversation_id_fk', $conversationId)->where('user_id_fk !=', $userId)->get('rel_user_conversation');
        return $sql->result();
    }
}