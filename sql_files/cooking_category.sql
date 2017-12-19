CREATE TABLE `cooking_category` (
  `category_id` INT AUTO_INCREMENT,
  `category_name` VARCHAR(32),
  PRIMARY KEY (`category_id`)
);

INSERT INTO `cooking_category` VALUES (1, '野菜');
INSERT INTO `cooking_category` VALUES (2, 'お肉');
INSERT INTO `cooking_category` VALUES (3, '魚介');
INSERT INTO `cooking_category` VALUES (4, '乳製品');
INSERT INTO `cooking_category` VALUES (5, 'その他');
