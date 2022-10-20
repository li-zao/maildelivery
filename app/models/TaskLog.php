<?php
class TaskLog extends Common
{
    protected $_name = "task_log";
    protected $_primary = 'id';


    public function getIndexTask($isAdmin = false, $uid = 0) {
        if (!$isAdmin && empty($uid)) {
            return [];
        }
        $sql = "
        SELECT 
        tl.id,
        tl.tid,
        tl.runtime,
        tl.total,
        tl.success,
        tl.failure,
        t.task_name
        FROM %s tl
        LEFT JOIN %s t on tl.tid = t.id
        WHERE %s
        %s
        ";
        $order = ' ORDER BY tl.`runtime` DESC LIMIT 4';
        if ($isAdmin) {
            $where = ' 1=1 ';
        } else {
            $where = sprintf(' uid = %d', $uid);
        }
        $data = $this->getAdapter()->fetchAll(sprintf($sql, $this->_name, 'mr_task', $where, $order));
        foreach ($data as &$val) {
            $val['progress'] = round(($val['success'] + $val['failure']) / $val['total'] * 100) . "%";
            $val['completion_rate'] = round($val['success'] / $val['total'] * 100) . '%';
        }
        if (count($data) != 4) {
            for ($i = 0; $i < 4; $i++) {
                if (!isset($data[$i])) {
                    $data[$i] = [];
                }
            }
        }
        return $data;
    }
}