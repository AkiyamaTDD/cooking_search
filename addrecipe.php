<?php

  // セッションスタート
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
    $recipe_name = mysqli_real_escape_string($dbc, trim($_POST['recipe_name']));
    //$ingredients = mysqli_real_escape_string($dbc, trim($_POST['ingredients']));
    $ingredients = $_POST['ingredients'];

    if (count($ingredients) == 0 ) {
      echo '食材を選択してください。';
      exit();
    }
    elseif (count($ingredients) > 5 ) {
      echo '食材の種類が多すぎます。';
      exit();
    }

    if (!empty($recipe_name) && !empty($ingredients)) {
      // 既に登録されているか確認
      $query = "SELECT * FROM cooking_recipe WHERE recipe_name = '$recipe_name'";
      $data = mysqli_query($dbc, $query);
      if (mysqli_num_rows($data) == 0) {
        $query = "INSERT INTO cooking_recipe (recipe_name, ingredient1, ingredient2, ingredient3, ingredient4, ingredient5) VALUES ('$recipe_name', '$ingredients[0]', '$ingredients[1]', '$ingredients[2]', '$ingredients[3]', '$ingredients[4]')";
        mysqli_query($dbc, $query);

        // 追加通知
        echo '<p>' . $recipe_name . 'を料理に追加しました。</p>';

      }
      else {
        // 既に登録されている場合
        echo '<p>この料理は既に登録されています。</p>';
        $recipe_name = "";
      }
    }
    else {
      echo '<p>料理名を入力してください。</p>';
    }
  }

?>

  <p>料理名を明記し、食材を選んで料理を追加してください。</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p>料理名</p>
    <input type="text" id="recipe_name" name="recipe_name">
    <p>食材リスト：料理に主に使用する食材を5個まで選んでください。</p>

<?php

  //食材リストを表示
  $query = "SELECT ci.ingredient_id, ci.ingredient_name, cc.category_id, cc.category_name " .
    "FROM cooking_ingredient AS ci " .
    "INNER JOIN cooking_category AS cc USING (category_id) " .
    "ORDER BY cc.category_id, ci.ingredient_id";
  $data = mysqli_query($dbc, $query);
  $category_sorts = array();
  while ($row = mysqli_fetch_array($data)) {
    array_push($category_sorts, $row);
  }

  $category = $category_sorts[0]['category_name'];
  echo '<fieldset><legend>' . $category_sorts[0]['category_name'] . '</legend>';
  foreach ($category_sorts as $category_sort) {
    if ($category != $category_sort['category_name']) {
      $category = $category_sort['category_name'];
      echo '</fieldset><fieldset><legend>' . $category_sort['category_name'] . '</legend>';
    }

    echo '<input type="checkbox" id="' . $category_sort['ingredient_id'] . '" name="ingredients[]" value= "'. $category_sort['ingredient_id']  . '"/>';
    echo $category_sort['ingredient_name'] ;
  }
  echo '</fieldset>';
  echo '<input type="submit" value="料理を追加" name="submit" />';
  echo '</form>';

  mysqli_close($dbc);

  ?>
