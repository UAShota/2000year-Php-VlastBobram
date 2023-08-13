<?
  include("api/api_mysql.php");
  include("api/api_theme.php");

  $search = addslashes(strip_tags(@$_REQUEST["search"]));
  $limit = @$_REQUEST["page"] or $limit = 1;
  $id = (int)@$_REQUEST["id"] or $id = "";
  $category = (int)@$_REQUEST["category"] or $category = "";
  $proffLink = "torrent.php?id=";

  $data = TorrentGet($search, $category, $limit, $count, $id);
  $navy = "<div align='center'>";

  if ($count > 10) {
    if ($limit > 6) $navy .= "<input type='submit' class='submit' name='page' value='1'> ... ";
    for ($i = $limit - 5; $i < $limit + 5; $i++)
    {
      if (($i < 1) || ($i > $count / 10)) continue;
      if ($limit != $i) $class = "submit"; else $class = "desubmit";
      $navy .= "<input type='submit' class='$class' name='page' value='".$i."'>&nbsp";
    }
    if ($limit != round($count / 10)) $class = "submit"; else $class = "desubmit";
    $navy .= "... <input type='submit' class='$class' name='page' value='".(round($count/10))."'>&nbsp";
  }

  $navy .= "</center></div>";

  $BLOCK = ThemeSearchTop($navy);
  $BLOCK .= "<table align='center' cellspacing='0' cellpadding='0' id='data'>";
  foreach ($data as $item)
  {
    $text = $item["description"];
    if ($id == "")
      $text = mb_substr($text, 0, 600, "utf-8")."... <br /><br /><span class='more'><a href='?id=".$item["id"]."'>Читать далее</a></span>";
    else
      $TITLE = UnSafeStr($item["title"]);

    $BLOCK .= "<tr><td class='title' colspan='2'><h1>".UnSafeStr($item["title"])."</h1></td></tr>";
    $BLOCK .= "<tr><td><div class='subbegin'><div class='subend'>".$item["filedate"]."</div></div></td></tr>";
    $BLOCK .= "<tr><td rowspan='2' width='100px' valign='top'><center><br/ ><img width='100px' src='".$item["picture"]."'>";
    $BLOCK .= "<a href='?category=".$item["category"]."'><br />".$item["catname"]."</a><br />";
    $BLOCK .= "<br />Размер<br />".$item["filesize"]."<br />";
    $BLOCK .= "<br /><a href='".$proffLink.$item["torrent"]."'><img src='images/download.png' width='16' height='16'> Скачать </a>";
    $BLOCK .= "<br /><br /></center></td></tr><tr><td class='text'>".$text."</td></tr>";
  }
  $BLOCK .= "</table>";

  $BLOCK .= ThemeSearchBottom($navy)."</div>";
  $LEFT .= ThemeSidebar("Категории", TorrentGetCategory());
?>