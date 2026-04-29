<?php
// models/product.php
class Product {
    public int    $id       = 0;
    public string $name     = '';
    public string $desc     = '';
    public float  $price    = 0.0;
    public string $category = '';
    public string $image_url = 'product.jpg';
    public int    $quantity = 0;
    public float  $promo_price = 0.0;
    public string $promo_label = '';

    public function __construct(
        string $name      = '',
        string $desc      = '',
        float  $price     = 0.0,
        string $category  = '',
        string $image_url = 'product.jpg',
        int    $quantity  = 0,
        float  $promo_price = 0.0,
        string $promo_label = ''
    ) {
        $this->name       = $name;
        $this->desc       = $desc;
        $this->price      = $price;
        $this->category   = $category;
        $this->image_url  = $image_url;
        $this->quantity   = $quantity;
        $this->promo_price = $promo_price;
        $this->promo_label = $promo_label;
    }

    public function hasPromo(): bool {
        return $this->promo_price > 0 && $this->promo_price < $this->price;
    }

    public function isInStock(): bool {
        return $this->quantity > 0;
    }
}