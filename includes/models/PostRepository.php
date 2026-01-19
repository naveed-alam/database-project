<?php
class PostRepository extends AbstractRepository implements HasMeta {

    protected function table(): string {
        return 'posts';
    }

    protected function primaryKey(): string {
        return 'id';
    }

    public function getMetaTable(): string {
        return 'postmeta';
    }

    public function getPrimaryKey(): string {
        return 'post_id';
    }
}