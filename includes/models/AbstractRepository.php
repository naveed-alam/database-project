<?php
abstract class AbstractRepository {

    public function __construct(
        protected Database $db,
        protected MetaRepository $meta
    ) {}

    abstract protected function table(): string;
    abstract protected function primaryKey(): string;

    public function delete(int $id): bool {
        if ($this instanceof HasMeta) {
            $this->meta->deleteAll($this, $id);
        }

        return $this->db->delete(
            $this->table(),
            [$this->primaryKey() => $id]
        );
    }
}