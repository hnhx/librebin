<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
      <title>Librebin</title>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="referrer" content="no-referrer" />
	<link rel="stylesheet" type="text/css" href="static/styles.css" />
  </head>
  <body>
  	<h1>Librebin</h1>
	<a href="https://github.com/hnhx/librebin">Source & Instances</a>
	<a href="./index.php">Upload</a>

	<?php
		require("config.php");	

		$content = NULL;

		if (isset($_REQUEST["p"]))
			$content = file_get_contents("p/" . $_REQUEST["p"]);

	?>

	<form method="POST" action="index.php">	
		<textarea name="content" cols="80" rows="20" minlength="15" <?php echo (!empty($content) ? "readonly=\"on\"" : ""); ?> maxlength="1048576" placeholder="Paste in your stuff here! (max 1048576 chars)"><?php echo (!empty($content) ? $content : ""); ?></textarea>
		<?php
			if (empty($content))
			{

				echo "<br/>";
				echo "<br/>";
				echo "<img src=\"captcha.php\" alt=\"captcha\" />";
				echo "<input name=\"captcha\" type=\"text\" maxlength=\"5\" placeholder=\"Captcha...\" />";
				echo "<br/>";
				echo "<button type=\"submit\">Upload</button>";
			} 
			else
			{	
				echo "<br />";
				echo "<br />";
				echo "<a href=\"p/" . $_REQUEST["p"] . "\">Raw text</a>";
			}
		?>
	</form> 
<?php
	function random_string()
        {
        	$chars = array_merge(range("A", "Z"), range("a", "z"), range(1, 9));
        	$random_str = "";


        	for($i=0; random_int(6,9)>$i; $i++)
            		$random_str .= $chars[random_int(0, count($chars)-1)];

        	return $random_str;
        }


	if (!isset($_REQUEST["content"]))
		die();

	session_start();
	$valid_upload = true;
	$content = htmlspecialchars(trim($_REQUEST["content"]));

	if (empty($_SESSION["captcha"]) || $_SESSION["captcha"] != strtoupper($_REQUEST["captcha"]))
	{

		if (!in_array($_REQUEST["api_key"], $api_keys))
		{
			$valid_upload = false;
			echo "<p>Invalid captcha!</p>";
		}
	}

	$content_len = strlen($content);
	if ($content_len > 1048576 || 15 > $content_len)
	{
		$valid_upload = false;
		echo "<p>Content must be between 15 and 1048576 chars!</p>";
	}

	if ($valid_upload)
	{
		if (!file_exists("p"))
			mkdir("p");
		
		$file_name = NULL;

		do
		{
			$file_name = random_string();

		} while (file_exists("p/" . $file_name));

		file_put_contents("p/" . $file_name, $content);

		header("Location: index.php?p=" . $file_name);
	}
?>
 	</body>
</html>
