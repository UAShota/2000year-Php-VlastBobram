<?
  class TMySQL {
    var $sql_link;
    var $sql_data;
    var $sql_params = array();

    function TMySQL($login, $pwd, $database, $host)
    {
      $this->sql_login = $login;
      $this->sql_pwd = $pwd;
      $this->sql_database = $database;
      $this->sql_host = $host;
    }
    function Connect()
    {
      $this->sql_link = mysql_connect($this -> sql_host, $this -> sql_login, $this -> sql_pwd);
      mysql_select_db($this -> sql_database, $this -> sql_link);
      //mysql_set_charset("utf8", $this -> sql_link);
    }

    function Disconnect()
    {
      mysql_close($this -> sql_link);
    }

    function ParamAdd($name, $value)
    {
      array_push($this -> sql_params, array($name, $value));
    }

    function sqlRun($sql, $debug = false)
    {
      while ($item = array_pop($this -> sql_params))
      {
        $sql = str_replace($item[0], $item[1], $sql);
      }

      if ($debug) echo "<hr>".$sql."<hr>";

      $this -> sql_data = mysql_query($sql, $this -> sql_link)
        or die($sql."<hr>".mysql_error());

      if ($this -> RowCount() > 0)
      {
        $data = array();
        while ($line = mysql_fetch_assoc($this -> sql_data))
        {
          array_push($data, $line);
        }
        return $data;
      } else
      {
        return array();
      }
    }

    function sqlRunOne(&$dump, $sql, $debug = false)
    {
      while ($item = array_pop($this -> sql_params))
      {
        $sql = str_replace($item[0], $item[1], $sql);
      }

      if ($debug) echo "<hr>".$sql."<hr>";

      $this -> sql_data = mysql_query($sql, $this -> sql_link)
        or die($sql."<hr>".mysql_error());

      $dump = mysql_fetch_assoc($this -> sql_data);

      return mysql_num_rows($this->sql_data) > 0;
    }

    function Execute($sql, $debug = false)
    {
      $this->stat_query_count++;
      while ($item = array_pop($this -> sql_params))
      {
        $sql = str_replace($item[0], $item[1], $sql);
      }

      if ($debug) echo "<hr>".$sql."<hr>";

      return mysql_query($sql, $this -> sql_link);
    }

    function RowCount()
    {
      return mysql_num_rows($this -> sql_data);
    }

    function RowAffected()
    {
      return mysql_affected_rows($this -> sql_link);
    }

  }
?>
