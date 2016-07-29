DROP DATABASE IF EXISTS `learningPlatform`;

CREATE SCHEMA IF NOT EXISTS `learningPlatform` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;
USE `learningPlatform` ;

-- -----------------------------------------------------
-- Table `learningPlatform`.`user` 用户
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `learningPlatform`.`user` (
	`uid` INT NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(50) NOT NULL, -- 用户名，英文开头，只包含英文和数字，长度不超过30
	`password` VARCHAR(50) NOT NULL, -- 密码，采用sha1(用户名+md5(原密码))加密，md5由前端Angular加密传输
	`email` VARCHAR(100) NOT NULL, -- 邮箱
	`phone` VARCHAR(20) NOT NULL, -- 联系电话
	`gender` INT(1) NOT NULL, -- 性别 0（男）或1（女）
	`studentId` VARCHAR(30) NOT NULL, -- 学号
	`roleId` INT NOT NULL DEFAULT 4, -- 角色（对应role表中rid）
	PRIMARY KEY (`uid`),
	UNIQUE INDEX `uid_UNIQUE` (`uid` ASC)
)ENGINE = InnoDB;
INSERT INTO `learningPlatform`.`user` VALUES (1, 'admin', sha1(concat('admin', md5('admin'))), 'admin@admin.com', 0, '0', 1);

-- -----------------------------------------------------
-- Table `learningPlatform`.`role` 用户角色
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `learningPlatform`.`role` (
	`rid` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL, -- 角色名称
	PRIMARY KEY (`rid`),
	UNIQUE INDEX `rid_UNIQUE` (`rid` ASC)
)ENGINE = InnoDB;
INSERT INTO `learningPlatform`.`role` VALUES (1, '管理员');
INSERT INTO `learningPlatform`.`role` VALUES (2, '老师');
INSERT INTO `learningPlatform`.`role` VALUES (3, '学生');
INSERT INTO `learningPlatform`.`role` VALUES (4, '游客');

-- -----------------------------------------------------
-- Table `learningPlatform`.`course` 课程
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `learningPlatform`.`course` (
	`cid` INT NOT NULL AUTO_INCREMENT,
	`ownerId` INT NOT NULL, -- 创建人uid
	`createTime` INT NOT NULL, -- 创建时间，秒级
	`updateTime` INT NOT NULL, -- 修改时间，秒级
	`name` VARCHAR(50) NOT NULL, -- 课程名称
	`content` TEXT NOT NULL, -- 课程内容
	`visibility` INT NOT NULL DEFAULT 0, -- 可见性，0隐藏，1可见，-1删除
	PRIMARY KEY (`cid`),
	UNIQUE INDEX `cid_UNIQUE` (`cid` ASC)
)ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `learningPlatform`.`point` 知识点
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `learningPlatform`.`point` (
	`pid` INT NOT NULL AUTO_INCREMENT,
	`createTime` INT NOT NULL, -- 创建时间，秒级
	`updateTime` INT NOT NULL, -- 修改时间，秒级
	`importance` INT(1) NOT NULL DEFAULT 0, -- 重要性，[0-5]表示[0-5]个感叹号
	`name` VARCHAR(50) NOT NULL, -- 知识点名称
	`content` TEXT NOT NULL, -- 知识点内容
	`courseId` INT NOT NULL, -- 属于的课程id
	`order` INT NOT NULL DEFAULT 0, -- 排序顺序，`order`相同则按照`pid`排序
	`visibility` INT NOT NULL DEFAULT 0, -- 可见性，0隐藏，1可见，-1删除
	PRIMARY KEY (`pid`),
	UNIQUE INDEX `pid_UNIQUE` (`pid` ASC)
)ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `learningPlatform`.`log` 知识点学习日志
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `learningPlatform`.`log` (
	`lid` INT NOT NULL AUTO_INCREMENT,
	`createTime` INT NOT NULL, -- 创建时间，秒级
	`userId` INT NOT NULL,
	`pointId` INT NOT NULL,
	`courseId` INT NOT NULL,
	`status` INT(1) NOT NULL DEFAULT 0, -- 0学习中  1学习完成
	PRIMARY KEY (`lid`),
	UNIQUE INDEX `lid_UNIQUE` (`lid` ASC)
)ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `learningPlatform`.`discuss` 讨论
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `learningPlatform`.`discuss` (
	`did` INT NOT NULL AUTO_INCREMENT,
	`userId` INT NOT NULL, -- 创建用户id
	`createTime` INT NOT NULL, -- 创建时间，秒级
	`updateTime` INT NOT NULL, -- 修改时间，秒级
	`title` VARCHAR(50) NOT NULL, -- 讨论标题
	`content` TEXT NOT NULL, -- 讨论内容
	`courseId` INT NOT NULL, -- discuss的课程id
	`fatherId` INT NOT NULL, -- 回复discuss的id，默认0表示新开
	PRIMARY KEY (`did`),
	UNIQUE INDEX `did_UNIQUE` (`did` ASC)
)ENGINE = InnoDB;

