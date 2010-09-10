<?php
require_once dirname(__FILE__) . '/Point.class.php'; 
require_once dirname(__FILE__) . '/Player.class.php';
require_once dirname(__FILE__) . '/V.class.php';
require_once dirname(__FILE__) . '/H.class.php';
require_once dirname(__FILE__) . '/L.class.php';
require_once dirname(__FILE__) . '/R.class.php';
require_once dirname(__FILE__) . '/J.class.php';

class Packman {

    private $_time_limit; 
    private $_current_time;
    public $_map;
    private $_map_width;
    private $_map_height;
    public  $_objects;
    public $_feed_points;
    private $_logs;

    CONST PLAYERID = 0;


    function __clone()
    {
        $this->_time_limit = $this->_time_limit;
        $this->_current_time = $this->_current_time;
        $this->_map_width = $this->_map_width;
        $this->_map_height = $this->_map_height;
        $this->_logs = $this->_logs;

        $this->_map = unserialize(serialize($this->_map));
        $this->_feed_points = unserialize(serialize($this->_feed_points));
        $this->_objects = unserialize(serialize($this->_objects));
    }





    function __construct($timelimit, $map_width, $map_height, $map) {

        $this->_map = array();
        $this->_objects = array();
        $this->_feed_points = array();
        $this->_current_time = 0;
        $this->_logs = '';

        $this->_time_limit = $timelimit;
        $this->_map_width = $map_width;
        $this->_map_height = $map_height;

        $this->setMap($map);
    }



    function autoGame() {

        global $commands;
        $commands = array('j', 'k', 'l', 'h', '.');

        function autoGame_i($world, $command, $log) {

            global $commands;

            for ($i=1; $i < count($world->_objects); $i++) {
                $world->_objects[$i]->move();
            }

            if (!$world->_objects[0]->move($command)) {
                return;
            }

            $world->incrementTime();
            $log .= $command;

            if ($world->isLose()) {
                echo "lose\n";
                return;
            }
    

            if ($world->isWin()) {
                echo $world->getMapString() . "\n";
                echo $log . "\n";
                exit;
            }
            
            foreach ($commands as $c) {
                autoGame_i(clone $world, $c, $log);
            }
        }
            
            
        foreach ($commands as $command) {
            autoGame_i(clone $this, $command, "");
        }
    }



    function game($command) {
        if (preg_match('/^(j|k|l|h|.)$/', $command) !== 1) {
            return false;
        }

        for ($i=1; $i < count($this->_objects); $i++) {

            $this->_objects[$i]->move();
        }

        $this->_objects[0]->move($command);

        $this->_logs .= $command;

        $this->incrementTime();

        echo $this->getMapString();
        echo 'feed(s) left:'. $this->countFeeds()."\n"; 
        echo 'time left:' . ($this->_time_limit - $this->_current_time) . "\n";
        echo 'log(s):'.$this->_logs."\n";

        if ($this->isLose()) {
            echo 'you lose' . "\n";
            exit;
        }

        if ($this->isWin()) {
            echo 'you win' . "\n";
            echo 'log(s):'.$this->_logs."\n";
            exit;
        }


    }




    public function isLose()
    {
        //timelimt over, you lose
        if ($this->_time_limit < $this->_current_time) {
            return true;
        }

        $player_p = $this->_objects[0]->getPoint();

        $player_pre_p = null;
        
        if ($this->_current_time > 0) {
            $player_pre_p = $this->_objects[0]->getPrePoint();
        }


        for ($i=1; $i < count($this->_objects); $i++) {
            $enemy_p = $this->_objects[$i]->getPoint();

            if ($this->_current_time > 0) {
                $enemy_pre_p = $this->_objects[$i]->getPrePoint();
                if ($player_p->equal($enemy_pre_p) && $player_pre_p->equal($enemy_p)) {
                    return true;
                }
            }

            if ($player_p->equal($enemy_p)) {
                return true;
            }

       }

        return false;
    }


    public function isWin()
    {
        if($this->_time_limit >= $this->_current_time) {

            if ($this->countFeeds() === 0) {
                return true;
            }
        }
        return false;
    }



    public function countFeeds()
    {
        $counter = 0;

        foreach ($this->_feed_points as $x) {
            $counter += count($x);
        }

        return $counter;
    }


    public function delFeed($point)
    {
        $p = $point;
        if (isset($this->_feed_points[$p->getX()][$p->getY()])) {
            unset($this->_feed_points[$p->getX()][$p->getY()]);
        }
    }



    public function countMovable($point)
    {
        $way_chars[] = $this->_map[$point->getX()][$point->getY() - 1];
        $way_chars[] = $this->_map[$point->getX()][$point->getY() + 1];
        $way_chars[] = $this->_map[$point->getX() - 1][$point->getY()];
        $way_chars[] = $this->_map[$point->getX() + 1][$point->getY()];

        $way_counter = 0;
        foreach ($way_chars as $char) {
            if (isset($char) && $char !== '#') {
                $way_counter += 1;
            }
        }
        return $way_counter;
    }



    public function getTime()
    {
        return $this->_current_time;
    }



    public function incrementTime()
    {
        $this->_current_time += 1;
    }



    public function getPlayerPoint()
    {
        return $this->_objects[self::PLAYERID]->getPoint();
    }


    public function canMove($point)
    {
        $p = $point;
        $char = null;

        if (isset($this->_map[$p->getX()][$p->getY()])) {
            $char = $this->_map[$p->getX()][$p->getY()];
        }

        if (isset($char) && $char !== '#') {
            return true;
        }

        return false;
    }



    public function getMapString()
    {
        $map_string = "";

        for ($y=0; $y < $this->_map_height; ++$y) {

            for ($x=0; $x <= $this->_map_width; ++$x) {

                if ($x === $this->_map_width) {
                    $map_string .= "\n";
                } else {

                    if ($this->_map[$x][$y] === " " && isset($this->_feed_points[$x][$y])) {

                       $map_string .= "."; 

                    } else {
                        $map_string .= $this->_map[$x][$y];
                    }

                }
            }
        }

        $map_array = preg_split('//', $map_string, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($this->_objects as $object) {
            $char = get_class($object)==='Player' ? '@' : get_class($object);
            $x = $object->getPoint()->getX();
            $y = $object->getPoint()->getY();

            $position = $x + ($y * ($this->_map_width + 1));
            $map_array[$position] = $char;
        }
        
        $map_string = implode('', $map_array);

        return $map_string;
    }



    public function setMap($map_string)
    {
        $map_array = preg_split('//', $map_string, -1, PREG_SPLIT_NO_EMPTY);

        $player = null;

        $x = 0;
        $y = 0;
        foreach ($map_array as $char) {

            if ($char === "\n") {

                $x = 0;
                $y += 1;
                continue;

            } else if ($char === '.') {

                $this->_feed_points[$x][$y] = true;
                $this->_map[$x][$y] = ' ';
            
            } else if (preg_match('/^(V|H|L|R|J)$/', $char) === 1) {

                $id = count($this->_objects) + 1;
                $this->_objects[$id] = new $char($id, new Point($x, $y), $this); 
                $this->_map[$x][$y] = ' ';

            } else if ($char === '@') {
 
                $player = new Player(self::PLAYERID, new Point($x, $y), $this); 
                $this->_map[$x][$y] = ' ';

            } else {
                $this->_map[$x][$y] = $char;
            }
                $x += 1;
        }

        //player into array named _objects
        $this->_objects[self::PLAYERID] = $player;
    }
}

