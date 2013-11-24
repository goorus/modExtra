<?php

$repl1 = 'YourName';
$repl2 = strtolower($repl1);

$find1 = 'modExtra';
$find2 = strtolower($find1);

$source_path = __DIR__ . '/';
$target_path = dirname(__DIR__) . '/' . $repl1 . '/';

if (file_exists($target_path)) {
    throw new Exception('Target dir already exists');
}

$plan = array(
    '_build',
    'assets',
    'core',
);
$plan_i = 0;
while ($plan_i < count($plan)) {
    $local_path = $plan[$plan_i] . '/';
    $full_path = $source_path . $local_path;
    $files = scandir($full_path);
    foreach ($files as $file) {
        if ($file[0] === '.') {
            continue;
        }
        $src_name = $local_path . $file;
        $dst_name = str_replace($find2, $repl2, $src_name);
        echo $dst_name, "\n";
        if (is_dir($source_path . $src_name)) {
            mkdir($target_path . $dst_name, 0777, true);
            $plan[] = $src_name;
        } else {
            $dir = dirname($target_path . $dst_name);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $content = str_replace(
                array($find1, $find2),
                array($repl1, $repl2),
                file_get_contents($source_path . $src_name)
            );
            file_put_contents($target_path . $dst_name, $content);
        }
    }
    $plan_i++;
}
