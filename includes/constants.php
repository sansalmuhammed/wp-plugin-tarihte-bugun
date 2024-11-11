<?php
if (!defined('ABSPATH')) exit;

define('TARIHTE_BUGUN_PATH', plugin_dir_path(dirname(__FILE__)));
define('TARIHTE_BUGUN_URL', plugin_dir_url(dirname(__FILE__)));

// Olay kategorileri ve ikonları
const OLAY_KATEGORILERI = [
    'dogum' => [
        'icon' => '👶',
        'title' => 'Doğum',
        'class' => 'kategori-dogum'
    ],
    'olum' => [
        'icon' => '⚰️',
        'title' => 'Ölüm',
        'class' => 'kategori-olum'
    ],
    'savas' => [
        'icon' => '⚔️',
        'title' => 'Savaş',
        'class' => 'kategori-savas'
    ],
    'baris' => [
        'icon' => '🕊️',
        'title' => 'Barış',
        'class' => 'kategori-baris'
    ],
    'kesif' => [
        'icon' => '🔬',
        'title' => 'Keşif/İcat',
        'class' => 'kategori-kesif'
    ],
    'spor' => [
        'icon' => '🏆',
        'title' => 'Spor',
        'class' => 'kategori-spor'
    ],
    'kultur' => [
        'icon' => '🎭',
        'title' => 'Kültür/Sanat',
        'class' => 'kategori-kultur'
    ],
    'siyasi' => [
        'icon' => '🏛️',
        'title' => 'Siyasi',
        'class' => 'kategori-siyasi'
    ],
    'doga' => [
        'icon' => '🌍',
        'title' => 'Doğa Olayı',
        'class' => 'kategori-doga'
    ],
    'genel' => [
        'icon' => '📅',
        'title' => 'Genel',
        'class' => 'kategori-genel'
    ]
];