<?php
//http://phpsadness.com/sad/50

class A
{
    public function __clone()
    {
        clone $this;
    }
}

clone new A();