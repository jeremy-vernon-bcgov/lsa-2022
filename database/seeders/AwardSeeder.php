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
            'image_url' => 'wp-content/uploads/2022/03/pen-compass.jpg',
            'milestone' => 25,
            'options' => '{"certificate": true}',
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 2,'name' => 'Bugatti® Padfolio',
            'short_name' => '25-case' ,
            'image_url' => 'wp-content/uploads/2022/03/bugatti-padfolio-compass.jpg',
            'description' => 'This recycled and synthetic leather case has "25 Years" debossed on the front. It has adjustable brackets which hold most tablet models, including three sizes of iPad. TThe cover includes a pocket for a smartphone, USB dongle and pen holders, card slots, an ID window and writing pad.',
            'milestone' => 25,
            'options' => '{"certificate": true}',
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 3,'name' => 'Passport and luggage tag set',
            'short_name' => '25-tags' ,
            'image_url' => 'wp-content/uploads/2022/03/luggage-tag-compass.jpg',
            'description' => 'This navy blue, genuine leather passport holder and luggage tag set has "25 Years" debossed on the front. Both items feature a magnetic closure.',
            'milestone' => 25,
            'options' => '{"certificate": true}',
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 4,'name' => 'Pearl earrings',
            'short_name' => '25-pearl-earrings' ,
            'image_url' => 'wp-content/uploads/2022/03/pearl-earrings-compass.jpg',
            'description' => 'These sterling silver freshwater pearl earrings have an accent of gold. They are made in Vancouver, B.C. by Howling Dog Artisan Jewellery. Size: 2.5 cm long by 1 cm wide.',
            'milestone' => 25,
            'options' => '{"certificate": true}',
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 5,'name' => 'Slate serving board',
            'short_name' => '25-serving-board' ,
            'image_url' => 'wp-content/uploads/2022/03/slate-serving-tray-compass.jpg',
            'description' => 'This serving board comes with a stainless steel cheese knife and and three cheese markers. Engraved with the BC Coat of Arms and "25 Years".',
            'milestone' => 25,
            'options' => '{"certificate": true}',
            'sort_order' => 5,
            'quantity' => -1]);

        /* 30 Milestone Awards */

        DB::table('awards')->insert(['id'=> 6,'name' => 'Rothsbury silver table clock',
            'short_name' => '30-clock' ,
            'description' => 'The solid aluminum case of this timepiece has polished bevels with a brushed aluminum top and sides. It’s lightweight yet durable, and it has felt on the bottom to protect your surfaces. Place the Rothbury silver table clock on your mantel or desk as a functional accent piece. It has classic Roman numerals and a silver-finished skeleton movement dial for refined style.',
            'image_url' => 'wp-content/uploads/2022/03/rothsbury-clock-compass.jpg',
            'milestone' => 30,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 8,'name' => 'Sterling silver earrings',
            'short_name' => '30-silver-earrings' ,
            'description' => 'These sterling silver drop earrings are individually handcrafted by B.C. artists Nancy Dawson and Vincent Henson of Silver Eagles Design Studio in Alert Bay. They come in a presentation box with, "In recognition of thirty years of service" engraved on the top. These earrings are intended to coordinate with the 35 year sterling silver bracelet.',
            'milestone' => 30,
            'image_url' => 'wp-content/uploads/2022/03/silver-earrings-compass.jpg',
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert([
            'name' => 'Appalachian Sherpa Blanket',
            'short_name' => '30-blanket' ,
            'description' => 'This high-end plush blanket has "30 Years" embroidered on the corner. It’s made of faux suede on one side and soft Sherpa fleece on the other.',
            'milestone' => 30,
            'image_url' => 'wp-content/uploads/2022/03/blanket-compass.jpg',
            'sort_order' => 5,
            'quantity' => -1]
        );
        DB::table('awards')->insert([
            'name' => '"Elements" Art Print',
            'short_name' => '30-elements' ,
            'description' => 'By B.C. artist Derek Thomas. \n\n This print is printed on fine art card stock, then framed with off-green matting and inner contrast colour and dark-grey frame. \n\n "In recognition of thirty years of service" is engraved on a plaque. \n\n',
            'milestone' => 30,
            'image_url' => 'wp-content/uploads/2022/03/elements-painting-compass.jpg',
            'sort_order' => 5,
            'quantity' => -1]
        );
        DB::table('awards')->insert([
            'name' => '"Loon Dance!!" Art Print',
            'short_name' => '30-LoonDance' ,
            'description' => 'By B.C. artist Isaac Bignell \n\n Printed on canvas, this piece is framed in black with off-white matting and black inner-matting. \n\n "In recognition of thirty years of service" is engraved on a plaque.',
            'milestone' => 30,
            'image_url' => 'wp-content/uploads/2022/03/loon-dance-painting-compass.jpg',
            'sort_order' => 5,
            'quantity' => -1]
        );


        /* 35 Milestone Awards */

        DB::table('awards')->insert(['name' => 'Bulova® watch',
            'short_name' => '35-bulova' ,
            'description' => 'This watch features the BC Coat of Arms on the dial and is personalized with your name and "35 years" engraved on the back. \n\n It comes in a choice of gold, silver or two-toned construction with a plated, black or brown leather strap.',
            'image_url' => 'wp-content/uploads/2022/03/watches-compass.jpg',
            'milestone' => 35,
            'sort_order' => 5,
            'options' => '{"straps": [{"value" : "black-leather", "text" : "Black Leather"},{"value" : "brown-leather", "text" : "Brown Leather"},{"value" : "plated", "text" : "Plated"}], "faces": [{"value" : "gold", "text" : "Gold"},{"value" : "silver" , "text" : "Silver"},{"value" : "two-toned" , "text" : "Two-Toned"}],"sizes" : [{"value" : "29mm" , "text" : "29 mm diameter face"},{"value" : "38mm" , "text" : "38 mm diameter face"}],"engraving": true}',
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Bushnell® Prime binoculars',
            'short_name' => '35-binoculars' ,
            'image_url' => 'wp-content/uploads/2022/03/binoculars-compass.jpg',
            'sort_order' => 5,
            'milestone' => 35,
            'quantity' => -1]);

        DB::table('awards')->insert(['name' => 'Sterling silver bracelet',
            'description' => 'This sterling silver bracelet is handcrafted by B.C. artists Nancy Dawson and Vincent Henson of Silver Eagles Design Studio, in Alert Bay. It comes in a box with, "In recognition of thirty five years of service',
            'short_name' => '35-bracelet' ,
            'image_url' => 'wp-content/uploads/2022/03/bracelet-compass.jpg',
            'milestone' => 35,
            'sort_order' => 5,
            'options' =>  '{"sizes": [{"value": "size-a", "text":"Fits 6 1/2\" - 7 1/2\" wrist"},{"value": "size-b", "text":"Fits 7 1/2\" - 8 1/2\" wrist"}]}',
            'quantity' => -1]);

        DB::table('awards')->insert([
            'name' => 'Pottery Oyster Bowl',
            'short_name' => '35-bowl' ,
            'description' => 'Deep oven, microwave and dishwasher save. This stunning show piece will be a treasure on your table for years to come. Please note, this is a handmade pottery shell bowl and colour tones may vary slightly from piece to piece. \n\n Made in BC by Mussels and More pottery.',
            'milestone' => 35,
            'image_url' => 'wp-content/uploads/2022/03/oyster-shell-bowl-compass.jpg',
            'sort_order' => 5,
            'quantity' => -1]
        );

        /* 40 Milestone Awards */

        DB::table('awards')->insert(['name' => 'Graham Bracket III Howard Miller mantel Clock',
            'short_name' => '40-bracket-clock',
            'description' => 'This beautiful Howard Miller mantle clock is finished in Windows cherry wood. The brass finished dial offers a silver chapter ring and decorative corner spandrels. Quartz battery-operated German movement plays full Westminster or Ave Maria chimes with strike at hour and optional 4/4 chime feature, playing at 1/4, 1/2 and 3/4 chimes. Volume control and nighttime volume reductions option included. "In recognition of forty years of service" is engraved on a plaque. \n\n Overall dimensions: 10.5" x 14.25" x 6.5"  ',
            'image_url' => 'wp-content/uploads/2022/03/Graham-bracket-clock-compass.jpg',
            'milestone' => 40,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Genuine diamond pendant and chain',
            'short_name' => '40-diamond-necklace' ,
            'image_url' => 'wp-content/uploads/2022/03/diamond-necklace-compass.jpg',
            'description' => 'This necklace features a 10 kt white gold, four claw pendant and 18 inch box chain. The round, brilliant cut diamonds are 0.20 carat total weight. It comes in a presentation box with, "In recognition of fort years of service" engraved on the top.',
            'milestone' => 40,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Genuine diamond stud earrings',
            'short_name' => '40-diamond-earrings' ,
            'image_url' => 'wp-content/uploads/2022/03/diamond-earrings-compass.jpg',
            'description' => 'These 14 kt white gold stud earrings feature brilliant round cut diamonds, 0.25 carat total weight. The Earrings have four white gold claws and butterfly backs. Presented in a box engraved with "In recognition of forty years of service."',
            'milestone' => 40,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Pottery Bowl and Mugs',
            'short_name' => '40-bowl-mugs' ,
            'description' => 'Simply stunning, this three piece set is just gorgeous! 20.5 x 8.5 x 4 inches. Made of high-fired porcelain clay - oven, microwave and dishwasher safe. Please note, this is a handmade pottery shell bowl and colour tones may vary slightly from piece to piece. \n\n Made in B.C. by Mussles and more pottery.',
            'image_url' => 'wp-content/uploads/2022/03/mussel-bowl-mug-set-compass.jpg',
            'milestone' => 40,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => '“Somewhere on the Beach” art print',
            'short_name' => '40-beach-print' ,
            'description' => 'By top B.C. artist Wani Pasion \n\n This art print is double matted in cream and natural beige, accented with a beautiful silver frame.\n\n "In recognition of forty years of service" engraved on a plaque. \n\n Size approx: 17 x 22 in.',
            'image_url' => 'wp-content/uploads/2022/03/somewhere-on-the-beach-compass.jpg',
            'milestone' =>40,
            'sort_order' => 5,
            'quantity' => -1]);

        /* 45 Milestone Awards */

        DB::table('awards')->insert([
                'name' => '"Whale Tail" Art Print',
                'short_name' => '45-whale-tail' ,
                'description' => 'By B.C. artist Pauline Bull \n\n Printed on canvas, framed in black with double groove. \n\n  "In recognition of fifty years of service" is engraved on a plaque. \n\n  Size: 18 x 24 inches.',
                'milestone' => 45,
            'image_url' => 'wp-content/uploads/2022/03/Whale-tail-print-compass.jpg',
            'sort_order' => 5,
            'quantity' => -1]
        );
        DB::table('awards')->insert(['name' => 'Akron Howard Miller mantel clock',
            'short_name' => '45-akron-clock' ,
            'image_url' => 'wp-content/uploads/2022/03/Akron-mantel-clock-compass.jpg',
            'milestone' => 45,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Thule® Subterra Luggage',
            'short_name' => '45-luggage' ,
            'description' => 'This durable luggage from Thule is made from water-resistant materials. The tough, oversized wheels and V-Tubing telescoping handles make for smooth and easy transport. It has a divided main compartment to separate clothes and has top, side and bottom handles. Complies with carry-on requirements for most airlines. Size: 21.7 x 13.8 x 7.9 inches.',
            'milestone' => 45,
            'image_url' => 'wp-content/uploads/2022/03/45-thule-luggage-compass.jpg',
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Carson® Telescope',
            'short_name' => '45-telescope' ,
            'milestone' => 45,
            'image_url' => 'wp-content/uploads/2022/03/telescope-red-planet-compass.jpg',
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Genuine Diamond Stud Earrings',
            'short_name' => '45-diamond-earrings' ,
            'description' => 'These 14 kt white gold stud earrings feature brilliant round cut diamonds, 0.25 carat total weight. The Earrings have four white gold claws and butterfly backs. Presented in a box engraved with "In recognition of fifty years of service."',
            'image_url' => 'wp-content/uploads/2022/03/diamond-earrings-compass.jpg',
            'milestone' => 45,
            'sort_order' => 5,
            'quantity' => -1]);

        DB::table('awards')->insert(['name' => 'Sterling Silver Bracelet and Earrings',
            'short_name' => '45-earrings-bracelet' ,
            'image_url' => 'wp-content/uploads/2022/03/Earrings-bracelet-compass.jpg',
            'options' =>  '{"sizes": [{"value": "size-a", "text":"Fits 6 1/2\" - 7 1/2\" wrist"},{"value": "size-b", "text":"Fits 7 1/2\" - 8 1/2\" wrist"}]}',
            'milestone' => 45,
            'sort_order' => 5,
            'quantity' => -1]);
     ;
        DB::table('awards')->insert(['name' => 'Freshwater Pearl Necklace',
            'short_name' => '45-pearl-necklace' ,
            'image_url' => 'wp-content/uploads/2022/03/45-pearl-necklace-compass.jpg',
            'description' => 'Round freshwater pearls (6 - 6.5 mm) with a 14k yellow gold fisheye clasp. Comes with an engraved plate on the presentation box, "In recognition of fifty years of service." \n\n 18 inches long.',
            'milestone' => 45,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert([
             'name' => 'Freshwater Pearl Bracelet (8 in.)',
             'short_name' => '45-pearl-bracelet',
             'image_url' => 'wp-content/uploads/2022/03/pearl-bracelet-compass.jpg',
             'milestone' => 45,
             'sort_order' => 5,
             'quantity' => -1
         ]);



        /* 50 Milestone Awards */

        DB::table('awards')->insert(['name' => 'Sterling Silver Gemstone Set',
            'short_name' => '50-gemstone-set' ,
            'image_url' => 'wp-content/uploads/2022/03/Earrings-bracelet-compass.jpg',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Crystal Pitcher and Glass Set',
            'short_name' => '50-pitcher' ,
            'image_url' => 'wp-content/uploads/2022/03/Crystal-set-with-glasses-compass.jpg',
            'description' => 'Chesley, lead-free crystal, featuring a 50 oz. pitcher. Comes with four highball glasses, each 13 oz.',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Citizen® Axiom Eco-Drive Watch',
            'short_name' => '50-citizen-watch' ,
            'image_url' => 'wp-content/uploads/2022/03/Citizen-axiom-watch-large.jpg',
            'description' => 'This 40mm watch features a black dial and black leather strap, a date feature and splash resistant casing. Comes in a stainless steel case. ',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Thule® Subterra Luggage',
            'short_name' => '50-luggage' ,
            'description' => 'This durable luggage from Thule is made from water-resistant materials. The tough, oversized wheels and V-Tubing telescoping handles make for smooth and easy transport. It has a divided main compartment to separate clothes and has top, side and bottom handles. Complies with carry-on requirements for most airlines. Size: 21.7 x 13.8 x 7.9 inches.',
            'image_url' => 'wp-content/uploads/2022/03/45-thule-luggage-compass.jpg',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Leather Messenger Bag',
            'short_name' => '50-messenger-bag' ,
            'image_url' => 'wp-content/uploads/2022/03/messenger-bag-compass.jpg',
            'description' => 'Full grained distressed water buffalo tanned leather. Holds a standard laptop or tablet. Flap cover has magnetic closure. This bag has extra inside pockets and a canvas lining. Shoulder strap is adjustable.',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Freshwater Pearl Necklace',
            'short_name' => '50-pearl-necklace' ,
            'image_url' => 'wp-content/uploads/2022/03/45-pearl-necklace-compass.jpg',
            'description' => 'Round freshwater pearls (6 - 6.5 mm) with a 14k yellow gold fisheye clasp. Comes with an engraved plate on the presentation box, "In recognition of fifty years of service." \n\n 18 inches long.',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Genuine Diamond Stud Earrings',
            'short_name' => '50-diamond-earrings' ,
            'image_url' => 'wp-content/uploads/2022/03/diamond-earrings-compass.jpg',
            'description' => 'These 14 kt white gold stud earrings feature brilliant round cut diamonds, 0.25 carat total weight. The Earrings have four white gold claws and butterfly backs. Presented in a box engraved with "In recognition of fifty years of service."',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1]);
        DB::table('awards')->insert(['name' => 'Sterling Silver Bracelet and Earrings',
            'short_name' => '50-earrings-bracelet' ,
            'image_url' => 'wp-content/uploads/2022/03/Earrings-bracelet-compass.jpg',
            'description' => 'This matched set of sterling silver cuff bracelet and drop earrings are handcrafted by BC artists Nancy Dawson and Vincent Henson of Silver Eagles Design Studio. \n\n  Presented in a box engraved with, "In recognition of fifty years of service."',
            'options' =>  '{"sizes": [{"value": "size-a", "text":"Fits 6 1/2\" - 7 1/2\" wrist"},{"value": "size-b", "text":"Fits 7 1/2\" - 8 1/2\" wrist"}]}',
            'milestone' => 50,
            'sort_order' => 5,
            'quantity' => -1
        ]);
        DB::table('awards')->insert([
           'name' => 'Eastmont Wall clock',
           'short_name' => '50-clock',
           'description' => 'This beautiful Howard Miller wall clock is finished in Windsor cherry wood. This traditional pendulum wall clock features a broad pediment molding accented with deep embossing. The Roman numeral dial is surrounded by a polished brass bezel and features wrought black hands. The polished brass pendulum features a decorative lyre. Quartz battery operated, movement plays full Westminster or Ave Maria chimes. Volume control and nighttime volume reduction option are available. \n\n 11.5" x 16.5" x 7" \n\n "In recognition of fifty years of service," is engraved on a plaque.',
           'image_url' => 'wp-content/uploads/2022/03/Eastmont-wall-clock-compass.jpg',
           'milestone' => 50,
           'sort_order' => 5,
           'quantity' => -1
        ]);


        /* PECSF DONATIONS */

        DB::table('awards')->insert(['id'=> 70,'name' => 'PECSF Donation',
                                                'short_name' => '25-pecsf' ,
                                                'image_url' => 'wp-content/uploads/2022/03/pecsf-logo-compass.jpg',
                                                'options' =>  '{"pecsf": true, "certificate": true}',
                                                'milestone' => 25,
                                                'description' => 'Donate to the Provincial Employees Community Services Fund, an annual fundraising campaign that helps BC Public Service employees support local charities.',
                                                'sort_order' => 1,
                                                'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 71,'name' => 'PECSF Donation',
                                                'short_name' => '30-pecsf' ,
                                                'image_url' => 'wp-content/uploads/2022/03/pecsf-logo-compass.jpg',
                                                'options' =>  '{"pecsf": true}',
                                                'milestone' => 30,
                                                'description' => 'Donate to the Provincial Employees Community Services Fund, an annual fundraising campaign that helps BC Public Service employees support local charities.',
                                                'sort_order' => 1,
                                                'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 72,'name' => 'PECSF Donation',
                                                'short_name' => '35-pecsf' ,
                                                'image_url' => 'wp-content/uploads/2022/03/pecsf-logo-compass.jpg',
                                                'options' =>  '{"pecsf": true}',
                                                'milestone' => 35,
                                                'description' => 'Donate to the Provincial Employees Community Services Fund, an annual fundraising campaign that helps BC Public Service employees support local charities.',
                                                'sort_order' => 1,
                                                'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 73,'name' => 'PECSF Donation',
                                                'short_name' => '40-pecsf' ,
                                                'image_url' => 'wp-content/uploads/2022/03/pecsf-logo-compass.jpg',
                                                'options' =>  '{"pecsf": true}',
                                                'milestone' => 40,
                                                'description' => 'Donate to the Provincial Employees Community Services Fund, an annual fundraising campaign that helps BC Public Service employees support local charities.',
                                                'sort_order' => 1,
                                                'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 74,'name' => 'PECSF Donation',
                                                 'short_name' => '45-pecsf' ,
                                                 'image_url' => 'wp-content/uploads/2022/03/pecsf-logo-compass.jpg',
                                                 'options' =>  '{"pecsf": true}',
                                                 'milestone' => 45,
                                                 'description' => 'Donate to the Provincial Employees Community Services Fund, an annual fundraising campaign that helps BC Public Service employees support local charities.',
                                                 'sort_order' => 1,
                                                 'quantity' => -1]);
        DB::table('awards')->insert(['id'=> 75,'name' => 'PECSF Donation',
                                                'short_name' => '50-pecsf' ,
                                                'image_url' => 'wp-content/uploads/2022/03/pecsf-logo-compass.jpg',
                                                'options' =>  '{"pecsf": true}',
                                                'milestone' => 50,
                                                'description' => 'Donate to the Provincial Employees Community Services Fund, an annual fundraising campaign that helps BC Public Service employees support local charities.',
                                                'sort_order' => 1,
                                                'quantity' => -1]);

    }
}
