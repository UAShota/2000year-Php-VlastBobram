<?
    include("config.php");
    include("core/core_mysql.php");
    
    $MySQL = new TMySQL($sql_login, $sql_pwd, $sql_database, $sql_host);
    $MySQL->Connect();
  
  function SafeStr($value)
  {
    return trim(mysql_real_escape_string(stripslashes($value)));
  } 
  
  function SafeFileName($name)
  {
    $in = array("\\", "/", ":", "*", "?", '"', ">", "<", "|", " _ ", " _", "_ ");
    return str_replace($in, "_", $name);
  }  
  
    $id = SafeStr($_GET["id"]);
    if (file_exists("torrent/".$id)) 
    {
        $MySQL->sqlRunOne($item, "select title from items where torrent='".$id."' limit 1;");
        $file = file_get_contents("torrent/".$id);
          
        header("HTTP/1.1 200 OK"); 
        header("Content-Type: application/x-bittorrent"); 
        header("Content-Disposition: attachment; filename='[kaznet].".SafeFileName($item["title"]).".torrent");
        echo $file ;
    } else echo "File not exist";
?>