<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 14.3.14
 * Time: 11:23
 *
 * Resi hlavni CRUD model s databazi
 */
class Crud extends CI_Model
{

    /**
     * Vraci data z dane tabulky
     * @param String $table tabulka, ze ktere se data nacitaji
     * @param String $orderCol sloupecek, podle ktereho se bude radit
     * @param String $orderBy asc,desc
     * @param Array $where parametry pro where vyber
     * @param int $limit pocet vracenych zaznamu
     * @param int $ofset ofset
     * @param Array $like vyhledavani podle parametru
     */
    public function getData(
        $table,
        $orderCol = "",
        $orderBy = "asc",
        $where = array(),
        $limit = 0,
        $ofset = 0,
        $like = array()
    )
    {
        $data = array();
        //order by
        if (!empty($orderCol))
            $this->db->order_by($orderCol, $orderBy);

        //where
        if (!empty($where))
            foreach ($where as $key => $value)
                $this->db->where($key, $value);

        //like
        if (!empty($like) || $like != FALSE)
            foreach ($like as $key => $value)
                $this->db->like($key, $value);

        //limit
        if ($limit != 0)
            $this->db->limit($limit, $ofset);

        $q = $this->db->get($table);

        if ($q->num_rows() > 0)
            foreach ($q->result() as $row)
                $data[] = $row;

        return $data;
    }

    /**
     * Vrati specificka data podle zadanych parametru
     * @param String $table tabulka, ze ktere se data nacitaji
     * @param Array $where parametry pro where vyber
     */
    public function getSpecificData($table, $where)
    {
        $data = $this->getData($table, "", "", $where);
        if (empty($data)) return NULL;
        return $data[0];
    }

    /**
     * Vrati pocet celkovych zaznamu z dane tabulky
     * podle predanych parametru
     * @param String $table pocitana tabulka
     * @param Array $where vymezujici parametry
     * @return int pocet zaznamu
     */
    public function getNumOfRecords($table, $where = array(), $like = array())
    {
        //where
        if (!empty($where))
            foreach ($where as $key => $value)
                $this->db->where($key, $value);

        //like
        if (!empty($like) || $like != FALSE)
            foreach ($like as $key => $value)
                $this->db->like($key, $value);

        $q = $this->db->get($table);
        return $q->num_rows();
    }


    /**
     * Maze polozku z databaze
     * @param String $table tabulka
     * @param Array $where vymezujici parametry
     */
    public function deleteRecord($table, $where)
    {
        foreach ($where as $key => $value)
            $this->db->where($key, $value);


        $this->db->delete($table);
    }

    /**
     * Vlozi zaznam
     * @param String $table tabulka zaznamu
     * @param Array $data data
     */
    public function insertRecord($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }


    /**
     * Upravi zaznam
     * @param String $table tabulka zaznamu
     * @param Array $data data
     * @param Array $where vymezujici parametry
     */
    public function updateRecord($table, $data, $where = array(), $p = TRUE)
    {
        if (!empty($where))
            foreach ($where as $key => $value)
                $this->db->where($key, $value);

        foreach ($data as $key => $value)
            $this->db->set($key, $value, $p);

        $this->db->update($table);
    }
}

?>
