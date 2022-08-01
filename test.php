<!DOCTYPE html>
<html lang="UTF-8">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>テスト</title>
    <link href="test.css" rel="stylesheet">
</head>
<body>
<h1>フォーム</h1>

<form action="test_post_copy.php" method="POST" class="form-example">
    <div class="form-example">
    <label for="tableno">テーブル</label>
    <select name="tableno">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="a">a</option>
        <option value="b">b</option>
        <option value="c">c</option>
        </select>
    </div>
    <div class="form-example">
    <label for="visitors">客数</label>
    <input type="number" name="visitors" id="visitors" required>
    </div>
    <div class="form-example">
    <input type="submit" value="登録">
    </div>
</form>
</body>
</html>