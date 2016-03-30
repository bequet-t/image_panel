<?php
function get_image($image, $filename) {
	$compteur = 0;
	while ($image[$compteur])
		{
			// Recherche des sources des images
			preg_match_all("#src=['\"][^'\"\?]*['\"]?#", $image[$compteur], $resultat);
			$resultat = $resultat[0][0];
			$res = substr($resultat, 5);
			
			// Susspression des backgrounds
			if (preg_match("#src=['\"][^'\"]*(background|_bg_|bg_|_bg)[^'\"]*['\"]#i", $res))
				$res = NULL;
			else
			{
				// Retrait des guillemets en fin de source
				if ($res[strlen($res) - 1] == '"' || $res[strlen($res) - 1] == "'")
					$res[strlen($res) - 1] = NULL;

				// Ajout de l'url à l'adresse de l'image
				if ($res[0] == '/')
					$res = $filename . $res;
				if ($res[0] == '.')
					$res = $filename . $res;

				// Ajout des sources des images dans un tableau 
				$image[$compteur] = $res;
				$compteur++;
			}
		}
		return $image;
}

function count_image($image, $filename) {
	$compteur = count($image);
	if ($compteur == 0)
	{
		echo "\nIl n'y a pas d'image";
	}
	elseif ($compteur == 1)
	{
		echo "\n$compteur image trouvée";
	}
	else
	{
		echo "\n$compteur images trouvées";
	}
	// Lien utilisé
	echo " dans $filename.\n";
	return $compteur;
}

function read_file($fichier, $nb_image, $filename) {
	$i = 0;
	while (($buffer = fgets($fichier)) !== false)
	{
		if (preg_match_all("#<img[^>]*>#", $buffer, $resultat))
		{
			while ($resultat[0][$o])
			{
				$image[$i] = $resultat[0][$o];
				$i++;
				$o++;
			}
		}
		$o = 0;
	}
	$compteur = count_image($image, $filename);
	while ($compteur > $nb_image)
	{
		array_pop($image);
		$compteur--;
	}
	fclose($fichier);
	return $image;
}