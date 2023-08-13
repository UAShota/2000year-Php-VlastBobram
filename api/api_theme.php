<?
  function ThemeSidebar($title, $text)
  {
    $out = "<table cellspacing='0' cellpadding='0' width='100%'><tr><td><h3>".$title."</h3></td></tr>";
    $out .= "<tr><td class='body'>".$text."</td></tr>";
    $out .= "<tr><td></td></tr></table>";
    return $out;
  }

  function ThemeSearchTop($text)
  {
    $out = "<table cellspacing='0' cellpadding='0' id='searchtop'><tr>";
    $out .= "<td class='left'>&nbsp;</td><td class='body'>".$text."</td><td class='right'>&nbsp;</td></tr></table>";
    return $out;
  }

  function ThemeSearchBottom($text)
  {
    $out = "<table cellspacing='0' cellpadding='0' id='searchbottom'><tr>";
    $out .= "<td class='left'>&nbsp;</td><td class='body'>".$text."</td><td class='right'>&nbsp;</td></tr></table>";
    return $out;
  }
?>