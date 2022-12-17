<?php
error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>入店登録</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/registercoming.css">    
    <script src="./js/JQuery.js"></script>
    <script type="text/javascript">
        function addTF(str) {
            document.mySheet.tableno.value += str;
        }
        function addTN(str) {
            document.mySheet.visitors.value += str;
        }

        let focus_flg = 0;

        function focusCheck(){
            if(document.activeElement.name === "tableno"){
                focus_flg = 0;
            }else{
                focus_flg = 1;
            }
        }
        const addNo = (str) =>{

             if(focus_flg === 0){
                document.mySheet.tableno.value += str;
            }else{
                document.mySheet.visitors.value += str;
            }
        } 
        
        $(function() {
            history.pushState(null, null, null);
            $(window).on("popstate", function() {
                history.pushState(null, null, null);
            });

            // submit
            $("#submit").on("click",function(){
                if(!$(".quantity").val() ||  !$(".output").val()){
                    alert("入力してください");
                    return false
                }
            })
        });
    </script>
</head>
<script>
    /* ピッチインピッチアウトによる拡大縮小を禁止 */
    document.documentElement.addEventListener('touchstart', function(e) {
        if (e.touches.length >= 2) {
            e.preventDefault();
        }
    }, {
        passive: false
    });
    /* ダブルタップによる拡大を禁止 */
    var t = 0;
    document.documentElement.addEventListener('touchend', function(e) {
        var now = new Date().getTime();
        if ((now - t) < 350) {
            e.preventDefault();
        }
        t = now;
    }, false);
</script>

<body>
    <header>
        <p class="title">入店登録</p>
        <p class="back"><a href="./index.php">< 戻る</a></p>
    </header>
    <div class="widget">
            <?php
                if (isset($_GET['p'])) {
                    $p = $_GET['p'];
                    $odh_No = $_GET['odh_No'];
                    $odh_Ninzu = $_GET['odh_Ninzu'];
                    $odh_situation = $_GET['odh_situation'];
                    $table_No = $_GET["table_No"];
                }
                // p=2は、変更の場合a
                if ($p == 2) {
                    print("<form name='mySheet' action='rejicom__db.php?p=" . $p . "&odh_No=" . $odh_No . "&odh_situation=" . $odh_situation . "' method='POST' id='form'>\n");
                } else{
                    print("<form name='mySheet' action='rejicom__db.php?p=" . $p . "' method='POST' id='form'>\n  ");
                }
            ?>
            <div style="display: grid;grid-template-columns: 1fr 1fr;margin-top:50px">
                <label>座席<input type="text" name="tableno" class="output" required readonly onclick="focusCheck()"
                <?php
                if ($p == 2) {
                    echo 'placeholder=' . $table_No;
                }else{
                    echo 'placeholder=' . "ボタンで入力";
                }
                ?>
                ></label>
                <label>人数<input type="text" class="quantity" name="visitors" id="visitors"required readonly onclick="focusCheck()"<?php 
                if ($p == 2) {
                    echo 'placeholder=' . $odh_Ninzu;
                }else{
                    echo 'placeholder=' . "ボタンで入力";
                }
                ?>
                ></label>
            </div>
            <div style="margin-top:150px">
                <button type="button" value="A" onClick="addNo(this.value)">A</button>
                <button type="button" value="B" onClick="addNo(this.value)">B</button>
                <button type="button" value="C" onClick="addNo(this.value)">C</button>
                <button type="button" value="D" onClick="addNo(this.value)">D</button><br>
                <button type="button" value="1" onClick="addNo(this.value)">1</button>
                <button type="button" value="2" onClick="addNo(this.value)">2</button>
                <button type="button" value="3" onClick="addNo(this.value)">3</button>
                <button type="button" value="4" onClick="addNo(this.value)">4</button><br>
                <button type="button" value="5" onClick="addNo(this.value)">5</button>
                <button type="button" value="6" onClick="addNo(this.value)">6</button>
                <button type="button" value="7" onClick="addNo(this.value)">7</button>
                <button type="button" value="8" onClick="addNo(this.value)">8</button><br>
                <button type="button" value="9" onClick="addNo(this.value)">9</button>
                <button type="button" value="0" onClick="addNo(this.value)">0</button>
                <button type="button" value="~" onClick="addNo(this.value)">~</button>
                <button type="button" value="," onClick="addNo(this.value)">,</button><br>
            </div>
            <div style="display: grid;grid-template-columns: 1fr 1fr;justify-items:center;align-items:center">
                <input type="reset" class="underbtn reset">
                <button class="underbtn register" id="submit">登録</button>
            </div>
        </form>
    </div>
</body>

</html>