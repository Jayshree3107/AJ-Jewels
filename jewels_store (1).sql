-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2024 at 03:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jewels_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '1234', '2024-10-22 08:26:46');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`) VALUES
(1, 'Earrings', 'Classic and modern designs, perfect for every occasion. Image: A close-up of gold hoops and diamond studs.', 'earrings_bg.jpeg'),
(2, 'Pendants', 'A stunning selection of pendants featuring intricate designs and vibrant gemstones.', 'Pendants_bg.jpeg'),
(3, 'Bracelets', ' From sleek bangles to charm bracelets, timeless wristwear.', 'Bracelets_bg.jpeg'),
(4, 'Rings', ' Stunning rings for every style, from elegant solitaires to unique designs.', 'rings_bg.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `billing_name` varchar(100) DEFAULT NULL,
  `billing_email` varchar(100) DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `shipping_cost`, `grand_total`, `payment_method`, `billing_name`, `billing_email`, `billing_address`, `created_at`, `status`) VALUES
(32, 2, 1098.00, 50.00, 1148.00, 'Cash on Delivery', 'Anjalee  Amin', 'anjalee@gmail.com', 'B-504B, Shapath IV, Opp. Karnavati Club, S.G. Highway, Prahlad Nagar, Ahmedabad, Gujarat 380015, India', '2024-10-25 04:54:38', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 32, 6, 1, 399.00),
(2, 32, 23, 1, 699.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `image`) VALUES
(1, 'Elysian Echo Ring', 'Elevate your style with our exquisite ring crafted with the finest materials and timeless design. Each ring exudes elegance and sophistication, making it the perfect accessory for any occasion. Indulge in luxury with our luxurious ring which is guaranteed to make a statement wherever you go.', 679.00, 4, 'RING1.png'),
(2, 'Square Link Studded Gold Ring', 'Elevate your style with our impeccable Square Link Studded Gold Ring. Crafted with precision and attention to detail, this mesmerizing piece effortlessly exudes elegance and sophistication. A true testament to luxurious design, this ring is a must-have addition to your jewelry collection.', 479.00, 4, 'ring2.png'),
(3, 'Silver Hug Promise Ring - Adjustable - Anti Tarnish', 'This Hug Ring is perfect for daily wear, it is shiny silver and you\'ll always feel like your loved ones are close to you! This beautiful ring is perfect for any occasion. It\'s comfortable to wear and will go with any outfit. It is anti-tarnish silver, so it is durable and strong.  With a unique twisted design, the Silver Hug Promise Ring is sure to be cherished for years to come. Incase you\'re looking for a promise ring, this one is perfect for you!', 349.00, 4, 'ring3.png'),
(4, 'Rimba Silver Couple\'s Rings', 'Looking for a ring that is both stylish and versatile? Look no further than our Rimba Silver Stackable Ring. The mesmerizing design of this ring is perfect for layering with other pieces or wearing on its own. Made with durable silver, this ring is sure to become a staple in your accessories collection.', 999.00, 4, 'ring4.png'),
(5, 'Solitaire Silver Ring', 'Elevate your style with the mesmerizing JJ Solitaire Silver Ring. Crafted with attention to detail, this exquisite piece will add a touch of elegance to any outfit. Whether you wear it on its own or stack it with other rings, this ring is sure to make a statement.', 850.00, 4, 'ring5.png'),
(6, 'Gossamer Mary Ring', 'Indulge in the charm of our beautiful ring, a true masterpiece that redefines luxury. Fashioned from high-quality materials, this striking ring boasts a brilliant centerpiece, expertly adorned with shimmering gemstones. Its intricate design and comfortable lightweight construction make it perfect for those who appreciate understated elegance. Be prepared to turn heads wherever you go, as this exquisite ring effortlessly enhances your natural beauty with a touch of sophistication.', 399.00, 4, 'ring6.png'),
(7, 'Champagne Self-Love Chain Ring', 'This Chain Ring is a powerful symbol, serving as a constant reminder to embrace the beautiful changes our bodies undergo in every stage of life. Due to its resizing capability to fit any finger size, the chain ring emerges as an exceptional choice for a thoughtful gift, perfectly suited for various occasions. ', 400.00, 4, 'ring7.png'),
(8, 'Acacia Sunshine Gold Ring', 'Adorn your finger with the enchanting Acacia Sunshine Gold Ring, a dainty and pretty accessory that will set you apart from the crowd. With its intricate design and eye-catching gold finish, this ring is a must-have for any stylish individual. Embrace the beauty and elegance of this piece and let it brighten your day with every wear.', 530.00, 4, 'ring8.png'),
(9, 'Opalescent Chain Ring', 'This Chain Ring is a powerful symbol, serving as a constant reminder to embrace the beautiful changes our bodies undergo in every stage of life. Due to its resizing capability to fit any finger size, the chain ring emerges as an exceptional choice for a thoughtful gift, perfectly suited for various occasions. Made with PREMIUM metal and 14K Gold plating\r\n', 530.00, 4, 'ring9.png'),
(10, 'Chroma Sparkle Ring ', 'Chroma Sparkle Ring - Lilac in Green is more than just a piece of jewelry; it\'s a celebration of nature\'s beauty and the vibrant energy of life. Whether worn as a symbol of renewal and growth or as a stunning accessory to complement any ensemble, this exquisite ring is sure to captivate hearts and inspire admiration with its radiant beauty and timeless elegance.', 499.00, 4, 'ring10.png'),
(11, 'Malia Studded Silver Ring', 'The Malia Studded Silver Ring is a dazzling addition to your jewelry collection. With its mesmerizing design, this ring is sure to catch the eye of anyone who sees it. Perfect for adding a touch of elegance to any outfit, this ring is a must-have accessory.\r\n', 450.00, 4, 'ring11.png'),
(12, 'Daisy On Date Crystal Spinner Ring', 'Elevate your cherished moments with the enchanting Daisy On Date Crystal Ring. May the glistening crystals illuminate your special occasions, creating a radiant and unforgettable experience. Whether you\'re treating yourself or a loved one, this stunning piece radiates elegance and charm. Embrace the beauty and splendor that this ring embodies, and let it become a symbol of love and joy in your life.', 349.00, 4, 'ring12.png'),
(13, 'Amor Evil Eye', 'Get ready to fall in love with our stunningly ring! Crafted with utmost precision and attention to detail, this one-of-a-kind accessory is a constant reminder of your unique style. Lightweight and comfortable, this versatile ring easily transitions from daytime chic to evening glamour. Make a statement and let your inner radiance shine through with this exquisite piece of jewelry!', 369.00, 4, 'ring13.png'),
(14, 'Crystal Gold Ring', 'This Maeve Crystal Gold Ring is truly mesmerizing! The stunning crystal center surrounded by delicate gold accents creates a beautifully elegant piece that will add a touch of glamour to any outfit. Perfect for a night out or simply to elevate your everyday look.', 369.00, 4, 'ring14.png'),
(15, 'Shava Studded Gold Ring', 'Looking for a chic accessory to complete your look? Look no further than the Shava Studded Gold Ring. With its eye-catching design and polished finish, this ring is a good choice for those who appreciate quality craftsmanship and timeless beauty.', 439.00, 4, 'ring15.png'),
(16, 'Crystal Studded Silver ', 'Immerse yourself in the lavish allure of the Ella Crystal Adorned Silver Ring. With its enchanting style and shimmering crystals, this ring will elegantly elevate your look and make a lasting statement.', 429.00, 4, 'ring16.png'),
(17, 'Coralie Break Studded Gold Ring', 'Add a touch of sophistication to your jewelry collection with the Coralie Break Studded Gold Ring. This stunning piece features a mesmerizing studded design that will instantly elevate any outfit. Made with high-quality materials and expert craftsmanship, this ring is sure to become a favorite accessory for years to come.', 399.00, 4, 'ring17.png'),
(18, 'Coralie Break Studded Gold Ring', 'Add a touch of sophistication to your jewelry collection with the Coralie Break Studded Gold Ring. This stunning piece features a mesmerizing studded design that will instantly elevate any outfit. Made with high-quality materials and expert craftsmanship, this ring is sure to become a favorite accessory for years to come.', 399.00, 4, 'ring17.png'),
(19, 'Soft Studded Crown Gold Ring', 'Experience the beauty of our Soft Studded Crown Gold Ring, a timeless piece that exudes grace and elegance. The exquisite craftsmanship and attention to detail make this ring a true work of art. The lustrous gold finish adds a touch of luxury, while the studded crown design adds a modern twist. Elevate your outfit with this good ring and make a confident fashion statement that is sure to impress.', 439.00, 4, 'ring18.png'),
(20, 'Aarna Black And White Enamel Heart Gold Ring', 'Embrace elegance and grace with the Aarna Black And White Enamel Heart Gold Ring. This exquisite piece features a charming heart motif embellished with beautiful black and white enamel details. The lustrous gold band complements the design, creating a luxurious finish that is both timeless and sophisticated. Elevate your look with this pretty ring that exudes sophistication and style.', 389.00, 4, 'ring19.png'),
(21, 'Bowy Bow Luxury Gold Earring', 'Introducing our exquisite Bowy Bow Luxury Gold Earrings, crafted with elegance and sophistication in mind. Made from high-quality materials, these earrings are perfect for adding a touch of luxury to any outfit.', 339.00, 1, 'earring1.png'),
(22, 'Hoop Earrings', 'Stunning Triple Layer Quirky Hoop Earrings by Salty. All heads will surely turn, as you rock this stunning piece\r\nThis exquisite piece is part of our In Trend collection and is a must-have for every woman. It pairs well with your outfit and is suitable for all occasions. A perfect gift for her for birthdays, anniversaries, and all celebrations', 159.00, 1, 'earring2.png'),
(23, 'Palatial Floral Drop Gold Earring', 'Elevate your jewelry collection with our Palatial Floral Drop Gold Earrings. The delicate floral motif paired with the luxurious gold creates a truly timeless piece that will never go out of style. Lightweight and easy to wear, these earrings are perfect for both casual and formal occasions.', 699.00, 1, 'earring3.png'),
(24, 'Captivating Luxury Silver Earring', 'Elevate your style with our Captivating Luxury Silver Earrings. Crafted with stunning detail and polished to perfection, these earrings are sure to turn heads wherever you go. With a timeless design and lightweight feel, you\'ll never want to take them off.', 599.00, 1, 'earring4.png'),
(25, 'Diamond Butterfly Stud Earrings', 'Beautiful Diamond Butterfly Stud Earring Asymmetric Tassel Drop by Salty. Accessorize like a true diva as you adorn this\r\nThis exquisite piece is part of our In Trend collection, and is a must have for every woman. It pairs well with your outfit and is suitable for all occasions. A perfect gift for her for birthdays, anniversaries and all celebrations', 249.00, 1, 'earring5.png'),
(26, 'Posh Night Clover Studs', 'Introducing our mesmerizing Clover Earrings, a testament to unparalleled grace and elegance. Crafted with meticulous attention to detail, these exquisite earrings embody a timeless charm that effortlessly complements any ensemble. The delicate clover-shaped design, exudes a captivating allure that will leave onlookers awe-inspired, these earrings offer a comfortably lightweight feel, ensuring a seamless transition from daytime sophistication to evenings of opulence. Elevate your ensemble with the sophistication and refinement that only our Clover Earrings can bring, radiating an aura of timeless luxury.', 349.00, 1, 'earring6.png'),
(27, 'Tactile Heart Gold Earring', 'Embrace timeless elegance with our Tactile Heart Gold Earrings. Handcrafted with precision and passion, these exquisite earrings are a symbol of sophistication and style. The intricate detailing and luxurious exclusive finish make them a standout accessory for any occasion. Elevate your ensemble and showcase your impeccable taste with the Tactile Heart Gold Earrings.', 399.00, 1, 'earring7.png'),
(28, 'Waterdrop Shape Ribbon Dangle Zircon Earrings', 'attractive Waterdrop Shape Ribbon Dangle Zircon Earrings by Salty. Designed for the modern woman team it with an elegant top or solids to create the perfect ensemble\r\nThis exquisite piece is part of our In Trend collection, and is a must have for every woman. It pairs well with your outfit and is suitable for all occasions. A perfect gift for her for birthdays, anniversaries and all celebrations', 199.00, 1, 'earring8.png'),
(29, 'Gail Silver Studded Earring', 'Create a timeless look with the Gail Silver Studded Earrings, a must-have for anyone who loves to sparkle and shine. These earrings are the perfect finishing touch for any outfit, adding a touch of pretty charm to your overall ensemble.', 599.00, 1, 'earring9.png'),
(30, 'Pumpkin & Bones Earrings', 'Embrace the whimsical spirit of Halloween with our Pumpkin & Bones Earrings. Crafted with exquisite attention to detail, these spooky yet stylish earrings feature intricately carved pumpkins and delicate bone accents. Elevate your seasonal ensemble with this luxurious accessory that effortlessly combines fun and fashion.', 699.00, 1, 'earring10.png'),
(31, 'Champagne Wishes Studs', 'Introducing our mesmerizing Clover Earrings, a testament to unparalleled grace and elegance. Crafted with meticulous attention to detail, these exquisite earrings embody a timeless charm that effortlessly complements any ensemble. The delicate clover-shaped design, exudes a captivating allure that will leave onlookers awe-inspired, these earrings offer a comfortably lightweight feel, ensuring a seamless transition from daytime sophistication to evenings of opulence. Elevate your ensemble with the sophistication and refinement that only our Clover Earrings can bring, radiating an aura of timeless luxury.', 349.00, 1, 'earring11.png'),
(32, 'Sweet Sparkles Earrings', 'Indulge in luxury with these stunning earrings that exude sophistication and class. Featuring a timeless design and impeccable craftsmanship, these earrings are bound to turn heads wherever you go. Elevate your style and make a statement with these beautiful earrings that are sure to become a staple in your jewelry collection.', 799.00, 1, 'earring12.png'),
(33, 'Curved Twist Beauty Gold Earrings', 'Experience the everlasting elegance of our Curved Twist Beauty Gold Earrings. Crafted with a distinctive curved design and a radiant gold finish, these earrings are essential for the sophisticated individual seeking to make a bold fashion statement. Whether adorning yourself for a grand occasion or simply aiming to elevate your daily ensemble, these exquisite earrings are guaranteed to exude a luxurious charm that will captivate all who behold them.', 869.00, 1, 'earring13.png'),
(34, 'Monarch Magic Hoops', 'Indulge in the ultimate elegance with our 14k gold plated earrings. These earrings are a beautiful blend of sophistication and charm, meticulously crafted to capture attention wherever you go. The lightweight design ensures a comfortable wear, allowing you to enjoy their exceptional beauty all day long. The 14k gold plating creates a mesmerizing shimmer, reflecting your unique sense of style. Elevate any look, from casual to formal, with these stunning earrings that will make you feel like a true fashion icon.', 859.00, 1, 'earring14.png'),
(35, 'Oceanic Opulence Earrings -Silver', 'Indulge in luxury with our Fine Earrings, a symbol of class and refinement. Expertly designed and meticulously crafted, these earrings exude glamour and timeless beauty. Elevate any outfit with these exquisite pieces that will make you feel confident and empowered, no matter the occasion.', 569.00, 1, 'earring15.png'),
(36, 'Baggy Gold Hoop Earring', 'Elevate your style with our best-selling Baggy Gold Hoop Earrings. These elegant hoops are perfect for adding a touch of glamour to any outfit. Whether you\'re dressing up for a special occasion or simply running errands, these lightweight earrings will become your go-to accessory.', 649.00, 1, 'earring16.png'),
(37, 'Country Retreat Gold Hoop Earring', 'If you\'re looking for the best hoop earrings to complete your look, look no further than our Country Retreat Gold Hoop Earrings. Crafted with care and attention to detail, these earrings will become your go-to accessory for a touch of sophistication. Comfortable and stylish, they are perfect for all-day wear.', 619.00, 1, 'earring17.png'),
(38, 'Rococo Pastel Luxury Gold Earring', 'Add a touch of elegance to your ensemble with our Rococo Pastel Luxury Gold Earrings. These stunning earrings are designed to make you feel like royalty, with their luxurious finish and pastel accents. Whether you\'re dressing up for a special occasion or just want to add some sparkle to your everyday look, these earrings are sure to impress.', 599.00, 1, 'earring18.png'),
(39, 'Leidy Marble Gold Hoop Earring', 'Experience pure indulgence with the exquisite Leidy Marble Gold Hoop Earrings. Crafted with a captivating blend of marble and gold, these earrings are a must-have for those who appreciate the art of luxury. Elevate your style effortlessly with these elegant hoops, adding a touch of glamour and sophistication to any ensemble.\r\n', 279.00, 1, 'earring19.png'),
(40, 'Flowers Floral Luxury Gold Earring', 'Make a bold statement with our Flowets Floral Luxury Gold Earring. Featuring a delicate floral design and shimmering gold accents, these earrings are perfect for adding a touch of glamour to any look. Elevate your outfit with these luxurious earrings that are sure to turn heads and make you stand out from the crowd.', 379.00, 1, 'earring20.png'),
(41, 'Round Memories Necklace', 'A timeless piece that captures your most cherished moments in an elegant, personalized way. Crafted with care, this necklace features a sleek round pendant, perfect for engraving things that hold special meaning to you. Whether it\'s a milestone, a memory, or a symbol of love, the Round Memories Necklace ensures your story stays close to your heart.', 699.00, 2, 'pendant1.png'),
(42, 'Pretty You Silver Necklace', 'Introducing our exquisite necklace, a true gem that will elevate your style to a whole new level! Crafted with meticulous attention to detail, this dainty and elegant piece boasts a stunning pendant that effortlessly captures the light. Its timeless design makes it suitable for any occasion, from a casual brunch with friends to an enchanting evening soiree.', 349.00, 2, 'pendant2.png'),
(43, 'Pearl Heart Charm Gold Necklace', 'Indulge in the beauty of understated elegance with our Pearl Heart Charm Gold Necklace. Meticulously handcrafted with the finest materials, this piece exudes a sense of luxury and refinement. The stunning pearl heart charm adds a touch of uniqueness to this classic design, making it a must-have accessory for discerning individuals.', 600.00, 2, 'pendant3.png'),
(47, 'Pearl Heart Charm Gold Necklace', 'Indulge in the beauty of understated elegance with our Pearl Heart Charm Gold Necklace. Meticulously handcrafted with the finest materials, this piece exudes a sense of luxury and refinement. The stunning pearl heart charm adds a touch of uniqueness to this classic design, making it a must-have accessory for discerning individuals.', 650.00, 2, 'pendant4.png'),
(48, 'Boho Glam Necklace', 'Elevate your look with our stunning beautiful necklace. Crafted with exquisite detail and luxurious materials, this piece will exude elegance and sophistication. The perfect accessory for any occasion, this necklace is sure to dazzle and impress.', 529.00, 2, 'pendant5.png'),
(49, ' Butterfly Gold Pendant', 'Grab attention and steal some hearts with our Eirene Butterfly Gold Charm. This dainty bauble is the ultimate addition to sprinkle some magic into your jewelry box. Infuse your outfit with a touch of whimsy and give it the fluttery flair it craves!', 399.00, 2, 'pendant6.png'),
(50, 'Layered Gold Pendant', 'Indulge in opulence with our Emerald Beauty Layered Gold Necklace, a true masterpiece of luxury and glamour. The flawless blend of gold and emerald stones creates a dazzling effect that is sure to turn heads wherever you go. Elevate your style with this unique statement piece that exudes class and sophistication.', 929.00, 2, 'pendant7.png'),
(51, 'Layered Gold Pendant', 'Indulge in opulence with our Emerald Beauty Layered Gold Necklace, a true masterpiece of luxury and glamour. The flawless blend of gold and emerald stones creates a dazzling effect that is sure to turn heads wherever you go. Elevate your style with this unique statement piece that exudes class and sophistication.', 929.00, 2, 'pendant7.png'),
(52, 'Dazzling Beehive Pendant', 'Fall in love with the mesmerizing beauty of our Dazzling Beehive Necklace! Crafted with intricate detail, this stunning piece blends charm and elegance effortlessly. The delicate design, adorned with sparkling crystals, creates a mesmerizing effect that will surely turn heads wherever you go. Whether you\'re attending a special event or simply want to add a touch of glamour to your everyday outfit, this necklace is the perfect choice. Get ready to shine and make a statement with our Dazzling Beehive Necklace!', 400.00, 2, 'pendant8.png'),
(53, 'Golden Bismark chain', 'Take your style game to unparalleled heights with our exquisite Golden Bismark Chain - a true embodiment of luxury craftsmanship. Meticulously crafted using only the finest material, this chain intricately weaves together a timeless design and unparalleled elegance. Its intricate links delicately glisten in the sunlight, imparting an enchanting allure wherever you go. With its lightweight construction and impeccable finishing, this chain not only adorns your neckline but also exudes an air of sophistication. Elevate your every ensemble with the radiance of our Golden Bismark Chain, a treasure to be cherished for generations to come.\r\n', 149.00, 2, 'pendant9.png'),
(54, 'Enamel Rose Silver Necklace', 'Exude femininity and grace with our Enamel Rose Silver Charm. The vibrant enamel petals combined with the shining silver base create a stunning visual appeal. Let this captivating charm be a reminder of beauty and strength in your everyday life.', 349.00, 2, 'Screenshot 2024-10-12 164732.png'),
(55, 'Silver Floral Tassel Necklace', 'Indulge in the beauty of our breathtaking necklace, designed to capture the essence of glamour and grace. Adorned with shimmering gems and intricate accents, this exquisite piece will adorn your neck with timeless style and allure. Elevate your ensemble with this luxurious necklace that is sure to make a statement.', 469.00, 2, 'Screenshot 2024-10-12 164824.png'),
(56, 'Rosy stone Love Gold Necklace', 'Luxuriate in the unparalleled beauty of the Rosy Stone Love Gold Necklace, a decadent enhancement to your fine jewelry assortment. Exuding refinement at its finest, this exquisite necklace features a breathtaking rosy stone pendant delicately suspended from a lustrous gold chain. Whether adorning your neckline solo or paired with other lavish pieces, this accessory radiates an aura of effortless glamour and sophistication. Elevate your ensemble with this timeless treasure that emanates pure femininity and grace.', 699.00, 2, 'Screenshot 2024-10-12 164938.png'),
(57, 'Mermaid Tale Gold Necklace ', 'Dive into a world of whimsy and magic with our Mermaid Tale Gold Charm! This lovely accessory is perfect for anyone who adores all things enchanting and mystical. Crafted with intricate detail, this charm is sure to be a standout piece in your collection.', 349.00, 2, 'Screenshot 2024-10-12 165050.png'),
(58, 'Victorian White Bracelet', 'Transform your ensemble into a fashion statement with our Victorian White Clover Bracelet. Meticulously crafted to perfection, this stunning piece showcases a dainty and intricately detailed white clover charm that captures the essence of timeless beauty. Crafted with the finest materials, our bracelet offers a seamless blend of durability and elegance. Ideal for both casual and formal occasions, our Victorian White Clover Bracelet is a must-have accessory for those seeking a touch of sophistication and luck.', 549.00, 3, 'Screenshot 2024-10-12 165221.png'),
(59, 'Traditional Evil Eye Bangle', 'Add a glamorous touch to your jewelry collection with our Elegant Evil Eye Bangle. Crafted with utmost precision, this mesmerizing piece features a stunning evil eye motif, believed to protect the wearer from negative energies. Made from high-quality materials, this bangle exudes elegance and sophistication. With its sleek design and comfortable fit, it can effortlessly elevate any outfit, from casual to formal. Unleash your inner beauty and showcase your unique sense of style with this exquisite bangle.', 549.00, 3, 'Screenshot 2024-10-12 165416.png'),
(60, 'Eternal Bond Gold Bracelet', 'Elevate your wrist with the timeless elegance of the Eternal Bond Golden Bracelet. Crafted from exquisite materials and finished with a radiant gold hue, this accessory exudes luxury and sophistication. Let its intricate design and flawless polish be a symbol of your everlasting bond with style.', 199.00, 3, 'Screenshot 2024-10-12 165518.png'),
(61, 'Crystal Bracelet', 'Embrace the limelight with the exquisite Nefeli Crystal Bracelet. Feel a surge of confidence and grace wash over you as you slip this dazzling accessory onto your wrist. Allow the radiant sparkle of the crystals to illuminate your day and elevate each outfit with a touch of glamour.', 999.00, 3, 'Screenshot 2024-10-12 165624.png'),
(62, 'Whispering Pebble Of Intuition Bracelet', 'Elevate your Raksha Bandhan celebrations with our manifestation stone Rakhi, a symbol of love, protection, and manifestation. This stunning Rakhi is not just a piece of jewelry, but a tool to help your sibling manifest their deepest desires and wishes. Show your sibling how much you believe in their dreams by gifting them this special Rakhi, and watch as the universe conspires to make their wishes come true!', 249.00, 3, 'Screenshot 2024-10-12 165707.png'),
(63, 'Unity Knot Wristlet - Gold', 'Add a touch of glamour to your ensemble with our chic bracelet that exudes timeless beauty. The intricate detailing and dainty charms create a sophisticated look that will make you stand out from the crowd. Made with premium materials, this bracelet is not only stylish but also comfortable to wear all day long. Elevate your accessory game with this stunning piece.', 499.00, 3, 'Screenshot 2024-10-12 165748.png'),
(64, 'Simple Layered Gold Bracelet', 'Envelop your wrist in the opulence of our Simple Layered Gold Bracelet. Featuring a sleek and modern design, this bracelet boasts a seamless blend of style and sophistication. The layers of gleaming gold create a mesmerizing glare that will catch the eye of onlookers. Elevate your outfit with this luxurious accessory and bask in the radiant glow that it exudes. Make a statement with our Simple Layered Gold Bracelet and embrace the allure of understated elegance.', 599.00, 3, 'Screenshot 2024-10-12 165904.png'),
(65, 'Evil Eye Bracelet', 'Embrace style and protection with our mesmerizing Evil Eye Bracelet! Handcrafted with utmost care, this beautiful piece is designed to ward off negative energy and bring good fortune. Made with premium materials, the intricate evil eye charm on this bracelet is believed to safeguard you from harm and ensure positive vibes all day long. Step out with confidence, knowing you\'re stylishly shielded against the evil eye. Grab yours now and let this stunning piece elevate your fashion game effortlessly!', 549.00, 3, 'Screenshot 2024-10-12 170037.png'),
(66, 'Blue Moon Resin Gold Bracelet', 'Elevate your wrist with our exquisite resin bracelet, exuding elegance and sophistication. Crafted from high-quality resin, this luxurious accessory boasts a unique design that is sure to make a statement. Add a touch of glamour to any outfit with this must-have piece.', 249.00, 3, 'Screenshot 2024-10-12 170142.png'),
(67, 'Layered Chain Gold Bracelet', 'Enhance your wrist game with our luxurious Layered Chain Gold Bracelet. Made from premium metal, this bold accessory exudes glamour and elevates any ensemble. Featuring a distinctive layered design, this bracelet is guaranteed to make a lasting impression and set you apart from the rest. Elevate your style with this stunning statement piece today!', 649.00, 3, 'Screenshot 2024-10-12 170336.png'),
(68, 'Inara Pink Heart Gold Bracelet', 'Impress and enchant with the Inara Pink Heart Gold Bracelet. This stunning piece showcases a captivating pink heart embellished with glistening crystals that emanate a dazzling gleam with each graceful movement. The opulent gold chain perfectly complements the intricate design, rendering it a versatile accessory that seamlessly transitions from day to night. Enhance your ensemble with this sophisticated bracelet and revel in the luminous allure of its crystal brilliance.', 649.00, 3, 'Screenshot 2024-10-12 170431.png'),
(69, 'Imperial Gold Cuff', 'Make your wrist more attractive with our stunning Imperial Gold Cuff. This statement piece is perfect for adding a touch of luxury to any outfit. Made with high-quality materials, this cuff is sure to make you feel like royalty.', 999.00, 3, 'Screenshot 2024-10-12 170528.png'),
(70, 'Mauve Magic Beaded Band', 'Introducing our Stone Bracelets, the perfect accessory to elevate your everyday style! Crafted with exquisite detail, these bracelets effortlessly combine elegance and charm. Handcrafted from high-quality materials, Whether you\'re heading to a casual outing or a formal event, these bracelets will add a touch of sophistication to any outfit.', 199.00, 3, 'Screenshot 2024-10-12 170639.png'),
(71, 'Linked Treasure Gold Bracelet', 'Enrich your jewelry collection with the Linked Treasure Gold Bracelet, a stunning piece that combines classic design with modern flair. The intricate detailing of the gold links adds a touch of luxury to this bracelet, making it the perfect accessory for any occasion. Whether you\'re dressing up for a special event or adding a touch of glamour to your everyday look, this bracelet is sure to impress with its unique charm and appeal.', 749.00, 3, 'Screenshot 2024-10-12 170756.png'),
(72, 'Molten Silver Bracelet', 'Stand out from the crowd with our eye-catching Molten Silver Bracelet. Made with high-quality silver, this sleek and elegant accessory is sure to turn heads wherever you go. Whether you\'re dressing up for a night on the town or simply looking to add some sparkle to your everyday attire, this bracelet is the perfect choice.', 349.00, 3, 'Screenshot 2024-10-12 170952.png'),
(73, 'Twist of Fate Gold Bracelet', 'Want to turn heads and show off your bold side? Look no further than our Twist of Fate Gold Bracelet! This stunning accessory combines sophistication with a hint of edge, perfect for any event where you want to stand out. Embrace life\'s unpredictability with this bracelet, a stylish reminder to seize every opportunity that comes your way. Add a touch of glamour to your look and elevate your style with this one-of-a-kind gold twist bracelet. Trust us, you\'ll be the envy of everyone in the room!', 999.00, 3, 'Screenshot 2024-10-12 171051.png'),
(74, 'Trendy Stardust Bangle', 'Elevate your accessory game with our Charm Cuff! Crafted with love and attention to detail, this stunning cuff bracelet brings a touch of sophistication to any look. Designed to be lightweight and comfortable, you can wear it all day long without any discomfort. Its unique charm adds a dash of personality, making it a perfect gift for yourself or a loved one. Versatile and easy to style, this cuff will become your go-to accessory for any occasion. Make a statement and let your inner beauty shine with the Charm Cuff.', 949.00, 3, 'Screenshot 2024-10-12 171155.png'),
(75, 'Glimmering Grace Gold Cuff', 'Here is the Glimmering Grace Gold Cuff. Crafted with precision and attention to detail, this elegant cuff exudes sophistication and beauty. The intricate design, embellished with sparkling crystals, will add a touch of luxury to your look. Perfect for special occasions or everyday wear, this cuff is a must-have addition to your jewelry collection.', 929.00, 3, 'Screenshot 2024-10-12 171249.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `hobby` varchar(255) NOT NULL,
  `pet_name` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `is_blocked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `address`, `hobby`, `pet_name`, `password`, `is_blocked`) VALUES
(6, 'anjalee', 'Anjalee', 'anjalee@gmail.com', 'B-504B, Shapath IV, Opp. Karnavati Club, S.G. Highway, Prahlad Nagar, Ahmedabad, Gujarat 380015, India', 'music', 'anj', '$2y$10$UELvmV3F/h5viH0fgnkRXeAc8or3mOJ0y', 0),
(7, 'yashvi', 'yashvi patel', 'yashvi@gmail.com', 'F-10, 1st Floor, Sharanya Avenue, B/H Sharda School, Darpan Six Rd, Navrangpura, Ahmedabad, Gujarat 380009, India', 'travelling', 'yashu', '$2y$10$hbOS4t4XU7Df6n7ULtJmZOL65STGimToo', 0),
(8, 'jayshree', 'jayshree patel', 'jayshree@gmail.com', '310, Nirman House, Behind Times Of India, Ashram Rd, Shreyas Colony, Navrangpura, Ahmedabad, Gujarat 380009, India', 'cooking', 'khushi', '$2y$10$wUtTwyWVLFFDwQfZNMoY..TSfMNE3MIX7', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
