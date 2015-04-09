<?php

/*
$faker = \Faker\Factory::create(\Config::get('app.locale'));
global $is_seasonal;

echo "\n\nSeedy z " . basename(__FILE__) . "\n\n";

return array(
    'Order' => array(
        array(
            'data' => array(
                'date_from' => function() use($faker){
                    $date = $faker->dateTimeBetween('-200 days', 'now');
                    return $date->format('Y-m-d');
                },
                'date_to' => function() use($faker){
                    $date = $faker->dateTimeBetween('now', '+200 days');
                    return $date->format('Y-m-d');
                },
//                'with_weekends' => function(){ return rand()%2; },
                'with_weekends' => 1,
                'weeks' => function() use($faker){ return $faker->randomNumber(1); },
                'price' => function() use($faker){ return $faker->randomNumber(4); },
                'user__id' => function(){
                    return App('User')->where('login', '=', 'admin')->limit(1)->get()->first()->id;
                },
                'city' => 'faker:city',
                'street' => 'faker:streetAddress',
                'street_number' => 'faker:randomNumber',
                'post_code' => 'faker:postcode',
                'local_number' => function() use($faker){ return $faker->randomNumber(3); },
                'floor_number' => function() use($faker){ return $faker->randomNumber(2); },
                'notes' => 'faker:text',
            ),
            'repetitions' => 1,
        ),
    ),
    'OrderTesting' => array(
        array(
            'data' => array(
                'date_delivery'  => function() use($faker){
                    $date = $faker->dateTimeBetween('now', '+30 days');
                    return $date->format('Y-m-d');
                },
                'user__id' => function(){
                    return App('User')->where('login', '=', 'admin')->limit(1)->get()->first()->id;
                },
            ),
            'repetitions' => \DatabaseSeeder::DENSITY * 8
        )
    ),
    'Street' => array(
        array(
            'data' => array(
                'city' => 'faker:city',
                'street' => 'faker:streetAddress',
            ),
            'repetitions' => \DatabaseSeeder::DENSITY * 10
        ),
    ),
    'LocationDelivery' => array(
        array(
            'data'=> array(
                'city' => 'faker:city',
                'district' => 'faker:word',
                'post_code' => 'faker:postcode',
                'street' => 'faker:streetAddress',
                'street_number' => function() use($faker){
                    return $faker->randomNumber(2);
                },
                'access_code' => function(){
                    if(rand()%2) {
                        return strtoupper(uniqid('ACCESS'));
                    }
                    else {
                        return '';
                    }
                },
                'service_access_code' => function(){
                    if(rand()%2) {
                        return strtoupper(uniqid('ACCESS'));
                    }
                    else {
                        return '';
                    }
                }
            ),
            'repetitions' => \DatabaseSeeder::DENSITY * 10
        ),
    ),
    'LocationReport' => array(
        array(
            'data' => array(
                'city' => 'faker:city',
                'street' => 'faker:streetAddress',
                'street_number' => function() use($faker){
                    return $faker->randomNumber(2);
                },
            ),
            'repetitions' => \DatabaseSeeder::DENSITY * 10
        ),
    ),
    'ProductEffect' => array(
        array(
            'data' => array(
                'name' => 'faker:word',
                'description' => 'faker:text',
                'image' => function(){
                    static $arr = array( '1.png', '2.png', '3.png', '4.png', '5.png', '6.png' );
                    $key = array_rand($arr);
                    $val = $arr[$key];
                    return $val;
                },
                'discount_percent' => function(){ return rand(0, 25); }
            ),
            'repetitions' => \DatabaseSeeder::DENSITY * 5
        )
    ),
    'Product' => array(
        array(
            'data' => array(
                'name' => 'faker:word',
                'description' => 'faker:text',
                'price' => function() use($faker){ return $faker->randomNumber(2); },
                'is_visible' => function(){ return rand()%2; },
                'amount_per_serving' => function() use($faker){ return $faker->randomNumber(1); },
                'serving_type' => function(){
                    $arr = array('sztuk','gram','ml','plastrów');
                    return $arr[array_rand($arr)];
                },
                'kcal' => function(){ return rand(25, 800); },
                'protein' => function(){ return rand(25, 800); },
                'fats' => function(){ return rand(25, 800); },
                'carbohydartes' => function(){ return rand(25, 800); },
                'is_seasonal' => function(){ global $is_seasonal; $is_seasonal = rand()%2; return $is_seasonal;  },
                'product_effect__id' => function(){
                    return App('ProductEffect')->orderByRaw("RAND()")->limit(1)->get()->first()->id;
                },
                'category__id' => function(){
                    return App('Category')->orderByRaw("RAND()")->limit(1)->get()->first()->id;
                },
                'sku' => function(){
                    return strtoupper(uniqid());
                }
            ),
            'repetitions' => \DatabaseSeeder::DENSITY * 10,
            'create' => array(
                'ProductImage' => function(){
                    $arr = array( '1.png', '2.png', '3.png', '4.png', '5.png', '6.png' );
                    $data = array();
                    foreach(range(0, rand(0, 9)) as $k=>$v) {
                        $data[] =  array(
                            'image' => $arr[array_rand($arr)],
                            'is_main' => $k==0 ? 1 : 0,
                            'title' => 'faker:word',
                        );
                    }
                    return $data;
                },
                'ProductSeason' => function() use($faker){
                    global $is_seasonal;
                    $data = array();
                    if($is_seasonal) {
                        $data = array(
                            array(
                                'date_from' => function() use($faker){
                                    $date = $faker->dateTimeBetween('-200 days', 'now');
                                    return $date->format('Y-m-d');
                                },
                                'date_to' => function() use($faker){
                                    $date = $faker->dateTimeBetween('now', '+200 days');
                                    return $date->format('Y-m-d');
                                },
                            )
                        );
                    }

                    return $data;
                },
            )
        )
    ),
    'Subscriber' => array(
        array(
            'data' => array(
                'email' => 'faker:email',
                'user__id' => NULL,
                'name' => 'faker:name',
            ),
            'repetitions' => \DatabaseSeeder::DENSITY * 5
        )
    ),
);
*/
