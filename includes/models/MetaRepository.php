<?php
class MetaRepository {

    public function __construct(
        private Database $db
    ) {}

    public function add(
        HasMeta $entity,
        int $entityId,
        string $key,
        string $value
    ): int|false {
        return $this->db->insert(
            $entity->getMetaTable(),
            [
                $entity->getPrimaryKey() => $entityId,
                'meta_key'   => $key,
                'meta_value' => $value,
            ]
        );
    }

    public function get(
        HasMeta $entity,
        int $entityId,
        string $key
    ): ?string {
        $row = $this->db->get_row(
            $this->db->prepare(
                "SELECT meta_value FROM {$this->db->prefix}{$entity->getMetaTable()}
                 WHERE {$entity->getPrimaryKey()} = %d AND meta_key = %s",
                $entityId,
                $key
            )
        );

        return $row['meta_value'] ?? null;
    }

    public function deleteAll(
        HasMeta $entity,
        int $entityId
    ): bool {
        return $this->db->delete(
            $entity->getMetaTable(),
            [$entity->getPrimaryKey() => $entityId]
        );
    }
}