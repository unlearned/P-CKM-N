<?php
require_once dirname(__FILE__) . '/Creature.class.php'; 

class Player extends Creature {

    public function move($command) 
    {
        $function = null;

        switch ($command) {
        case '.':
            $function = 'moveStay';
            break;
        case 'j':
            $function = 'moveDown';
            break;
        case 'k':
            $function = 'moveUp';
            break;
        case 'h':
            $function = 'moveLeft';
            break;
        case 'l':
            $function = 'moveRight';
            break;
        default:
            return false;
           break; 
        }

        if ($this->$function()) {
            $this->_eat();
            return true;
        }

       return false; 
    }



    public function _eat()
    {
        $this->_map->delFeed($this->getPoint());
    }



}

