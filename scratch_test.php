<?php
require __DIR__ . '/vendor/autoload.php';
$max = 1;
try {
    $res = collect(range(2, $max))->mapWithKeys(fn($i) => ["guest_nik_$i" => null])->toArray();
    echo "Success: ";
    print_r($res);
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
