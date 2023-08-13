<?
  include("api/api_mysql.php");
  include("api/api_parser.php");

  function fetchIt($index)
  {
    global $user_uid, $user_pid;
     
    $baseurl = "http://newtorr.org/";

    // Логин в систему
    $url = $baseurl."login";
    getResponse($url, array("uid"=>$user_uid, "pwd"=>$user_pid));

    // Предварительный парсинг
    $url = $baseurl."torrent.php?active=0&order=3&by=DESC&count=150000&pages=".$index;
    $dump = getResponse($url, "");
    preg_match_all('#<a href=(.+?)</a>#', $dump, $matches );

    // Цикл по всем вхождениям
    $count = count($matches[0]);
    for ($i = 0; $i < $count; $count++)
    {
      // Выход если достигли конца списка
      if ($i >= 140) break;
      // Сброс данных в лог
      flush();
      // Отрезаем торренты без картинок
      preg_match('#/torrent/(.+?)"#', $matches[0][++$i], $noimage);
      if (count($noimage) > 0) {
        $i += 3;
        continue;
      }
      // Выпиливаем код категории
      preg_match('#y/(.+?)"#', $matches[0][$i], $category);
      $category = $category[1];
      // Отрезаем порно, ибо оно ведет на другой торрент-треккер
      if ($category == 2) {
         $i += 4;
         continue;
      }
      // Выпиливаем имя категории
      preg_match('#>(.+?)<#', $matches[0][$i], $categoryName);
      $categoryName = $categoryName[1];
      // Выпиливаем название     
      preg_match('#<a href="(.+?)"><b>(.+?)</b>#', $matches[0][++$i], $titles);
      $title = strip_tags($titles[2]);
      preg_match('#/torrent/(.+?)"#', $titles[1], $tpath);
      $torrent_path = $tpath[1];      
      // Выпиливаем ссылку на информацию о торренте
      preg_match('#"/(.+?)"#', $matches[0][$i], $info);
      $info = $info[1];

      // Используются только первые две величины
      $i += 3;

      // Проверка на дубль файла
      if (!TorrentCheck($title)) {
        echo "<br>-".$title;
        continue;
      } else
        echo "<br>+".$title;
        
      // Подробный парсинг
      $url = $baseurl.$info;
      $dump = getResponse($url, "");
      preg_match_all('#<td class="lista1"(.+?)</td>#', $dump, $submatches);
      
      // Выпиливаем картинку
      preg_match('#<a href="(.+?)"#', $submatches[0][2], $picture);
      $picture = $picture[1];
      // Выпиливаем подробное описание без картинок
      preg_match('#>(.+?)</td>#', $submatches[0][3], $desc);
      $desc = strip_tags($desc[1], "<div><br><b>");
      $desc = str_replace("<br /><br />", "<br/>", $desc);
      $desc = str_replace("<br /><br />", "<br/>", $desc);
      // Выпиливаем размер файла
      preg_match('#>(.+?)</td>#', $submatches[0][8], $size);
      $size = $size[1];
      // Выпиливаем  дату файла
      preg_match('#>(.+?)</td>#', $submatches[0][10], $date);
      $date = strtotime(str_replace("/", ".", $date[1]));
      $date = date("Y-m-d", $date);

      $tfile = getResponse($baseurl."download/".$torrent_path, "");   
      file_put_contents("torrent/".$torrent_path, $tfile);
          
      // Запись в базу
      TorrentWrite($category, $picture, $title, $desc, $size, $date, $categoryName, $torrent_path);
    }
  }

  @set_time_limit(360000);
  fetchIt(1);
  fetchIt(2);
  fetchIt(3);
  fetchIt(4);
  fetchIt(5);
  fetchIt(6);
  fetchIt(7);
  fetchIt(8);
  fetchIt(9);
  fetchIt(10);
?>
