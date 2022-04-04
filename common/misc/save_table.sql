USE app;

DROP TABLE IF EXISTS t2;

CREATE TABLE t2 (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    c1 TEXT
);

DELIMITER $  # 修改分隔符为 $, 以$开始
DROP PROCEDURE IF EXISTS save_table;
CREATE PROCEDURE save_table()
BEGIN
	DECLARE i INT;
	DECLARE str VARCHAR(100);

	SET i = 0;
	SET str = RPAD('', 100, '?');

	WHILE i < 10000 DO
		INSERT INTO t2 (c1) VALUES (str);
		SET i = i + 1;
	END WHILE;
END$  # 在$处结束
DELIMITER ;  # 把分隔符改成默认的;

CALL save_table();

# 创建存储过程的时候，要修改分隔符，一定要注意。
# 在命令行执行即可
