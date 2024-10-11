<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_backup extends CI_Model
{
    public function droptabel()
    {
        $cek = $this->db->query("SHOW TABLES");
        if ($cek->num_rows() > 0) {
            $query = $this->db->query("DROP TABLE masukan semua tabel di database dipisah koma ");
            return $query;
        } else {
            return true;
        }
    }
}
