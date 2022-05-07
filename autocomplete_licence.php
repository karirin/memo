<?php

$licence_list = array(
    'ITパスポート',
    '基本情報技術者',
    '応用情報技術者',
    'ITストラテジスト',
    'ITサービスマネージャー',
    'プロジェクトマネージャー',
    'システム監査技術者',
    'エンベデッドシステムスペシャリスト',
    'システムアーキテクト',
    'データベーススペシャリスト',
    'ネットワークスペシャリスト',
    '情報セキュリティスペシャリスト'
);

$words = array();

// 現在入力中の文字を取得
$term = (isset($_GET['term']) && is_string($_GET['term'])) ? $_GET['term'] : '';

// 部分一致で検索
foreach ($licence_list as $word) {
    if (mb_stripos($word, $term) !== FALSE) {
        $words[] = $word;
    }
}

header("Content-Type: application/json; charset=utf-8");
echo json_encode($words);