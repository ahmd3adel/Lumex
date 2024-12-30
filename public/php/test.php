<?php
class test
{
    public $name;
    public $name2;
    public $name3;
    public $age;
    public function sayHii($name , $age)
    {
        $this->name = $name;
        $this->age = $age;
        return $this;
    }

    public function sayhello($name , $age)
    {
        $this->name = $name;
        $this->age = $age;
        return $this;
    }

}

$greeting = new test();
echo "<pre>";
var_dump($greeting->sayHii('ahmed' , 20));
echo "</pre>";

var_dump(get_class_methods($greeting));
