<?php
class Database {
    protected mysqli $dbh;
    public string $prefix;
    public function __construct() {
        $this->prefix = DB_PREFIX;
        $this->dbh = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_NAME,
            3306
        );
        if ($this->dbh->connect_error) {
            throw new Exception('DB Connection failed: ' . $this->dbh->connect_error);
        }

        $this->dbh->set_charset(DB_CHARSET);
    }

    /* === wpdb-like helpers === */

    public function query(string $sql)
    {
        return $this->dbh->query($sql);
    }

    public function get_results(string $sql): array
    {
        $result = $this->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : array();
    }

    public function get_row(string $sql): ?array
    {
        $result = $this->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    public function get_var(string $sql): object {
        $row = $this->get_row($sql);
        return $row ? array_shift($row) : null;
    }

    public function insert(string $table, array $data): int|false {
        $cols = implode(',', array_keys($data));
        $vals = implode(',', array_fill(0, count($data), '?'));

        $stmt = $this->dbh->prepare(
            "INSERT INTO {$this->prefix}{$table} ($cols) VALUES ($vals)"
        );

        $stmt->bind_param(
            str_repeat('s', count($data)),
            ...array_values($data)
        );

        return $stmt->execute() ? $stmt->insert_id : false;
    }

    public function update(string $table, array $data, array $where): bool {
        $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $cond = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($where)));
        $stmt = $this->dbh->prepare(
            "UPDATE {$this->prefix}{$table} SET $set WHERE $cond"
        );
        $values = array_merge(array_values($data), array_values($where));
        $stmt->bind_param(str_repeat('s', count($values)), ...$values);
        return $stmt->execute();
    }

    public function delete(string $table, array $where): bool {
        $cond = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($where)));
        $stmt = $this->dbh->prepare(
            "DELETE FROM {$this->prefix}{$table} WHERE $cond"
        );
        $stmt->bind_param(
            str_repeat('s', count($where)),
            ...array_values($where)
        );
        return $stmt->execute();
    }

    public function prepare(string $query, ...$args): string { // ...$args means get unlimited arguments as an array
        foreach ($args as $arg) {
            $query = preg_replace('/%s/', "'" . $this->dbh->real_escape_string($arg) . "'", $query, 1);
            $query = preg_replace('/%d/', (int)$arg, $query, 1);
        }
        return $query;
    }
}