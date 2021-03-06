<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="robots" content="noindex,nofollow>
	<title><?php echo $webpage->PageTitle?></title>
	<meta name="description" content="<?php echo $webpage->PageDescription?>" />
	<meta name="keywords" content="<?php echo $webpage->PageKeywords?>" />

	<?php if ($webpage->PageIcon != null) { ?> <link rel="shortcut icon" href="<?php $webpage->PageIcon?>" /><?php } ?>
	<?php if ($webpage->StyleSheets != null)
	{
		foreach ($webpage->StyleSheets as $style)
		{
			echo '<link rel="stylesheet" href="'._SITE_RELATIVE_URL.'style/'.$style.'" />';
		}
	}

    if ($webpage->StyleSheetsOutsideStyleFolder != null)
    {
        foreach ($webpage->StyleSheetsOutsideStyleFolder as $style)
        {
            echo '<link rel="stylesheet" href="'._SITE_RELATIVE_URL.$style.'" />';
        }
    }
	
	if ($webpage->ScriptsHeader != null)
	{
		foreach ($webpage->ScriptsHeader as $script)
		{
			echo '<script src="'.$script.'" ></script>';
		}
	}
	?>
</head>