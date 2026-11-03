<?php

namespace Core;

use PDO;

class Db
{
    private PDO $connection;

    public function __construct(string $database, string $schema)
    {
        try {
            $this->createSchema($database, $schema);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Instantiate sqlite db
     * Create schema if tables don't exist
     * I wouldn't normally do this here but as a separate installation script
     */
    private function createSchema(string $database, string $schema): void
    {
        $createSchema = false;
        if (!file_exists($database)) {
            $createSchema = true;
        }

        $this->connection = new PDO('sqlite:' . $database);

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($createSchema) {
            $schemaSQL = file_get_contents($schema);

            $this->connection->exec($schemaSQL);
        }
    }

    public function insert(string $table, array $data): int
    {
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');

        $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $placeholders) . ')';

        $statement = $this->connection->prepare($sql);
        $statement->execute(array_values($data));

        return $this->connection->lastInsertId();
    }

    public function fetchBy(string $table, string $field, string $value): array|false
    {
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $field . ' = ?';

        $statement = $this->connection->prepare($sql);
        $statement->execute([$value]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchFilteredActions(?array $filters): array|false
    {
        $partials = [];
        $filterValues = [];
        if (is_array($filters)) {
            foreach ($filters as $field => $value) {
                $partial = '';
                if ($field === 'user') {
                    $userRow = $this->fetchBy('users', 'username', $value);

                    $partial .= 'actions.userId = ?';

                    if ($userRow) {
                        $filterValues[] = $userRow['id'];
                    } else {
                        $filterValues[] = 0; // non-existent user
                    }
                } else {
                    $partial .= 'actions.' . $field . ' = ?';
                    $filterValues[] = $value;
                }

                $partials[] = $partial;
            }

            $partialSql = ' WHERE ' . implode(' AND ', $partials);
        } else {
            $partialSql = '';
        }

        $sql = 'SELECT actions.date, users.username AS user, actions.action, actions.data FROM actions '
            . ' LEFT JOIN users ON users.id = actions.userId'
            . $partialSql
            . ' ORDER BY date ASC';

        $statement = $this->connection->prepare($sql);
        $statement->execute(is_array($filters) ? $filterValues: null);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchGraphReport(int $userId): array|false
    {
        $sql = "SELECT "
            . "date, "
            . "SUM(CASE WHEN action = 'view-page' AND data = 'a' THEN 1 ELSE 0 END) AS 'page_views_a', "
            . "SUM(CASE WHEN action = 'view-page' AND data = 'b' THEN 1 ELSE 0 END) AS 'page_views_b', "
            . "SUM(CASE WHEN action = 'button-click' AND data = 'Buy a cow' THEN 1 ELSE 0 END) AS 'button_clicks_buy', "
            . "SUM(CASE WHEN action = 'button-click' AND data = 'Download' THEN 1 ELSE 0 END) AS 'button_clicks_download' "
            . "FROM actions "
            . "WHERE userId = ? "
            . "GROUP BY date "
            . "ORDER BY date ASC";

        $statement = $this->connection->prepare($sql);
        $statement->execute([$userId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}