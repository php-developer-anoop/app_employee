<?php
namespace App\Models;
use CodeIgniter\Model;
class Common_model extends Model {
    public $DBGroup = 'default';
    public $table = "dt_websetting";
    public $primaryKey = 'id';
    public $useAutoIncrement = true;
    public $allowedFields;
    public function insertRecords($table, $data) {
        $db = db_connect('default');
        $builder = $this->db->table($table);
        $builder->insert($data);
        return $db->insertID();
    }
    public function insertBatchItems($table, $data) {
        $builder = $this->db->table($table);
        if (!empty($data)) {
            $builder->insertBatch($data);
        }
    }
    public function getAllData($table = null, $select = null, $where = null, $limit = null, $offset = null, $orderby = null, $key = null, $groupby = null, $jointable = null, $join = null,$jointype=null) {
        $builder = $this->db->table($table);
        if (!empty($select)) {
            $builder->select($select);
        }
        if (!empty($where)) {
            $builder->where($where);
        }
        if (!empty($key)) {
            if (!empty($orderby)) {
                $builder->orderBy($key, $orderby);
            } else {
                $builder->orderBy($key);
            }
        } else if (empty($key) && !empty($orderby)) {
            $builder->orderBy($this->primaryKey, $orderby);
        }
        if (!empty($limit) && !empty($offset)) {
            $builder->limit($limit, $offset);
        } else if (!empty($limit) && empty($offset)) {
            $builder->limit($limit);
        }
        if (!empty($groupby)) {
            $builder->groupBy($groupby);
        }
        if (!empty($jointable) && !empty($join) && !empty($jointype)) {
            $builder->join($jointable, $join,$jointype);
        }
        $results = $builder->get()->getResultArray();
        return $results;
    }
    public function countRecords($table = null, $where = null, $selectKey = null,$limit = null, $offset = null) {
        $builder = $this->db->table($table);
        if (!empty($selectKey)) {
            $builder->select($selectKey);
        }
        if (!empty($where)) {
            $builder->where($where);
        }
        if (!empty($limit) && !empty($offset)) {
            $builder->limit($limit, $offset);
        } else if (!empty($limit) && empty($offset)) {
            $builder->limit($limit);
        }
        $results = $builder->get()->getResultArray();
        return $results;
    }
    public function getSingle($table = null, $select = null, $where = null, $orderby = null) {
        $builder = $this->db->table($table);
        if (!empty($select)) {
            $builder->select($select);
        }
        if (!empty($where)) {
            $builder->where($where);
        }
        if (!empty($orderby)) {
            $builder->orderBy($this->primaryKey, $orderby);
        }
        return $builder->get()->getRowArray();
    }
    public function updateRecords($table, $data, $where) {
        $builder = $this->db->table($table);
        $builder->set($data)->where($where)->update();
    }
    public function deleteRecords($table, $where) {
        $builder = $this->db->table($table);
        $builder->where($where);
        $builder->delete();
        return true;
    }
    public function getfilter($table, $where = false, $limit = false, $start = false, $orderby = false, $orderbykey = false, $getorcount = false, $select = false) {
        $builder = $this->db->table($table);
        if (!empty($select)) {
            $builder->select($select);
        }
        if (!empty($where)) {
            $builder->where($where);
        }
        $builder->limit($limit, $start);
        $builder->orderBy($orderbykey, $orderby);
        if (!empty($getorcount) && $getorcount == "count") {
            $results = $builder->get()->getResultArray();
            return count($results);
        } else if (!empty($getorcount) && $getorcount == "get") {
            $results = $builder->get()->getResultArray();
            return $results;
        }
    }
    public function updateData($table, $data, $where) {
        $builder = $this->db->table($table);
        $builder->set($data)->where($where);
        if ($builder->update()) {
            return true; // Return true if the update was successful.
            
        } else {
            return false; // Return false if the update failed.
            
        }
    }
}
?>