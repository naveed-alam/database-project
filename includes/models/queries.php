<?php
declare(strict_types=1);
class Get_Data {
    public $db;
    public function __construct( $db ) {
        $this->db = $db;
    }
    public function insert_data( array $data ) {
        $this->db->insert($this->db->users, $data);
    }
    public function get_data( $query ) {
        $users = $this->db->get_results(
            $this->db->prepare($query)
        );
    }
    public function update_data( string $table, array $data, array $where ) {
        $this->db->update($table, $data, $where);
    }
    public function delete_data( $table, $where ) {
        $this->db->delete($table, $where);
    }
}