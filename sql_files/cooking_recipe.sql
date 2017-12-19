CREATE TABLE `cooking_recipe` (
  `recipe_id` INT AUTO_INCREMENT,
  `recipe_name` VARCHAR(32),
  `ingredient1` INT,
  `ingredient2` INT,
  `ingredient3` INT,
  `ingredient4` INT,
  `ingredient5` INT,
  PRIMARY KEY (`recipe_id`)
);

INSERT INTO `cooking_recipe` (recipe_id, recipe_name, ingredient1, ingredient2, ingredient3, ingredient4) VALUES (1, 'カレー', 1, 2, 3, 4);
