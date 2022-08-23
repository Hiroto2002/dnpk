<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <title>入店登録</title>
        <link rel="stylesheet" href="css/registercoming.css">
        <script type="text/javascript">
            function addTF(str)
            {
                document.mySheet.tableno.value += str;
            }
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
        $(function(){
            history.pushState(null, null, null);
            $(window).on("popstate", function(){
                history.pushState(null, null, null);
            });
        });
        </script>
    </head>
    <script>
        /* ピッチインピッチアウトによる拡大縮小を禁止 */
        document.documentElement.addEventListener('touchstart', function (e) {
        if (e.touches.length >= 2) {e.preventDefault();}
        }, {passive: false});
        /* ダブルタップによる拡大を禁止 */
        var t = 0;
        document.documentElement.addEventListener('touchend', function (e) {
        var now = new Date().getTime();
        if ((now - t) < 350){
            e.preventDefault();
        }
        t = now;
        }, false);
    </script>
    <body>
        <header style="text-align:center;line-height: 1.5;">
            <p class="back"><a href="./index.php">＜戻る</a></p>
            <p class="title">入店登録</p> 
        </header>
        <div class="widget">
        <?php
        if(isset($_GET['p'])) {
            $p = $_GET['p'];
            $odh_No= $_GET['odh_No'];
            $odh_Ninzu= $_GET['odh_Ninzu'];
            $odh_situation= $_GET['odh_situation'];
        }
        // p=2　は、変更の場合a
        if($p==2){
            print("<form name='mySheet' action='rejicom__db.php?p=".$p."&odh_No=".$odh_No."&odh_situation=".$odh_situation."' method='POST'>\n");
        }else{
            print("<form name='mySheet' action='rejicom__db.php?p=".$p."' method='POST'>\n");
        }
        ?>
                <button type="button" value="A" onClick="addTF(this.value)">A</button>
                <button type="button" value="B" onClick="addTF(this.value)">B</button>
                <button type="button" value="C" onClick="addTF(this.value)">C</button>
                <button type="button" value="D" onClick="addTF(this.value)">D</button><br>
                <button type="button" value="1" onClick="addTF(this.value)">1</button>
                <button type="button" value="2" onClick="addTF(this.value)">2</button>
                <button type="button" value="3" onClick="addTF(this.value)">3</button>
                <button type="button" value="4" onClick="addTF(this.value)">4</button><br>
                <button type="button" value="5" onClick="addTF(this.value)">5</button>
                <button type="button" value="6" onClick="addTF(this.value)">6</button>
                <button type="button" value="7" onClick="addTF(this.value)">7</button>
                <button type="button" value="8" onClick="addTF(this.value)">8</button><br>
                <button type="button" value="9" onClick="addTF(this.value)">9</button>
                <button type="button" value="0" onClick="addTF(this.value)">0</button>
                <button type="button" value="~" onClick="addTF(this.value)">~</button>
                <button type="button" value="," onClick="addTF(this.value)">,</button><br>
                <input type="reset" class="reset"><br>
                <input type="text" placeholder="座席をボタンで入力して下さい" name="tableno" class="output" required readonly><br>
                <div>
                
                    <input type="text" pattern="[0-9]*" class="quantity" name="visitors" id="visitors" placeholder="人数を入力して下さい" required <?php if($p==2) echo 'value='.$odh_Ninzu ?>>
                </div>
                <input type="submit" value="登録" class="register">
            </form> 
        </div>
        <footer style="text-align:center; font-size:20px;line-height: 11;" >
            <p style="color: rgb(255, 255, 255);">Copyright © DNPK.JP All Rights Reserved.</p> 
        </footer>
    </body>
</html>
