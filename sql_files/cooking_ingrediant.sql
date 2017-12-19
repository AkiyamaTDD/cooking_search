CREATE TABLE `cooking_ingredient` (
  `ingredient_id` INT AUTO_INCREMENT,
  `ingredient_name` VARCHAR(32),
  `category_id` INT,
  PRIMARY KEY (`ingredient_id`)
);

INSERT INTO `cooking_ingredient` VALUES (1, 'たまねぎ', 1);
INSERT INTO `cooking_ingredient` VALUES (2, 'にんじん', 1);
INSERT INTO `cooking_ingredient` VALUES (3, 'じゃがいも', 1);
INSERT INTO `cooking_ingredient` VALUES (4, 'カレー用牛肉', 2);
