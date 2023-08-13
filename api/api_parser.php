<?
  // Логирование действий
  function DebugLog($value, $fail)
  {
    if ($fail) $color = "#FFD2D2"; else $color = "#CAFFCA";
    echo "<div style='background-color:$color'>".$value."</div><hr>";
  }

  // Получение ответа от сервера
  function getResponse($Url, $Params)
  {
    global $COOKIE;
    // User Agent для CURL
    $user_agent = "Mozilla/5.0 (Windows NT 5.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1";
    $user_cookie = dirname(__FILE__)."\data.cookie";

    if (!$ch = curl_init())
    {
      DebugLog(curl_error($ch), true);
      exit;
    }

    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_REFERER,   'http://newtorr.org/');
    curl_setopt($ch, CURLOPT_COOKIE, $COOKIE);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $user_cookie);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $user_cookie);
    curl_setopt($ch, CURLOPT_TIMEOUT,  360);

    if ($Params != "") {
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $Params);
    } else {
      curl_setopt($ch, CURLOPT_POST, 0);
    }

    $Data = curl_exec($ch);
    if ((!$Data) && (curl_getinfo($ch, CURLINFO_HTTP_CODE) != "302")) {
      DebugLog(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL), true);
      DebugLog(curl_error($ch), true);
    }
    curl_close($ch);

    return $Data;
  }

?>
