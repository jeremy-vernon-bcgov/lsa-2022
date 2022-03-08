<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** INSERT TEMPLATE
        DB::table('awards')->insert([
        'id' => ,
        'name' => '',
        'short_name' => '' ,
        'description' => '',
        'milestone' => ,
        'image_url' => '',
        'sort_order' => ,
        'quantity' => -1]
        );



         */




        /* 25 Milestone Awards */

        DB::table('awards')->insert(['id'=> 1,'name' => 'Cross® Pen',
            'short_name' => '25-pen' ,
            'description' => 'This Cross® Calais chrome and blue lacquer rollerball pen is lightweight with a bold profile. Pen has "25 Years" engraved on the lid.',
            'image_url' => '25/25-pen.png',
            'milestone' => 25,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 2,'name' => 'Bugatti® Padfolio',
            'short_name' => '25-case' ,
            'image_url' => '25/25-bugatti-padfolio.png',
            'description' => 'This recycled and synthetic leather case has "25 Years" debossed on the front. It has adjustable brackets which hold most tablet models, including three sizes of iPad. TThe cover includes a pocket for a smartphone, USB dongle and pen holders, card slots, an ID window and writing pad.',
            'milestone' => 25,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 3,'name' => 'Passport and luggage tag set',
            'short_name' => '25-tags' ,
            'image_url' => '25/25-passport-luggage.png',
            'description' => 'This navy blue, genuine leather passport holder and luggage tag set has "25 Years" debossed on the front. Both items feature a magnetic closure.',
            'milestone' => 25,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 4,'name' => 'Pearl earrings',
            'short_name' => '25-pearl-earrings' ,
            'image_url' => '25/25-pearl-earrings.png',
            'description' => 'These sterling silver freshwater pearl earrings have an accent of gold. They are made in Vancouver, B.C. by Howling Dog Artisan Jewellery. Size: 2.5 cm long by 1 cm wide.',
            'milestone' => 25,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 5,'name' => 'Slate serving board',
            'short_name' => '25-serving-board' ,
            'image_url' => '25/25-serving-board.png',
            'description' => 'This serving board comes with a stainless steel cheese knife and and three cheese markers. Engraved with the BC Coat of Arms and "25 Years".',
            'milestone' => 25,
            'sort_order' => 5,
            'quantity' => -1]);

        /* 30 Milestone Awards */

        DB::table('awards')->insert(['id'=> 6,'name' => 'Rothsbury silver table clock',
            'short_name' => '30-clock' ,
            'description' => 'The solid aluminum case of this timepiece has polished bevels with a brushed aluminum top and sides. It’s lightweight yet durable, and it has felt on the bottom to protect your surfaces. Place the Rothbury silver table clock on your mantel or desk as a functional accent piece. It has classic Roman numerals and a silver-finished skeleton movement dial for refined style.',
            'image_url' => '30/30--clock.png',
            'milestone' => 30,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 8,'name' => 'Sterling silver earrings',
            'short_name' => '30-silver-earrings' ,
            'description' => 'These sterling silver drop earrings are individually handcrafted by B.C. artists Nancy Dawson and Vincent Henson of Silver Eagles Design Studio in Alert Bay. They come in a presentation box with, "In recognition of thirty years of service" engraved on the top. These earrings are intended to coordinate with the 35 year sterling silver bracelet.',
            'milestone' => 30,
            'image_url' => '30/30-silver-earrings',
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert([
            'name' => 'Appalachian Sherpa Blanket',
            'short_name' => '30-blanket' ,
            'description' => 'This high-end plush blanket has "30 Years" embroidered on the corner. It’s made of faux suede on one side and soft Sherpa fleece on the other.',
            'milestone' => 30,
            'image_url' => '',
            'sort_order' => 5,
            'quantity' => -1]
        );
        DB::table('awards')->insert([
            'name' => '"Elements" Art Print',
            'short_name' => '30-elements' ,
            'description' => 'By B.C. artist Derek Thomas. \n\n This print is printed on fine art card stock, then framed with off-green matting and inner contrast colour and dark-grey frame. \n\n "In recognition of thirty years of service" is engraved on a plaque. \n\n',
            'milestone' => 30,
            'image_url' => '',
            'sort_order' => 5,
            'quantity' => -1]
        );
        DB::table('awards')->insert([
            'name' => '"Loon Dance" Art Print',
            'short_name' => '30-LoonDance' ,
            'description' => '',
            'milestone' => 30,
            'image_url' => '',
            'sort_order' => 5,
            'quantity' => -1]
        );


        /* 35 Milestone Awards */

        DB::table('awards')->insert(['name' => 'Bulova® watch',
            'short_name' => '35-bulova' ,
            'image_url' => '35/35-watches.png',
            'milestone' => 35,
            'sort_order' => 5,
            'options' => '{"straps": [{"value" : "black-leather", "text" : "Black Leather"},{"value" : "brown-leather", "text" : "Brown Leather"},{"value" : "plated", "text" : "Plated"}], "faces": [{"value" : "gold", "text" : "Gold"},{"value" : "silver" , "text" : "Silver"},{"value" : "two-toned" , "text" : "Two-Toned"}],"sizes" : [{"value" : "29mm" , "text" : "29 mm diameter face"},{"value" : "38mm" , "text" : "38 mm diameter face"}],"engraving": true}',
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Bushnell® Prime binoculars',
            'short_name' => '35-binoculars' ,
            'image_url' => '35/35-binoculars.png',
            'sort_order' => 5,
            'milestone' => 35,
            'quantity' => -1]);

        DB::table('awards')->insert(['name' => 'Sterling silver bracelet',
            'description' => 'This sterling silver bracelet is handcrafted by B.C. artists Nancy Dawson and Vincent Henson of Silver Eagles Design Studio, in Alert Bay. It comes in a box with, "In recognition of thirty five years of service',
            'short_name' => '35-bracelet' ,
            'image_url' => '35/35-bracelet.png',
            'milestone' => 35,
            'sort_order' => 5,
            'options' =>  '{"sizes": [{"value": "size-a", "text":"Fits 6 1/2\" - 7 1/2\" wrist"},{"value": "size-b", "text":"Fits 7 1/2\" - 8 1/2\" wrist"}]}',
            'quantity' => -1]);

        DB::table('awards')->insert([
            'name' => 'Pottery Oyster Bowl',
            'short_name' => '35-bowl' ,
            'description' => '',
            'milestone' => 35,
            'image_url' => '',
            'sort_order' => 5,
            'quantity' => -1]
        );

        /* 40 Milestone Awards */

        DB::table('awards')->insert(['name' => 'Graham Bracket III Howard Miller mantel Clock',
            'short_name' => '40-bracket-clock' ,
            'image_url' => '',
            'milestone' => 40,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Genuine diamond pendant and chain',
            'short_name' => '40-diamond-necklace' ,
            'image_url' => '40/40-diamond-necklace.png',
            'milestone' => 40,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Genuine diamond stud earrings',
            'short_name' => '40-diamond-earrings' ,
            'image_url' => '40/40-diamond-earrings.png',
            'milestone' => 40,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Pottery Bowl and Mugs',
            'short_name' => '40-bowl-mugs' ,
            'image_url' => '',
            'milestone' => 40,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => '“Somewhere on the Beach” art print',
            'short_name' => '40-beach-print' ,
            'image_url' => '',
            'milestone' =>40,
            'sort_order' => 5,
            'quantity' => -1]);

        /* 45 Milestone Awards */

        DB::table('awards')->insert([
                'name' => '"Whale Tail" Art Print',
                'short_name' => '45-whale-tail' ,
                'description' => '',
                'milestone' => 45,
            'image_url' => '',
            'sort_order' => 5,
            'quantity' => -1]
        );
        DB::table('awards')->insert(['name' => 'Akron Howard Miller mantel clock',
            'short_name' => '45-akron-clock' ,
            'image_url' => '45/45-akron-clock.png',
            'milestone' => 45,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Thule® Subterra Luggage',
            'short_name' => '45-luggage' ,
            'image_url' => '45-thule-luggage.png',
            'milestone' => 45,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Carson® Telescope',
            'short_name' => '45-telescope' ,
            'milestone' => 45,
            'image_url' => '45/45-telescope.png',
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Genuine Diamond Stud Earrings',
            'short_name' => '45-diamond-earrings' ,
            'image_url' => '45/45-diamond-earrings',
            'milestone' => 45,
            'sort_order' => 5,
            'quantity' => -1]);

        DB::table('awards')->insert(['name' => 'Sterling Silver Bracelet and Earrings',
            'short_name' => '45-earrings-bracelet' ,
            'image_url' => '45/45-earrings-bracelet.png',
            'options' =>  '{"sizes": [{"value": "size-a", "text":"Fits 6 1/2\" - 7 1/2\" wrist"},{"value": "size-b", "text":"Fits 7 1/2\" - 8 1/2\" wrist"}]}',
            'milestone' => 45,
            'sort_order' => 5,
            'quantity' => -1]);
     ;
        DB::table('awards')->insert(['name' => 'Freshwater Pearl Necklace',
            'short_name' => '45-pearl-necklace' ,
            'image_url' => '45/45-pearl-necklace.png',
            'milestone' => 45,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert([
             'name' => 'Freshwater Pearl Bracelet (8 in.)',
             'short_name' => '45-pearl-bracelet',
             'image_url' => '',
             'milestone' => 45,
             'sort_order' => 5,
             'quantity' => -1
         ]);



        /* 50 Milestone Awards */

        DB::table('awards')->insert(['name' => 'Sterling Silver Gemstone Set',
            'short_name' => '50-gemstone-set' ,
            'image_url' => '50/50-earrings-bracelet.png',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Crystal Pitcher and Glass Set',
            'short_name' => '50-pitcher' ,
            'image_url' => '50/50-crystal-pitcher-glasses.png',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Citizen® Axiom Eco-Drive Watch',
            'short_name' => '50-citizen-watch' ,
            'image_url' => '50/50-citizen-axiom-watch.png',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Thule® Subterra Luggage',
            'short_name' => '50-luggage' ,
            'image_url' => '50/50-thule-luggage.png',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Leather Messenger Bag',
            'short_name' => '50-messenger-bag' ,
            'image_url' => '50/50-messenger-bag.png',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Freshwater Pearl Necklace',
            'short_name' => '50-pearl-necklace' ,
            'image_url' => '50/50-pearl-necklace.png',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Genuine Diamond Stud Earrings',
            'short_name' => '50-diamond-earrings' ,
            'image_url' => '50/50-diamond-earrings.png',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Sterling Silver Bracelet and Earrings',
            'short_name' => '50-earrings-bracelet' ,
            'image_url' => '50/50-earrings-bracelet.png',
            'options' =>  '{"sizes": [{"value": "size-a", "text":"Fits 6 1/2\" - 7 1/2\" wrist"},{"value": "size-b", "text":"Fits 7 1/2\" - 8 1/2\" wrist"}]}',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1
        ]);
        DB::table('awards')->insert([
           'name' => 'Eastmont Wall clock',
           'short_name' => '50-clock',
           'image_url' => '',
           'milestone' => 50,
           'sort_order' => 5,
           'quantity' => -1
        ]);


        /* PECSF DONATIONS */

        DB::table('awards')->insert(['id'=> 70,'name' => 'PECSF Donation',
                                                'short_name' => '25-pecsf' ,
                                                'image_url' => '25/1-pecsf.png',
                                                'options' =>  '{"pecsf": "true"}',
                                                'milestone' => 25,
                                                'sort_order' => 1,
                                                'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 71,'name' => 'PECSF Donation',
                                                'short_name' => '30-pecsf' ,
                                                'image_url' => '30/1-pecsf.png',
                                                'options' =>  '{"pecsf": "true"}',
                                                'milestone' => 30,
                                                'sort_order' => 1,
                                                'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 72,'name' => 'PECSF Donation',
                                                'short_name' => '35-pecsf' ,
                                                'image_url' => '35/1-pecsf.png',
                                                'options' =>  '{"pecsf": "true"}',
                                                'milestone' => 35,
                                                'sort_order' => 1,
                                                'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 73,'name' => 'PECSF Donation',
                                                'short_name' => '40-pecsf' ,
                                                'image_url' => '40/1-pecsf.png',
                                                'options' =>  '{"pecsf": "true"}',
                                                'milestone' => 40,
                                                'sort_order' => 1,
                                                'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 74,'name' => 'PECSF Donation',
                                                 'short_name' => '45-pecsf' ,
                                                 'image_url' => '45/1-pecsf.png',
                                                 'options' =>  '{"pecsf": "true"}',
                                                 'milestone' => 45,
                                                 'sort_order' => 1,
                                                 'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 75,'name' => 'PECSF Donation',
                                                'short_name' => '50-pecsf' ,
                                                'image_url' => '50/1-pecsf.png',
                                                'options' =>  '{"pecsf": "true"}',
                                                'milestone' => 50,
                                                'sort_order' => 1,
                                                'quantity' => -1]);

    }
}
