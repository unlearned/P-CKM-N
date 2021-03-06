<?php
require_once dirname(__FILE__) . '/class/Packman.class.php';

$map =<<< EOT
##########################################################
#........................................................#
#.###.#########.###############.########.###.#####.#####.#
#.###.#########.###############.########.###.#####.#####.#
#.....#########....J.............J.......###.............#
#####.###.......#######.#######.########.###.#######.#####
#####.###.#####J#######.#######.########.###.##   ##.#####
#####.###L#####.##   ##L##   ##.##    ##.###.##   ##.#####
#####.###..H###.##   ##.##   ##.########.###.#######J#####
#####.#########.##   ##L##   ##.########.###.###V....#####
#####.#########.#######.#######..........###.#######.#####
#####.#########.#######.#######.########.###.#######.#####
#.....................L.........########..........R......#
#L####.##########.##.##########....##....#########.#####.#
#.####.##########.##.##########.##.##.##.#########.#####.#
#.................##............##..@.##...............R.#
##########################################################
EOT;


$packman = new Packman(700, 58, 17, $map);

echo $packman->getMapString();

while(true) {
    echo 'your turn [k/j/h/l/.] : ';
    $input = fgets(STDIN,4096);

    $packman->game(trim($input));




}


