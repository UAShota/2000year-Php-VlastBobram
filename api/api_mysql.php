<?
  include("core/core_mysql.php");

  $MySQL = new TMySQL($sql_login, $sql_pwd, $sql_database, $sql_host);
  $MySQL->Connect();
  
  function UnSafeStr($value)
  {
    return trim(str_replace('"', "'", stripcslashes($value)));
  }
    
  function SafeStr($value)
  {
    return trim(mysql_real_escape_string(stripslashes($value)));
  }  
  
  function TorrentCheck($title)
  {
    global $MySQL;

    $SQL = "select id from items where title like '".SafeStr($title)."'";
    $exist = $MySQL->sqlRun($SQL);
    return (count($exist) == 0);
  }

  function TorrentCategory($category, $categoryName)
  {
    global $MySQL;

    $SQL = "select id from category where id=".$category;
    $exist = $MySQL->sqlRun($SQL);
    if (count($exist) == 0)
    {
      $SQL = "insert into category values(".$category.", '".SafeStr($categoryName)."')";
      $MySQL->Execute($SQL);
    }
  }

  function TorrentWrite($category, $picture, $title, $desc, $size, $time, $categoryName, $torrent_path)
  {
    global $MySQL;

    TorrentCategory($category, $categoryName);

    $SQL = "insert into items values(null,
      ".$category.",
      '".SafeStr($picture)."',
      '".SafeStr($title)."',
      '".SafeStr($desc)."',
      '".$size."',
      '".$time."',
      '".$torrent_path."'
    )";
    $MySQL->Execute($SQL);
  }

  function TorrentGet($name, $category, $limit = 0, &$count, $id)
  {
    global $MySQL;

    $SQL = "select it.*, ca.caption as catname, torrent from items it, category ca where it.category=ca.id ";
    $SQLCOUNT = "select count(*) as icount from items it, category ca where it.category=ca.id ";
    $where = "";
    if ($limit == "") $limit = 0; else $limit--;

    if ($name != "") $where .= " and it.title like '%".$name."%'";
    if ($category != "") $where .= " and it.category = ".$category;
    if ($id != "") $where .= " and it.id = ".$id;

    $MySQL->sqlRunOne($count, $SQLCOUNT.$where);
    $count = $count["icount"];

    $where .= " ORDER BY filedate desc LIMIT ".($limit * 10).", 10";
    return $MySQL->sqlRun($SQL.$where);
  }

  function TorrentGetCategory()
  {
    global $MySQL;

    $SQL = "select * from category order by caption";
    $data = $MySQL->sqlRun($SQL);

    $dump = "<ul><li><a href='/'>На главную</a>";
    foreach ($data as $item)
    {
      $dump .= "<li><a href='?category=".$item["id"]."'>".$item["caption"]."</a>";
    }

    return $dump."</ul>";
  }
?>