<?php
function imagetype($image) {
	if (exif_imagetype($image) == IMAGETYPE_PNG) // Si le fichier est un png
	{
		$insert = imagecreatefrompng($image);
	}
	elseif (exif_imagetype($image) == IMAGETYPE_JPEG) // Si le fichier est un jpeg
	{
		$insert = imagecreatefromjpeg($image);
	}
	elseif (exif_imagetype($image) == IMAGETYPE_GIF) // Si le fichier est un gif
	{
		$insert = imagecreatefromgif($image);
	}
	else
		echo "\nLe format de l'image n'est pas reconnu.\n";
	return $insert;
}

function tri_image($image)
{
	$l = 0;
	$c = 0;
	$i = 0;
	while ($image[$l])
	{
		while ($image[$l][$c])
		{
			$new_image[$i] = $image[$l][$c];
			$i++;
			$c++;
		}
		$c = 0;
		$l++;
	}
	return $new_image;
}

function help()
{
	echo "\nExemple d'usage : php imagepanel.php [options] lien1 [lien2 [...]] base\n\n";
	echo "Liste des options :
			-g	Les images générées seront en .gif.
			-j	Les images générées seront en .jpeg.
			-l 'n'	n images seront recuperées pour chaque lien.
			-n	Affiche sous les images le nom de celles-ci (sans l'extension).
			-N	Affiche sous les images le nom de celles-ci (avec l'extension).
			-p	Les images générées seront en .png.
			-s	Trier les images par ordre alphabétique.\n\n";
}