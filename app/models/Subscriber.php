<?php
class Subscriber extends Common
{
    protected $_name = "subscriber";
    protected $_primary = 'id';

    /**
     * Get subscriber num
     * @param false $isAdmin
     * @param int $uid
     * @return string
     */
    public function getIndexTotal($isAdmin = false, $uid = 0) {
        $db = $this->getAdapter();
        $sql = "
        SELECT COUNT(id) as num
        FROM %s
        WHERE %s
        ";
        $where = " 1=1 ";
        if (!$isAdmin) {
            $where = sprintf(' uid = %d', $uid);
        }
        return $db->fetchOne(sprintf($sql, $this->_name, $where));
    }
}