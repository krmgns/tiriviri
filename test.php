<?php
// Tests
require(__DIR__.'/Tiriviri.php');

header('Content-Type: text/plain; charset=utf-8');

$words = array(
    'Ali', 'Kerem', 'Tuğçe', 'Murat',
    'Facebook', 'Ev', 'Av', 'Odun', 'Göl',
    'Araba', 'Antalya', 'Çatak',
);

$last = '';
foreach ($words as $word) {
    printf("%s'%s\n", $word,  Tiriviri::run($word, Tiriviri::CMD_EA));
    printf("%s'%s\n", $word,  Tiriviri::run($word, Tiriviri::CMD_II));
    printf("%s'%s\n", $word,  Tiriviri::run($word, Tiriviri::CMD_ININ));
    printf("%s'%s\n", $word,  Tiriviri::run($word, Tiriviri::CMD_DEDA));
    // // more...
    printf("%s'%sn\n", $word, Tiriviri::run($word, Tiriviri::CMD_DEDA));
    printf("%s'%ski\n", $word, Tiriviri::run($word, Tiriviri::CMD_DEDA));
    if ($last != $word) {
        $last = $word;
        printf("-\n");
    }
}
