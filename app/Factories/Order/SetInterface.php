<?php

namespace App\Factories\Order;

interface SetInterface {
    
     public function getindex();
    
     public function getPONo();
     
     public function orderToSupplier($id);
}
