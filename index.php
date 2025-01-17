<?php

/*
  https://cine.x20.space/
  ?day=[1-7]  # Montag bis Sonntag
  &from=12:00 # von-uhrzeit
  &to=17:00   # bis-uhrzeit
  &all        # zeige, wenn heute, auch vergangene, ausser wenn von-bis eingeschraenkt
  &by=movie   # movie|theater, defaults to theater
*/

date_default_timezone_set( 'Europe/Paris' );
$url = 'https://www.cinecitta.de/common/ajax.php?bereich=portal&modul_id=101&klasse=vorstellungen&cli_mode=1&com=anzeigen_spielplan';
$lfn = 'cine-spielplan.json';

$tage = array(
    1 => "Montag",
    2 => "Dienstag",
    3 => "Mittwoch",
    4 => "Donnerstag",
    5 => "Freitag",
    6 => "Samstag", 
    7 => "Sonntag",
);

$kinos = array(
    "CINECITTA'<br/>Cinemagnum" => "524, 610m2",

    "CINECITTA'<br/>Kino 1" =>  "549, 109",
    "CINECITTA'<br/>Kino 3" =>  "465, 109",
    "CINECITTA'<br/>Kino 12" => "417, 96",
    "CINECITTA'<br/>Kino 2" =>  "319, 82",
    "CINECITTA'<br/>Kino 4" =>  "317, 72",
    "CINECITTA'<br/>Kino 5" =>  "208, 38",
    "CINECITTA'<br/>Kino 10" => "186, 38",
    "CINECITTA'<br/>Kino 11" => "186, 38",
    "CINECITTA'<br/>Kino 7" =>  "173, 34",
    "CINECITTA'<br/>Kino 13" => "147, 34",
    "CINECITTA'<br/>Kino 17" => "140, 38",
    "CINECITTA'<br/>Kino 9" =>  "139, 27",
    "CINECITTA'<br/>Kino 8" =>  "131, 27",
    "CINECITTA'<br/>Deluxe 1" => "120, 75",
    "CINECITTA'<br/>Deluxe 2" => "105, 64",
    "CINECITTA'<br/>Kino 6" =>  "105, 27",
    "CINECITTA'<br/>Deluxe 4" => "63, 55",
    "CINECITTA'<br/>Deluxe 3" => "49, 34",
    "CINECITTA'<br/>Studio 2" => "47, 18",
    "CINECITTA'<br/>Studio 3" => "43, 12",
    "CINECITTA'<br/>Studio 1" => "33, 18",
    "CINECITTA'<br/>Deluxe 5 Blackbox" => "31, 17",

    "CINECITTA'<br/>Open Air" => "?", 

    "Manhattan Erlangen<br/>Kino 1" => "?", 
    "Manhattan Erlangen<br/>Kino 2" => "?", 
    "Manhattan Erlangen<br/>Kino 3" => "?", 

    "Meisengeige<br/>Kino 1" => "?", 
    "Meisengeige<br/>Kino 2" => "?", 

    "Metropolis<br/>Kino 1" => "?", 
    "Metropolis<br/>Kino 2" => "?", 
);

function display_table_by_theater($k, $f) {
  uasort($f, function(array $x, array $y) {return $x <=> $y; });
  $sorted_filme = array_reverse(array_keys($f));
?>
     <table id="movies">
       <tr> <!-- header -->
         <td>/</td>
<?php    foreach($k as $kino => $w) { ?>
           <td class='rotate'>
             <div title="<?= $k[$kino] ?>">
               <?= $kino ?>
             </div>
           </td>
<?php    } ?>
       </tr>

<?php  foreach($sorted_filme as $film => $v) { ?>
         <tr<?= (preg_match("/OV/", $v))? " style='background-color: #cef;'" :""?>>
           <td title="<?= $f[$v] ?>" ><?= $v ?></td>
<?php      foreach($k as $kino => $w) { 
             if (array_key_exists($v, $f) && array_key_exists($kino, $f[$v])) { 
               $rgb = 0; $color = "#fff";
               $border = "";
               $cell = join("<br>", $f[$v][$kino]);
               if (preg_match("/UA/", $cell)) { $rgb = $rgb + 0x800000; }
               if (preg_match("/3D/", $cell)) { $rgb = $rgb + 0x8000; }
               if (preg_match("/OV/", $cell)) { $rgb = $rgb + 0xF0; }
               if (preg_match("/mU/", $cell)) { $rgb = $rgb + 0xA; }
               if (preg_match("/deaktiviert/", $cell)) { $rgb = 0xAAAAAA; }
               if (preg_match("/gelb/", $cell)) { $border = "border: 5px solid #a90;"; }
               if (preg_match("/orange/", $cell)) { $border = "border: 5px solid #d85;"; }
      ?>       <td style='padding: 2px; <?= $border ?>; color: #fff; background-color: #<?= substr('00000' . dechex($rgb), -6); ?>;'>
                 <?= $cell ?>
               </td>
<?php        } else { print("<td/>"); }
           } ?>
         </tr>
<?php  }  ?>
     </table>
<?php } 

function display_table_by_movie($k, $f) {
  uasort($f, function(array $x, array $y) {return $x <=> $y; });
  $sorted_filme = array_reverse(array_keys($f));
?>
     <table id="movies">
       <tr>
<?php    foreach($sorted_filme as $film => $v) { ?>
           <td style="vertical-align: bottom;"><div style="text-align:center;" title="<?= $f[$v] ?>" ><?= $v ?></div></td>
<?php    }  ?>
       </tr>
<?php  foreach($sorted_filme as $film => $v) { ?>
<?php  $cell = array(); ?>
<?php      foreach($k as $kino => $w) { 
             if (array_key_exists($v, $f) && array_key_exists($kino, $f[$v])) { 
               $cell = array_merge($cell, $f[$v][$kino]);
             } ?>
<?php      } ?>
           <td>
<?php        sort($cell);
             foreach ($cell as $c) {
               $rgb = 0; $color = "#fff";
               $border = "";
               if (preg_match("/UA/", $c)) { $rgb = $rgb + 0x800000; }
               if (preg_match("/3D/", $c)) { $rgb = $rgb + 0x8000; }
               if (preg_match("/OV/", $c)) { $rgb = $rgb + 0xF0; }
               if (preg_match("/mU/", $c)) { $rgb = $rgb + 0xA; }
               if (preg_match("/deaktiviert/", $c)) { $rgb = 0xAAAAAA; }
               if (preg_match("/gelb/", $c)) { $border = "border: 5px solid #a90;"; }
               if (preg_match("/orange/", $c)) { $border = "border: 5px solid #d85;"; } ?>
               <div style="padding: 2pt; color: #fff; background-color: <?= substr('00000' . dechex($rgb), -6); ?>; <?= $border ?>"><?= $c ?></div>
<?php        } ?>
           </td>
<?php  }  ?>
     </table>
<?php } 


function is_past($testday, $referenceday){
    if ( $testday <= 3 && $referenceday > 3 ) { return false; } 
    if ( $testday < $referenceday ) { return true; }
    return false;
}

function get_cache_or_remote($local, $remote) {
    $fd = fopen($local, "r+");

    if ($fd == false  ||  fstat($fd)['mtime'] < (time() - 3600)) {
        if ($fd == true) { fclose($fd); }
        print("<div style='color: red;'>remote fetch</div>");
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_POST => 1,
            CURLOPT_URL => $remote,
            CURLOPT_RETURNTRANSFER => 1
        ));
        $data = curl_exec($curl);

        $fd = fopen($local, "w");
        fwrite($fd, $data);
        fclose($fd);
    } 
    return file($local);
}

$today = date("w");
if ($today == 0) { $today = 7; }
if (array_key_exists('day', $_GET)) { $qday = intval($_GET['day']); } else { $qday = 0; }
if ($qday == 0) { $qday = $today; }

$tag = $tage[$qday];

$data = get_cache_or_remote($lfn, $url);
$json = json_decode($data[0]);
$items = $json->{'daten'}->{'items'};
$filme = array();


$instances = array();
foreach($items as $item) {
  foreach($item->{'theater'} as $theater) { 
    foreach($theater->{'leinwaende'} as $leinwand) { 
      $t = $theater->{'theater_name'} . "<br/>" .  $leinwand->{'leinwand_name'};// . "<br/><br/>" . 
      foreach($leinwand->{'vorstellungen'} as $vorstellung) { 
        $flags = array();
        foreach ($leinwand->{'release_flags'} as $flag) {
          $fn = preg_replace("/Ukrainisch/", "UA", $flag->{'flag_name'});
          array_push($flags, $fn);
        }
        $flags = array_diff($flags, array("ATMOS", "finity", "D-BOX", "Onyx LED"));
        array_push($flags, $vorstellung->{'belegung_ampel'});
        if (property_exists($vorstellung, 'deaktiviert') && $vorstellung->{'deaktiviert'} == true) { array_push($flags, "deaktiviert"); };

        $y = strftime("%H:%M");
        $d = $vorstellung->{'tag_der_woche'};
        $u = $vorstellung->{'uhrzeit'};
        $f = (property_exists($item, 'film_neu') && $item->{'film_neu'} ? "<span class='neu'>" . $item->{'film_neu'} . "!</span> ": "") . $item->{'film_titel'};
        $filme[$f] = preg_replace("/\"/", "'", $item->{'film_beschreibung'});

        $show = true;
        if (( in_array("OV", $flags) && ($item->{'film_ist_ov'} == 1 ))) { $show = false; } // dupefilter: ov wird als ov und im normalen film eingeblendet
        if (array_key_exists('from', $_GET) && $_GET['from'] > $u)       { $show = false; }
        if (array_key_exists('to',   $_GET) && $_GET['to']   < $u)       { $show = false; }
        if ($qday == $today && $u < $y && !array_key_exists('from', $_GET) && !array_key_exists('to', $_GET) 
            && !array_key_exists('all', $_GET)) { $show = false; } // ignoriere vergangene sendungen fuer heute
        if ($d != $qday) { $show = false; } // ignoriere alle tage ausser den abgefragten

        if ($show == true) { // dupe check
              if (! array_key_exists($f, $instances))     { $instances[$f] = array(); }
              if (! array_key_exists($t, $instances[$f])) { $instances[$f][$t] = array(); }
              array_push($instances[$f][$t], sprintf("%s<sup><small>%s</small></sup>", $u,  (count($flags) > 0 ? ("&nbsp;" . join("&nbsp;", $flags)) : "")));
        }
      }
    }
  }
}


?>
<html>
  <header>
   <style>
     body {
       font-size: 0.8rem;
       font-family: Arial;
     }
     table, td, th {
       font-size: 0.8rem;
       border: 1px black solid;
       border-collapse: collapse;
 	   border-spacing: 0px;
     }
     td.rotate {
       padding: 10px;
       height: 200pt;
     }
     .rotate {
       text-align: center;
       /* white-space: nowrap; */ 
       vertical-align: middle !important;
       width: 1.5em;
     }
     .rotate div {
        
                 filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
              transform: rotate(-90.0deg);  /* FF3.5+ */
            margin-left: -10em;
           margin-right: -10em;
     }
     #links {
         background-color: #ecf; 
         padding: 5px;
         /* evil hack to stop the table headers to block the links */
         position: relative;
         z-index: 9999;
     }
     #links span {
         padding: 5px;
     }
     #legend {
         background-color: #cef; 
         padding: 3pt;
     }
     #movies tr td {
         vertical-align: top;
     }
     .warning {
         padding: 2px;
         background-color: #c12;
         color: #fff;
     }
     .neu {
         font-weight: 800;
         color: #b52;
     }
     .today { 
         background-color: #bad; 
     }
     .qday {
         border: 3pt solid #099;
         border-radius: 5pt;
     }
   </style>
 </header>
 <body>
   <!-- +2 -->
     <div id="links">
<?php 
       foreach ($tage as $loop => $loopname) {
          $class = "links";
          if ($loop == $qday)   { $class .= " qday";  }
          if ($loop == $today)  { $class .= " today"; }

          if (is_past($loop, $today)) { 
              print(" <span class='$class'>"  . $tage[$loop] ."</span>"); 
          } else { 
              print(" <span class='$class'><a href='/?day=" . $loop ."'>" . $tage[$loop] . "</a></span>"); 
          }
 
       }  ?>
       (<?= $qday ?> / <?= $today ?>)

       <span class='warning'>
       <?= array_key_exists('from', $_GET) ? " -  <b>Filter von: " . $_GET['from'] :"" ?>
       <?= array_key_exists('to', $_GET)   ? " -  <b>Filter bis: " . $_GET['to'] :"" ?>
       <?= array_key_exists('all', $_GET)  ? " -  <b>Filter ALLES" :"" ?>
       </span>
     </div>
     <div id="legend">
       <b><u>Legende</u></b>: Kinos sind nach Grösse des Saals sortiert, Filme nach Anzahl Vorstellungen, Hover ueber beides zeigt Details (Sitze, Grösse Leinwand qm beim Kino), 
       <b>3D</b>: 3-dimensional (mit Brille), <b>OV</b>: Original Version, <b>mU</b>: mit Untertiteln, <b>UA</b>: Ukrainisch, <b>gelb & orange</b>: fast ausverkauft
     </div>
<?php
     if (is_past($qday, $today)) {
       print("Dont look back in anger (You asked for a day that's past today)");
       exit();
     }

/* __ the actual table __ */
     if (! array_key_exists('by', $_GET) || $_GET['by'] == 'theater' ) {
       display_table_by_theater($kinos, $instances); 
     } else if ($_GET['by'] == 'movie' ) {
       display_table_by_movie($kinos, $instances); 
     } else {
       print("invalid 'BY' parameter");
     }

?>
     <small><a href='https://gl.v4.x20.space/mc/cine/-/blob/main/index.php'>code</a> or <a href='https://github.com/mc/cine/blob/main/index.php'>fork</a></small>
  </body>
</html>
