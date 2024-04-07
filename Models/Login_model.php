<?php
namespace App\Models;
use CodeIgniter\Model;

class Login_model extends Model
{
    public $DBGroup = "default";
    public $table = "dt_role_users";
    public $primaryKey = "id";
    public $useAutoIncrement = true;

    public $allowedFields = ["id", "email", "password", "status", "add_date"];

    public function getAllData($table, $select = null)
    {
        $builder = $this->db->table($table);
        return $query = $builder
            ->select($select)
            ->get()
            ->getResultArray();
    }
    public function getSingle($select = null, $where = null)
    {
        $builder = $this->db->table($this->table);

        return $query = $builder
            ->select($select)
            ->where($where)
            ->get()
            ->getRowArray();
    }

    public function updateRecords($data, $where)
    {
        $builder = $this->db->table($this->table);
        $builder
            ->set($data)
            ->where($where)
            ->update();
        //return $id;
    }

    public function remove($id)
    {
        $builder = $this->db->table($this->table);
        $builder->delete($id);
    }
}
?>
