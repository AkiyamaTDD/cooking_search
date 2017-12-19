<?php

  //材料リスト作成関数
  function make_ingredients_list ($dbc) {
    $query = "SELECT * FROM cooking_ingredient";
    $data = mysqli_query($dbc, $query);
    $lists = array();
    while ($row = mysqli_fetch_array($data)) {
      $id = (int) $row['ingredient_id'];
      $lists[$id] = $row['ingredient_name'];
    }
    return $lists;
  }

  //料理検索クエリ作成関数
  function build_query($ingredients) {
    if (count($ingredients) == 0 ) {
      echo '食材が選択されていません。';
      exit();
    }
    elseif (count($ingredients) > 3) {
      echo '4個以上の食材が選択されています。';
      exit();
    }

    $search_query = "SELECT * FROM cooking_recipe";
    $temp_query = array();
    for ($i=0 ; $i<count($ingredients) ; $i++) {
      // 食材が1～5のどこに登録されていてもひっかかるようなクエリ作成
      $temp_query[$i] = "(ingredient1 = '$ingredients[$i]' OR ingredient2 = '$ingredients[$i]' OR ingredient3 = '$ingredients[$i]' OR ingredient4 = '$ingredients[$i]' OR ingredient5 = '$ingredients[$i]') ";
    }

    $temp_query_sum = implode(' AND ', $temp_query);

    if (!empty($temp_query)) {
      $search_query .= " WHERE $temp_query_sum";
    }
    return $search_query;
  }

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

  // データベース接続
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if(!isset($_POST['submit'])) {
  // 食材リスト作成
    $query = "SELECT ci.ingredient_id, ci.ingredient_name, cc.category_id, cc.category_name " .
      "FROM cooking_ingredient AS ci " .
      "INNER JOIN cooking_category AS cc USING (category_id) " .
      "ORDER BY cc.category_id, ci.ingredient_id";
    $data = mysqli_query($dbc, $query);
    $category_sorts = array();
    while ($row = mysqli_fetch_array($data)) {
      array_push($category_sorts, $row);
    }
    mysqli_close($dbc);

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<p>食材リスト：使いたい食材を3個まで選んでください。</p>';
    $category = $category_sorts[0]['category_name'];
    echo '<fieldset><legend>' . $category_sorts[0]['category_name'] . '</legend>';
    foreach ($category_sorts as $category_sort) {
      if ($category != $category_sort['category_name']) {
        $category = $category_sort['category_name'];
        echo '</fieldset><fieldset><legend>' . $category_sort['category_name'] . '</legend>';
      }

    //  echo '<label ' . ($category_sort['ingredient_name'] == NULL ? 'class="error"' : '') . ' for="' . $category_sort['ingredient_id'] . '">' . $response['ingredient_name'] . ':</label>';
      echo '<input type="checkbox" id="' . $category_sort['ingredient_id'] . '" name="ingredients[]" value= "'. $category_sort['ingredient_id']  . '"/>';
      echo $category_sort['ingredient_name'] ;
    }
    echo '</fieldset>';
    echo '<input type="submit" value="料理を検索" name="submit" />';
    echo '</form>';
  }
  else {

    //検索結果画面
  ?>

    <table border="1">
      <thead>
        <tr>
          <th width="200">料理名</th><th width="125">材料1</th><th width="125">材料2</th><th width="125">材料3</th><th width="125">材料4</th><th width="125">材料5</th>
        </tr>
      </thead>
      <tbody>

<?php

    // 食材をidで引っ張ってこれるように処理
    $ingredientslist = make_ingredients_list($dbc);
    $ingredients = $_POST['ingredients'];
    $query = build_query($ingredients);
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
      echo '<tr>';
      echo '<td>' . $row['recipe_name'] . '</td>';
      echo '<td>' . $ingredientslist[$row['ingredient1']] . '</td>';
      echo '<td>' . $ingredientslist[$row['ingredient2']] . '</td>';
      echo '<td>' . $ingredientslist[$row['ingredient3']] . '</td>';
      echo '<td>' . $ingredientslist[$row['ingredient4']] . '</td>';
      echo '<td>' . $ingredientslist[$row['ingredient5']] . '</td>';
      echo '</tr>';
    }
    echo '</tbody></table>';
    mysqli_close($dbc);


  }


?>
