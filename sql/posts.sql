-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 03. čen 2024, 21:47
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `rapnews_database`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `subcategory` varchar(50) DEFAULT NULL,
  `post_date` datetime DEFAULT current_timestamp(),
  `image_url` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `likes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `title`, `category`, `subcategory`, `post_date`, `image_url`, `content`, `likes`) VALUES
(26, 1, 'Playboi Carti Teases New Album', 'Music', 'Albums', '2024-06-03 21:39:21', 'https://img.buzzfeed.com/buzzfeed-static/complex/images/cj1iw7dviovzt4rppewp/playboi-carti-getty-timothy-norris.jpg?output-format=jpg&output-quality=auto', 'Playboi Carti has recently hinted at a new album release, sending waves of excitement through the rap community. In a series of cryptic social media posts, Carti shared snippets of new tracks, showcasing his signature style and experimental beats. Fans are eagerly awaiting more details and a release date, hoping for another genre-defying project from the enigmatic artist. Playboi Carti, known for his unique sound and energetic performances, has been a significant figure in the rap scene since his breakout hit \"Magnolia\". His last album, \"Whole Lotta Red\", received mixed reviews but was praised for its bold and unconventional approach. With his new album, Carti promises to deliver something even more groundbreaking, pushing the boundaries of hip-hop and experimenting with new sounds. The anticipation is palpable as fans speculate about possible collaborations and the overall direction of the album. Will Carti continue to innovate and surprise, or will he return to his roots? One thing is certain: the rap world will be watching closely.', 0),
(27, 2, 'Kanye West Announces \"Vultures 2\"', 'Music', 'Albums', '2024-06-03 21:39:21', 'https://www.rollingstone.com/wp-content/uploads/2024/04/kanye-west-yeezy-porn.jpg?w=1581&h=1054&crop=1', 'Kanye West has announced the sequel to his critically acclaimed album, \"Vultures\". \"Vultures 2\" is set to explore new sonic landscapes, featuring collaborations with some of the biggest names in the industry. With his innovative production techniques and thought-provoking lyrics, Kanye is poised to deliver another masterpiece. The anticipation for \"Vultures 2\" is palpable, as fans eagerly await its release. Kanye West has always been a trailblazer in the music industry, known for his ability to reinvent himself with each album. His previous works, including \"The College Dropout\", \"My Beautiful Dark Twisted Fantasy\", and \"Ye\", have left an indelible mark on hip-hop. \"Vultures 2\" promises to be no different, with Kanye hinting at a blend of various musical genres and influences. In recent interviews, Kanye has discussed his creative process, revealing that he draws inspiration from a wide range of sources, including classical music, electronic beats, and even gospel. This eclectic mix is expected to be reflected in \"Vultures 2\", making it a highly anticipated release. Fans and critics alike are eager to see what Kanye has in store and how he will continue to shape the future of music.', 0),
(28, 3, 'Metro Boomin\'s Next Project', 'Music', 'Producers', '2024-06-03 21:39:21', 'https://media.gq.com/photos/5a5f936eab6df56bfd56d048/16:9/w_2560%2Cc_limit/Metro_Boomin_02.jpg', 'Renowned producer Metro Boomin is gearing up for his next big project. Known for his work with artists like Future, Drake, and 21 Savage, Metro Boomin has been teasing new music on his social media. His upcoming project promises to blend hard-hitting beats with melodic undertones, continuing his legacy as one of the most influential producers in the rap game. Metro Boomin, whose real name is Leland Tyler Wayne, has been a dominant force in hip-hop production for over a decade. His signature tag, \"If Young Metro don\'t trust you...\", has become synonymous with some of the biggest hits in recent years. With his new project, Metro aims to push the boundaries of production, incorporating elements from various genres and experimenting with new sounds. In interviews, Metro has expressed his desire to evolve as a producer and create music that transcends traditional rap boundaries. This project is expected to feature a mix of established artists and up-and-coming talent, showcasing Metro\'s ability to craft hits across different styles. Fans are eagerly awaiting the release, ready to experience the next chapter in Metro Boomin\'s illustrious career.', 0),
(29, 3, 'Travis Scott Drops Hints About Upcoming Album', 'Music', 'Artists', '2024-06-03 21:39:21', 'https://www.rollingstone.com/wp-content/uploads/2023/06/Travis-Scott-charges.jpg', 'Travis Scott has been dropping subtle hints about his upcoming album, causing a stir among his fanbase. Known for his high-energy performances and innovative music, Travis Scott\'s new album is expected to push the boundaries of hip-hop. With collaborations rumored with both new and established artists, this project is shaping up to be one of the most anticipated releases of the year. Travis Scott, born Jacques Webster, has become a household name in the rap industry. His previous albums, including \"Astroworld\" and \"Birds in the Trap Sing McKnight\", have received critical acclaim and commercial success. Scott\'s ability to blend various musical elements and create immersive experiences for his listeners has set him apart from his peers. The upcoming album is rumored to feature a diverse range of sounds, from trap beats to psychedelic influences. Travis has been spotted in the studio with artists like Kid Cudi, Kendrick Lamar, and even genre-crossing collaborations with rock and electronic musicians. Fans are eagerly dissecting every teaser and snippet, speculating about the album\'s themes and potential tracklist. With his track record of delivering hits, there\'s no doubt that Travis Scott\'s new album will be a major event in the music world.', 0),
(30, 2, 'Drake and Future Collaboration Rumors', 'Music', 'Collaborations', '2024-06-03 21:39:21', 'https://townsquare.media/site/812/files/2023/01/attachment-Future-Drake.jpg?w=780&q=75', 'Rumors are swirling about a potential collaboration between Drake and Future. The duo, known for their previous hits, have been spotted in the studio together, sparking speculation about a new joint project. Fans are eagerly anticipating what could be another chart-topping release, combining Drake\'s lyrical prowess with Future\'s unique style and flow. Drake and Future have a history of creating magic together, with their 2015 collaborative mixtape \"What a Time to Be Alive\" being a standout project. Their chemistry in the studio is undeniable, resulting in a string of hits that have dominated the charts. The prospect of a new joint project has fans buzzing with excitement, as both artists have evolved significantly since their last collaboration. Drake, known for his versatility and ability to blend different musical styles, has consistently pushed the envelope with his music. Future, on the other hand, has cemented his place as one of the pioneers of the modern trap sound. Together, they have the potential to create another groundbreaking project that will captivate listeners. As rumors continue to circulate, fans are eagerly awaiting official confirmation and details about the release.', 0);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
