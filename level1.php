<?php
require_once dirname(__FILE__) . '/class/Packman.class.php';

$map =<<< EOT
###########
#.V..#..H.#
#.##...##.#
#L#..#..R.#
#.#.###.#.#
#....@....#
###########
EOT;


$packman = new Packman(50, 11, 7, $map);

echo $packman->getMapString();

while(true) {
    echo 'your turn [k/j/h/l/.] : ';
    $input = fgets(STDIN,4096);

    $packman->game(trim($input));




}


