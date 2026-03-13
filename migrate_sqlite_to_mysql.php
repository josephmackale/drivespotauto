<?php

$sqlitePath = __DIR__ . '/database/database.sqlite';

$mysqlHost = '127.0.0.1';
$mysqlPort = 3306;
$mysqlDb   = 'drivespotauto';
$mysqlUser = 'drivespotauto';
$mysqlPass = 'DrivespotAuto254#';

$tables = [
    'users',
    'brands',
    'categories',
    'products',
    'product_attributes',
    'product_attribute_values',
    'product_images',
];

try {
    $sqlite = new PDO('sqlite:' . $sqlitePath);
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mysql = new PDO(
        "mysql:host={$mysqlHost};port={$mysqlPort};dbname={$mysqlDb};charset=utf8mb4",
        $mysqlUser,
        $mysqlPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $mysql->exec("SET FOREIGN_KEY_CHECKS=0");

    foreach ($tables as $table) {
        echo "\n=== Migrating {$table} ===\n";

        $rows = $sqlite->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            echo "No rows found in {$table}, skipping.\n";
            continue;
        }

        $mysqlColumnsRaw = $mysql->query("SHOW COLUMNS FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        $mysqlColumns = array_map(fn($col) => $col['Field'], $mysqlColumnsRaw);

        $sqliteColumns = array_keys($rows[0]);

        $commonColumns = array_values(array_filter(
            $mysqlColumns,
            fn($col) => in_array($col, $sqliteColumns, true)
        ));

        $columnList = '`' . implode('`,`', $commonColumns) . '`';
        $placeholders = implode(',', array_fill(0, count($commonColumns), '?'));

        $stmt = $mysql->prepare(
            "INSERT INTO `{$table}` ({$columnList}) VALUES ({$placeholders})"
        );

        foreach ($rows as $row) {
            $values = [];
            foreach ($commonColumns as $col) {
                $values[] = $row[$col] ?? null;
            }
            $stmt->execute($values);
        }

        $count = $mysql->query("SELECT COUNT(*) FROM `{$table}`")->fetchColumn();
        echo "Inserted {$count} row(s) into {$table}.\n";

        if (in_array('id', $mysqlColumns, true)) {
            $maxId = (int) $mysql->query("SELECT COALESCE(MAX(id), 0) FROM `{$table}`")->fetchColumn();
            $mysql->exec("ALTER TABLE `{$table}` AUTO_INCREMENT = " . ($maxId + 1));
        }
    }

    $mysql->exec("SET FOREIGN_KEY_CHECKS=1");
    echo "\nMigration completed successfully.\n";

} catch (Throwable $e) {
    if (isset($mysql)) {
        try {
            $mysql->exec("SET FOREIGN_KEY_CHECKS=1");
        } catch (Throwable $ignored) {}
    }

    fwrite(STDERR, "\nERROR: " . $e->getMessage() . "\n");
    exit(1);
}
