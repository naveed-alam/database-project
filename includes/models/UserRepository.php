<?php
class UserRepository extends AbstractRepository implements HasMeta {

    protected function table(): string {
        return 'users';
    }

    protected function primaryKey(): string {
        return 'id';
    }

    public function getMetaTable(): string {
        return 'usermeta';
    }

    public function getPrimaryKey(): string {
        return 'user_id';
    }
}