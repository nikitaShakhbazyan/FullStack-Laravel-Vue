<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'Laptop Dell XPS 15',
                'slug' => 'laptop-dell-xps-15',
                'description' => 'High-performance laptop with Intel Core i7 processor',
                'price' => 1299.99,
                'stock' => 15,
                'image' => '/images/laptop-dell.jpg',
            ],
            [
                'category_id' => 1,
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'Latest iPhone with A17 Pro chip and advanced camera system',
                'price' => 999.99,
                'stock' => 25,
                'image' => '/images/iphone-15.jpg',
            ],
            [
                'category_id' => 1,
                'name' => 'Sony WH-1000XM5 Headphones',
                'slug' => 'sony-wh-1000xm5',
                'description' => 'Premium noise-cancelling wireless headphones',
                'price' => 399.99,
                'stock' => 30,
                'image' => '/images/sony-headphones.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Nike Air Max 270',
                'slug' => 'nike-air-max-270',
                'description' => 'Comfortable running shoes with air cushioning',
                'price' => 159.99,
                'stock' => 50,
                'image' => '/images/nike-shoes.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Levi\'s 501 Original Jeans',
                'slug' => 'levis-501-jeans',
                'description' => 'Classic straight-fit jeans',
                'price' => 89.99,
                'stock' => 40,
                'image' => '/images/levis-jeans.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Adidas Hoodie',
                'slug' => 'adidas-hoodie',
                'description' => 'Comfortable cotton hoodie',
                'price' => 59.99,
                'stock' => 60,
                'image' => '/images/adidas-hoodie.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Clean Code by Robert Martin',
                'slug' => 'clean-code-book',
                'description' => 'A handbook of agile software craftsmanship',
                'price' => 39.99,
                'stock' => 100,
                'image' => '/images/clean-code.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'The Pragmatic Programmer',
                'slug' => 'pragmatic-programmer',
                'description' => 'Your journey to mastery',
                'price' => 44.99,
                'stock' => 80,
                'image' => '/images/pragmatic-programmer.jpg',
            ],
            [
                'category_id' => 4,
                'name' => 'Robot Vacuum Cleaner',
                'slug' => 'robot-vacuum',
                'description' => 'Smart robot vacuum with mapping technology',
                'price' => 299.99,
                'stock' => 20,
                'image' => '/images/robot-vacuum.jpg',
            ],
            [
                'category_id' => 4,
                'name' => 'Garden Tool Set',
                'slug' => 'garden-tool-set',
                'description' => 'Complete 10-piece garden tool set',
                'price' => 79.99,
                'stock' => 35,
                'image' => '/images/garden-tools.jpg',
            ],
            [
                'category_id' => 5,
                'name' => 'Yoga Mat Premium',
                'slug' => 'yoga-mat-premium',
                'description' => 'Non-slip yoga mat with carrying strap',
                'price' => 34.99,
                'stock' => 70,
                'image' => '/images/yoga-mat.jpg',
            ],
            [
                'category_id' => 5,
                'name' => 'Dumbbell Set 20kg',
                'slug' => 'dumbbell-set-20kg',
                'description' => 'Adjustable dumbbell set for home workout',
                'price' => 149.99,
                'stock' => 25,
                'image' => '/images/dumbbells.jpg',
            ],
            [
                'category_id' => 1,
                'name' => 'Samsung Galaxy Tab S9',
                'slug' => 'samsung-galaxy-tab-s9',
                'description' => '11-inch Android tablet with S Pen',
                'price' => 749.99,
                'stock' => 18,
                'image' => '/images/samsung-tab.jpg',
            ],
            [
                'category_id' => 5,
                'name' => 'Tennis Racket Pro',
                'slug' => 'tennis-racket-pro',
                'description' => 'Professional tennis racket with carbon frame',
                'price' => 189.99,
                'stock' => 12,
                'image' => '/images/tennis-racket.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Design Patterns Book',
                'slug' => 'design-patterns-book',
                'description' => 'Elements of reusable object-oriented software',
                'price' => 49.99,
                'stock' => 55,
                'image' => '/images/design-patterns.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
