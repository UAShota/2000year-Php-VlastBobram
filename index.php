<?
  include("config.php");

  if (isset($_REQUEST["parse"])) {
    include("parser/parser_newtorr.php"); 
    die("");
  }

  $COOKIE = "";
  $BLOCK = "<br/><br/>";
  $LEFT = "";
  $RIGHT = "";
  $TITLE = "Верните казнет!";
    
  header('Content-Type: text/html; charset=utf-8');
  include("view/torrents.php");
  include("panels.php");
  include("template/default.html");
?>
