<?php

//krijimi i klases Service per te shfaqur sherbimet e ofruara nga agjensioni turistik

class Service {
    private $img;
    private $title;

    public function __construct($img, $title) {
        $this->img = $img;
        $this->title = $title;
    }

 
    public function render() {
        return "
        <div class='box'>
            <img src='{$this->img}' alt=''>
            <h3>{$this->title}</h3>
        </div>
        ";
    }
}

?>