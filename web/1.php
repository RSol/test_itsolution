<?php

$dbh = new PDO('mysql:host=localhost;dbname=test_itsolution', 'root', '');

/**
 * Create table and fill data
 * @param PDO $dbh
 */
function createTable100x100(PDO $dbh)
{

    $dbh->query("CREATE TABLE `table100x100` (
        `x` TINYINT(3) UNSIGNED NOT NULL,
        `y` TINYINT(3) UNSIGNED NOT NULL,
        `val` INT(10) UNSIGNED NOT NULL
    )");

    $values = [];
    foreach (range(1, 100) as $x) {
        foreach (range(1, 100) as $y) {
            $val = rand(1, 99999);
            $values[] = "({$x}, {$y}, {$val})";
        }
    }
    $values = implode(",", $values);

    $dbh->query("INSERT INTO table100x100 (x, y, val) VALUES {$values}");

}

//function log($msg) {
//    if (php_sapi_name() == 'cli') {
//        echo $msg;
//    }
//}

if (!($result = $dbh->query("SHOW TABLES LIKE 'table100x100'")) || ($result->rowCount() == 0)) {
    createTable100x100($dbh);
}

function getValues(PDO $dbh)
{
    return $dbh->query("SELECT * FROM table100x100", PDO::FETCH_ASSOC)->fetchAll();
}

function formatValues($values) {
    $result = [];
    foreach ($values as $row) {
        $result[$row['x']][$row['y']] = $row['val'];
    }
    return $result;
}

function findEdited($values, $post) {
    $formatValues = formatValues($values);
    $new = [];
    foreach ($post as $index => $item) {
        if ($dif = array_diff_assoc($item, $formatValues[$index])) {
            $new[$index] = $dif;
        }
    }

    return $new;
}

function editNew(PDO $dbh, $new) {
    $update = $dbh->prepare("UPDATE table100x100 SET val=:val WHERE x=:x AND y=:y");
    foreach ($new as $x => $row) {
        foreach ($row as $y => $val) {
            if (!$update->execute([
                ':val' => $val ?: 0,
                ':x' => $x,
                ':y' => $y,
            ])) {
                echo "<p style='color: red;'>Field 'val_{$x}_{$y}' value '{$val}' is invalid - don't saved</p>";
            }
        }
    }
}

$values = [];

if (isset($_POST['table100x100'])) {
    $values = getValues($dbh);

    if ($new = findEdited($values, $_POST['table100x100'])) {
        editNew($dbh, $new);
    }

    $values = getValues($dbh);
}


function createHTMLTable($values)
{
    $newRow = 1;
    $rows = [];
    $cols = [];
    foreach ($values as $item) {
        if ($newRow <> $item['x']) {
            $cols[] = '<td>' . implode('</td><td>', $rows) . '</td>';
            $rows = [];
            $newRow = $item['x'];
        }
        $val = $item['val'] ?: '';
        $label = "val_{$item['x']}_{$item['y']}";
        $rows[] = "<label for='{$label}' style='font-size:10px;'>{$label}</label><input id='{$label}' name='table100x100[{$item['x']}][{$item['y']}]' value='{$val}'>";
    }
    $cols[] = '<td>' . implode('</td><td>', $rows) . '</td>';

    return '<table><tr>' . implode("</tr>\n<tr>", $cols) . '</tr></table>';
}

function createHTMLForm($inside) {
    return "<form method='post'>{$inside}<input type='submit' value='submit'></form>";
}

$values = $values ?: getValues($dbh);

echo createHTMLForm(createHTMLTable($values));