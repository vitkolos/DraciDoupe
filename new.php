<!doctype html>
<html>
    <head>
	<meta name="robots" content="noindex, nofollow">
	<meta charset="utf-8">
        <title>Nový room</title>
		<?php
		session_start();
if (!isset($_SESSION["vypravec"])) {echo '<meta http-equiv="refresh" content="0;url=index.php">';}
?>
    </head>
    <body>
	<a href="index.php">Zpět</a>
        <div style="text-align:center"><form action="new.php" method="post"><table style="text-align:left; display:inline-block;"><tr><td>Název: </td><td><input type="text" name="nazev"></td></tr><tr><td>Tvůj nick (jaký chceš): </td><td><input type="text" name="owner" <?php if(isset($_SESSION['nick'])){echo 'value="'.$_SESSION["nick"].'"';} ?> ></td></tr></table><br><input type="submit" value="Odeslat"></form><br><br>
		<?php
		require_once ("databaze.php");
		if (isset($_SESSION["premiovy"])) {
			$maxhraci = 50;
		}
		else {
			$maxhraci = 5;
		}
		if (isset($_POST['nazev']) && isset($_POST['owner'])) {
			$_SESSION["nick"] = $_POST['owner'];
			zapis ("INSERT INTO rooms(nazev, vypravec, zalozeno, cas, hraci, maxhraci) VALUES ('".$_POST['nazev']."', '".$_POST['owner']."', ".time().", 10, 1, ".$maxhraci.")");
			$db = new PDO($dbset, $dbnick, $dbpass);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$parametry = array();
			$dotaz = $db->prepare("SELECT * FROM rooms WHERE nazev='".$_POST["nazev"]."'");
			$dotaz->execute($parametry);
			for ($i = 0; $vystup = $dotaz->fetch(); $i++) {
				$roomid = $vystup['id'];
			}
			$_SESSION["owner"] = $roomid;
			zapis('CREATE TABLE `bajker006.wz7970`.`room'.$roomid.'` ( `id` INT NOT NULL AUTO_INCREMENT , `jmeno` TEXT CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL , `pohlavi` TEXT CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL , `penize` INT NOT NULL , `cp` INT NOT NULL , `hp` INT NOT NULL, PRIMARY KEY (id) ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_czech_ci');
			zapis('CREATE TABLE `bajker006.wz7970`.`roomchat'.$roomid.'` ( `id` INT NOT NULL AUTO_INCREMENT , `autor` int(11) NOT NULL , `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP , `text` text CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL , PRIMARY KEY (id) ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_czech_ci');
			echo 'Hotovo!<meta http-equiv="refresh" content="1;url=room.php?id='.$roomid.'">';
		}
		?>
		</div>
		<br><br>
	</body>
</html>