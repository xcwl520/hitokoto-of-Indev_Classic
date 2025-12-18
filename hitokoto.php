<?php
// 存储数据的文件
$filename = 'hitokoto.dat';
// 指定页面编码
header('Content-type: text/html; charset=utf-8');

// 检查文件是否存在
if (!file_exists($filename)) {
    if (isset($_GET['json'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => $filename . ' 数据文件不存在'], JSON_UNESCAPED_UNICODE);
    } else {
        die($filename . ' 数据文件不存在');
    }
    exit;
}

// 读取整个数据文件
$data = file_get_contents($filename);

// 按换行符分割成数组
$data = explode(PHP_EOL, $data);

// 过滤空行，确保数组中没有空元素
$data = array_filter($data, function($line) {
    return trim($line) !== '';
});

// 检查是否有有效数据
if (empty($data)) {
    if (isset($_GET['json'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => '数据文件为空或没有有效数据'], JSON_UNESCAPED_UNICODE);
    } else {
        die('数据文件为空或没有有效数据');
    }
    exit;
}

// 重新索引数组，防止array_rand出错
$data = array_values($data);

// 随机获取一行索引
$randomIndex = array_rand($data);
$result = $data[$randomIndex];

// 去除多余的换行符和空白字符
$result = trim(str_replace(array("\r", "\n", "\r\n"), '', $result));

// 如果结果仍然为空，则返回默认值
if (empty($result)) {
    $result = '没有可用的一言数据';
}

// 在每个一言的末尾添加后缀


// 检查是否请求JSON格式
if (isset($_GET['json'])) {
    // 返回JSON格式
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'hitokoto' => $result,
        'from_who' => 'Indev_Classic',
        'from' => 'Indev_Classic名言警句'
    ], JSON_UNESCAPED_UNICODE);
} else {
    $result = $result . ' --Indev_Classic';// 返回普通文本格式，并添加后缀
    $html_result = nl2br(htmlspecialchars($result, ENT_QUOTES, 'UTF-8'));
    echo $html_result;
}
?>
