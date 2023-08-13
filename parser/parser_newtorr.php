<?
  include("api/api_mysql.php");
  include("api/api_parser.php");

  function fetchIt($index)
  {
    global $user_uid, $user_pid;
     
    $baseurl = "http://newtorr.org/";

    // ����� � �������
    $url = $baseurl."login";
    getResponse($url, array("uid"=>$user_uid, "pwd"=>$user_pid));

    // ��������������� �������
    $url = $baseurl."torrent.php?active=0&order=3&by=DESC&count=150000&pages=".$index;
    $dump = getResponse($url, "");
    preg_match_all('#<a href=(.+?)</a>#', $dump, $matches );

    // ���� �� ���� ����������
    $count = count($matches[0]);
    for ($i = 0; $i < $count; $count++)
    {
      // ����� ���� �������� ����� ������
      if ($i >= 140) break;
      // ����� ������ � ���
      flush();
      // �������� �������� ��� ��������
      preg_match('#/torrent/(.+?)"#', $matches[0][++$i], $noimage);
      if (count($noimage) > 0) {
        $i += 3;
        continue;
      }
      // ���������� ��� ���������
      preg_match('#y/(.+?)"#', $matches[0][$i], $category);
      $category = $category[1];
      // �������� �����, ��� ��� ����� �� ������ �������-�������
      if ($category == 2) {
         $i += 4;
         continue;
      }
      // ���������� ��� ���������
      preg_match('#>(.+?)<#', $matches[0][$i], $categoryName);
      $categoryName = $categoryName[1];
      // ���������� ��������     
      preg_match('#<a href="(.+?)"><b>(.+?)</b>#', $matches[0][++$i], $titles);
      $title = strip_tags($titles[2]);
      preg_match('#/torrent/(.+?)"#', $titles[1], $tpath);
      $torrent_path = $tpath[1];      
      // ���������� ������ �� ���������� � ��������
      preg_match('#"/(.+?)"#', $matches[0][$i], $info);
      $info = $info[1];

      // ������������ ������ ������ ��� ��������
      $i += 3;

      // �������� �� ����� �����
      if (!TorrentCheck($title)) {
        echo "<br>-".$title;
        continue;
      } else
        echo "<br>+".$title;
        
      // ��������� �������
      $url = $baseurl.$info;
      $dump = getResponse($url, "");
      preg_match_all('#<td class="lista1"(.+?)</td>#', $dump, $submatches);
      
      // ���������� ��������
      preg_match('#<a href="(.+?)"#', $submatches[0][2], $picture);
      $picture = $picture[1];
      // ���������� ��������� �������� ��� ��������
      preg_match('#>(.+?)</td>#', $submatches[0][3], $desc);
      $desc = strip_tags($desc[1], "<div><br><b>");
      $desc = str_replace("<br /><br />", "<br/>", $desc);
      $desc = str_replace("<br /><br />", "<br/>", $desc);
      // ���������� ������ �����
      preg_match('#>(.+?)</td>#', $submatches[0][8], $size);
      $size = $size[1];
      // ����������  ���� �����
      preg_match('#>(.+?)</td>#', $submatches[0][10], $date);
      $date = strtotime(str_replace("/", ".", $date[1]));
      $date = date("Y-m-d", $date);

      $tfile = getResponse($baseurl."download/".$torrent_path, "");   
      file_put_contents("torrent/".$torrent_path, $tfile);
          
      // ������ � ����
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
