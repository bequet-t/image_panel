<?php
require_once("imagetype.php");
function image($image, $base, $n_option, $type) {
	$c = 0;
	$i = 0;
	static $n = 0; // Variable pour le nom de l'image
	$x = 0;
	$y = 0;
	$font_width = imagefontwidth(20);
	$img = imagecreatetruecolor(1000, 1000);
	$white = imagecolorallocate($img, 255, 255, 255);
	$black = imagecolorallocate($img, 0, 0, 0);
	imagefilledrectangle($img, 0, 0, 1000, 1000, $white);

	$image = tri_image($image);

	while ($image[$c])
	{
		$insert = imagetype($image[$c]);


		$y_max = 200;
		$x_image = imagesx($insert); // Largeur de l'image
		$y_image = imagesy($insert); // Hauteur de l'image
		$rapport_x_y = $x_image / $y_image;
		$x_resize = $rapport_x_y * 200;

		$x_tmp = $x + $x_resize; // Largeur de l'image dans le panel

		if ($x_tmp > 1000)	// Passage à la ligne des images
		{
			$x = 0;
			$y = $y + 200 + $y_font_max;
			$y_font_max = 0;
		}

		if ($y + $y_max > 1000) // Creation d'une nouvelle image si la courante est pleine
		{
			$y = 0; // Reinitialisation des compteurs
			$x = 0;
			$y_max = 200;
			$m = $n + 1;
			if ($m == 1)
				$name = "$base.$type";
			else
				$name = "$base$m.$type";
			rendu_image($img, $name, $type); // Création de l'image
			$n++;
			$img = imagecreatetruecolor(1000, 1000); // Création d'une nouvelle base d'image
			imagefilledrectangle($img, 0, 0, 1000, 1000, $white);		
		}

		// Insertion de l'image
		imagecopyresized($img, $insert, $x, $y, 0, 0, $x_resize, 200, imagesx($insert), imagesy($insert));

		// Insertion du texte
		if ($n_option != NULL)
		{
			$nom_image = preg_split("#\/#", $image[$c]); // Decoupage sur les '/'
			$adresse_image = count($nom_image); // Emplacement du nom de l'image dans le tableau
			$rapport_font = intval($x_resize / $font_width); // Nombre de lettres par ligne
			if ($n_option == "no_ext") // Si l'option ne demande pas l'extension, retrait de l'extension
				$nom_image[$adresse_image - 1] = substr($nom_image[$adresse_image - 1], 0, strlen($nom_image[$adresse_image - 1]) - 5);
			while ($i * $rapport_font < strlen($nom_image[$adresse_image - 1])) // Affichage du nom avec des retours à la ligne
			{
				$y_text = $y + 200 + 16 * $i;
				$nom = substr($nom_image[$adresse_image - 1], $i * $rapport_font, $rapport_font); // Selection des lettres de la ligne
				imagestring($img, 20, $x, $y_text, $nom, $black); // Ajout du texte
				$i++;
			}
			if ($y_font_max < $i * 16)
				$y_font_max = $i * 16;
			if ($y_image == $y_max)
				$y_max = $y_max + $y_font_max + 10;
			$i = 0;
		}
		// Calcul de la taille de l'image
		$x = $x + $x_resize;

		$c++; // Passage à l'image suivante
	}
	$m = $n + 1;
	if ($m == 1)
		$name = "$base.$type";
	else
		$name = "$base$m.$type";
	rendu_image($img, $name, $type);
	imagedestroy($img);
	$n++;
}

function rendu_image($img, $name, $type)
{
	if ($type == "png")
		imagepng($img, $name);
	else if ($type == "jpeg")
		imagejpeg($img, $name);
	else if ($type == "gif")
		imagegif($img, $name);
	echo "\nCreation de $name\n";
}