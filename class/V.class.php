<?php
require_once dirname(__FILE__) . '/Creature.class.php'; 

class V extends Creature {

    public function moveFree()
    {
        $me = $this->getPoint();
        $relative = $this->getRelativePoint();
        $relative_x = $relative->getX();
        $relative_y = $relative->getY();

        if ($relative_y !== 0) {
            $move = 'moveUp';

            if ($relative_y > 0) {
                $move = 'moveDown';
            }

            if($this->$move() === true) {
                return true;
            }
        }


        if ($relative_x !== 0) {
            $move = 'moveLeft';

            if ($relative_x > 0) {
                $move = 'moveRight';
            }

            if($this->$move() === true) {
                return true;
            }
        }

        if ($this->moveDown() === true) {
            return true;
        } elseif ($this->moveLeft() === true) {
            return true;
        } elseif ($this->moveUp() === true) {
            return true;
        } elseif ($this->moveRight() === true) {
            return true;
        }

        return false;
    }

}

