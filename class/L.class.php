<?php
require_once dirname(__FILE__) . '/Creature.class.php'; 

class L extends Creature {

    public function moveFree()
    {
        $pre_p = $this->getPrePoint();
        $now_p = $this->getPoint();

        $x_v = $now_p->getX() - $pre_p->getX();
        $y_v = $now_p->getY() - $pre_p->getY();


        $moves = array(); //実行手順を入れておく

        if ($y_v === 0) {

            if($x_v > 0) {
                //from left to right
                $moves[] = 'moveUp';    //1.left
                $moves[] = 'moveRight'; //2.front
                $moves[] = 'moveDown';  //3.right

            } else {
                //from right to left
                $moves[] = 'moveDown'; //1.left
                $moves[] = 'moveLeft'; //2.front
                $moves[] = 'moveUp';   //3.right
            }


        } elseif ($x_v === 0) {

            if($y_v > 0) {
                //move up to down
                $moves[] = 'moveRight';  //1.left
                $moves[] = 'moveDown';   //2.front
                $moves[] = 'moveLeft';   //3.right
            } else {
                //move down to up
                $moves[] = 'moveLeft';   //1.left
                $moves[] = 'moveUp';     //2.front
                $moves[] = 'moveRight';  //3.right
            }
        }

        foreach ($moves as $move) {
            if ($this->$move()) {
                return true;
            }
        }

        return false;
    }

}

