<?php
	require_once("imagetype.php");
	require_once("image.php");
	require_once("get_image.php");
	// Recuperation des options
	$o = 0;
	$l = 0; // Compteur pour les liens
	$nb_arg = 1; // Commence les arguments aprés le nom du fichier
	$nb_option = 1; // Compteur d'option
	$i = 0;
	$type = "png"; // Format par defaut
	$nb_max_arg = count($argv); // Nombre total d'argument
	$base = $argv[$nb_max_arg - 1]; // Base du nom des images
	$nb_image = 100; // Nombre d'images par défaut
	while ($argv[$nb_arg])
	{
		if ($argv[$nb_arg][0] == "-")
		{
			// Separation des differentes options en char	
			while ($argv[$nb_arg][$nb_option])
			{
				if ($argv[$nb_arg][$nb_option] == "s")
					$sort = true;
				else if ($argv[$nb_arg][$nb_option] == "l")
				{
					$nb_image = $argv[$nb_arg + 1];
					$nb_arg++;
				}
				else if ($argv[$nb_arg][$nb_option] == "g")
					$type = "gif";
				else if ($argv[$nb_arg][$nb_option] == "j")
					$type = "jpeg";
				else if ($argv[$nb_arg][$nb_option] == "p")
					$type = "png";
				else if ($argv[$nb_arg][$nb_option] == "n")
					$n_option = "no_ext";
				else if ($argv[$nb_arg][$nb_option] == "N")
					$n_option = "ext";
				else if ($argv[$nb_arg][$nb_option] == "h")
				{
					help();
					exit;
				}
				else
				{
					echo "\n$argv[$nb_arg] : Invalid option. Type -h for help.\n\n";
					exit;
				}
				$nb_option++;
				$i++;
			}
		}
		else if ($nb_arg != $nb_max_arg - 1)
		{
			$lien[$l] = $argv[$nb_arg]; // Recuperation des liens
			$l++;
		}
		$nb_option = 1;
		$nb_arg++;
	}
	if ($lien == NULL)
	{
		help();
		exit;
	}
		

	$l = 0;

	while ($filename = $lien[$l])
	{
		if (substr($filename, 0, 4) == "http" || substr($filename, 0, 4) == "www.")
		{
			$curl = curl_init($filename);
			curl_setopt($curl, CURLOPT_NOBODY, true);
			$result = curl_exec($curl);
			if ($result !== false)
			{
				$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				if ($statusCode == 404)
				{
			  		echo "$filename : Invalid URL\n";
				}
				else
				{
					// Ouverture du fichier
					// Si le fichier est ouvert
					if ($result == true)
					{
						$image[$l] = open_file($filename, $nb_image, $base, $n_option, $type);
					}
					else
						echo "$filename : Can't open file.\n";
				}
			}
			else
			{
				echo "$filename : Invalid URL\n";
			}
		}
		else if (is_readable($filename))
			$image[$l] = open_file($filename, $nb_image, $base, $n_option, $type);
		else
			echo "$filename : No such file or directory\n";
		$l++; // Passage au lien suivant
	}
	if (!empty($image[0]))
	{
		if ($sort == true)
			sort($image);
		image($image, $base, $n_option, $type);
	}
	echo "\n";

function open_file($filename, $nb_image, $base, $n_option, $type)
{
	require_once("image.php");
	require_once("get_image.php");
	$i = 0;

	// Ouverture du fichier
	// Si le fichier est ouvert
	$fichier = fopen($filename, "r");
	$image = read_file($fichier, $nb_image, $filename);
	$image = get_image($image, $filename);
	return $image;
}

?>