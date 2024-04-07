<?php
namespace App\Models;
use CodeIgniter\Model;
class Api_model extends Model {
    public $DBGroup = 'default';
    public $table = "dt_websetting";
    public $primaryKey = 'id';
    public $useAutoIncrement = true;
    public $allowedFields;
    
    function insertRecords($table, $data) {
        $builder = $this->db->table($table);
        $builder->insert($data);
        return $this->db->insertID();
    }
    public function insertBatchItems($table,$data)
    {
        $builder = $this->db->table($table);
        if (!empty($data)) {
            $builder->insertBatch($data);
        }
    }
    public function getAllData($table = null, $select = null, $where = null, $limit = null, $offset = null, $orderby = null,$key=null,$groupby=null) {
        $builder = $this->db->table($table);
        if (!empty($select)) {
            $builder->select($select);
        }
        if (!empty($where)) {
            $builder->where($where);
        }
        
        if (!empty($key)) {
            $builder->orderBy($key, $orderby);
        }else if(empty($key) && !empty($orderby)){
            $builder->orderBy($this->primaryKey, $orderby);
        }
        if (!empty($limit) && !empty($offset)) {
            $builder->limit($limit, $offset);
        } else if (!empty($limit) && empty($offset)) {
            $builder->limit($limit);
        }
        if(!empty($groupby)){
            $builder->groupBy($groupby);
        }
        $results = $builder->get()->getResultArray();
        return $results;
    }
    public function countRecords($table = null, $where = null, $selectKey = null) {
        $builder = $this->db->table($table);
        if (!empty($selectKey)) {
            $builder->select($selectKey);
        }
        if (!empty($where)) {
            $builder->where($where);
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
}
?>