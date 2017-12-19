<?php

  // セッション開始
  require_once('startsession.php');

  // ヘッダー
  $page_title = '1品で冷蔵庫を綺麗に！';
  require_once('header.php');
  echo '<h1>1品で冷蔵庫を綺麗に！</h1>';

  require_once('appvars.php');
  require_once('connectvars.php');

  // ナビメニュー
  require_once('navmenu.php');

  // データベースと接続
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
    // POSTからデータを取得
    $ingredient_name = mysqli_real_escape_string($dbc, trim($_POST['ingredient_name']));
    $category_id = mysqli_real_escape_string($dbc, trim($_POST['category']));



    if (!empty($ingredient_name) && !empty($category_id)) {
      // 既に登録されていないか確認
      $query = "SELECT * FROM cooking_ingredient WHERE ingredient_name = '$ingredient_name'";
      $data = mysqli_query($dbc, $query);
      if (mysqli_num_rows($data) == 0) {
        $query = "INSERT INTO cooking_ingredient (ingredient_name, category_id) VALUES ('$ingredient_name', '$category_id')";
        mysqli_query($dbc, $query);

        // 追加通知
        echo '<p>' . $ingredient_name . 'をレシピに追加しました。</p>';
      }
      else {
        // 登録されていた場合
        echo '<p>この食材は既に登録されています</p>';
        $ingredient_name = "";
      }
    }
    elseif (!isset($category_id)) {
      echo 'カテゴリーを選択してください。';
      exit();
    }
    else {
      echo '<p>食材名を入力してください。</p>';
    }
  }

?>

  <p>食材名を入力し、カテゴリーを選んで食材を追加してください。</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p>食材名</p>
    <input type="text" id="ingredient_name" name="ingredient_name">
    <p>カテゴリーリスト：追加する食材に当てはまるカテゴリーを選んでください。</p>

<?php

  // カテゴリー表示
  $query = "SELECT * FROM cooking_category ORDER BY category_id";
  $data = mysqli_query($dbc, $query);
  echo '<fieldset>';
  while ($row = mysqli_fetch_array($data)) {
    echo '<input type="radio" id="' . $row['category_id'] . '" name="category" value="' . $row['category_id'] . '"/>' ;
    echo $row['category_name'];
    echo '<br />';
  }
  echo '</fieldset>';
  echo '<input type="submit" value="食材を追加" name="submit" />';
  echo '</form>';

  mysqli_close($dbc);

  ?>
