<?php
require_once '../db.php';

$order = $_POST['order'] ?? 'asc';

$packages = [
    [ "id"=>1, "country"=>"India", "price"=>949, "image"=>"images/Arizona.jpg", "desc"=>"Journey with us, where every moment becomes an unforgettable adventure." ],
    [ "id"=>2, "country"=>"Switzerland", "price"=>799, "image"=>"images/NewYork.avif", "desc"=>"Journey with us, where every moment becomes an unforgettable adventure." ],
    [ "id"=>3, "country"=>"Latvia", "price"=>699, "image"=>"images/egjipt.jpg", "desc"=>"Journey with us, where every moment becomes an unforgettable adventure." ]
];

usort($packages, function($a, $b) use ($order) {
    return $order === 'asc' ? $a['price'] <=> $b['price'] : $b['price'] <=> $a['price'];
});

foreach($packages as $pkg) {
    echo '<div class="box">';
    echo '<div class="image"><img src="'.$pkg['image'].'" alt="Package"></div>';
    echo '<div class="content">';
    echo '<h3>'.$pkg['country'].' <b>$'.$pkg['price'].'</b></h3>';
    echo '<p>'.$pkg['desc'].'</p>';
    echo '<button class="btn book-btn" data-id="'.$pkg['id'].'">Book Now</button>';
    echo '</div></div>';
}
?>