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


// $db   = new Database();
// $meta = new MetaRepository($db);
// $posts = new PostRepository($db, $meta);
// $users = new UserRepository($db, $meta);
// // Add meta to post
// $meta->add($posts, 10, 'color', 'red');
// // Get meta
// $meta->get($users, 5, 'timezone');
// // Delete post (meta auto-deleted)
// $posts->delete(10);