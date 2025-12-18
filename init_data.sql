CREATE DATABASE IF NOT EXISTS dbh;
USE dbh;

-- 1. 초기화
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS board_categories;
DROP TABLE IF EXISTS personnel;
DROP TABLE IF EXISTS board;
DROP TABLE IF EXISTS activity;
DROP TABLE IF EXISTS members;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS departments; 
SET FOREIGN_KEY_CHECKS = 1;

-- 2. 테이블 생성
CREATE TABLE departments (
    department_id INT PRIMARY KEY AUTO_INCREMENT,
    department_code CHAR(5) UNIQUE NOT NULL,
    department_name VARCHAR(100) NOT NULL
);

CREATE TABLE members (
    member_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id CHAR(10) NOT NULL,
    user_pass CHAR(64) NOT NULL,
    user_name VARCHAR(50) NOT NULL,
    nickname VARCHAR(20),
    birth DATE,
    fk_department_code CHAR(5),
    reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_login_date DATETIME,
    login_yn BOOLEAN NOT NULL DEFAULT FALSE,
    phone CHAR(11) NOT NULL,
    FOREIGN KEY (fk_department_code) REFERENCES departments(department_code) ON DELETE CASCADE
);

CREATE TABLE activity (
    activity_id INT PRIMARY KEY AUTO_INCREMENT,
    max_personnel INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('모집 중', '마감', '취소', '기간만료') NOT NULL
);

CREATE TABLE board (
    board_id INT PRIMARY KEY AUTO_INCREMENT,
    fk_member_id INT NOT NULL,
    fk_activity_id INT,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modify_date DATETIME,
    real_yn TINYINT NOT NULL,
    hits INT,
    isDiffSelect TINYINT,
    FOREIGN KEY (fk_member_id) REFERENCES members(member_id) ON DELETE CASCADE,
    FOREIGN KEY (fk_activity_id) REFERENCES activity(activity_id) ON DELETE CASCADE
);

CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE board_categories (
    board_categories_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fk_board_id INT NOT NULL,
    fk_category_id INT NOT NULL,
    FOREIGN KEY (fk_board_id) REFERENCES board(board_id) ON DELETE CASCADE,
    FOREIGN KEY (fk_category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

CREATE TABLE personnel (
    personnel_id  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fk_activity_id INT NOT NULL,
    fk_member_id INT NOT NULL,
    join_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (fk_activity_id, fk_member_id),
    FOREIGN KEY (fk_activity_id) REFERENCES activity(activity_id) ON DELETE CASCADE,
    FOREIGN KEY (fk_member_id) REFERENCES members(member_id) ON DELETE CASCADE
);

-- 3. 데이터 삽입
INSERT INTO departments (department_code, department_name) VALUES
('BU01', '컴퓨터공학부'), ('BU02', '경영학부'), ('BU03', '디자인영상학부'),
('BU04', '첨단IT학부'), ('BU05', '외식조리학부'), ('BU06', '스포츠과학부'),
('BU07', '어문학부'), ('BU08', '사회복지학부');

INSERT INTO categories (category_id, category_name) VALUES
(1, '스포츠'), (2, '공부'), (3, '식사'), (4, '택시'), (5, '아웃팅');

INSERT INTO members (member_id, user_id, user_pass, user_name, nickname, birth, fk_department_code, reg_date, last_login_date, login_yn, phone) VALUES
(1, 'test1', '1234', '강호동', '강호동', '1970-07-14', 'BU01', '2025-12-13 00:43:56', '2025-12-13 23:17:23', 0, '01011112222'),
(2, 'test2', '1234', '유재석', '유재석', '1972-08-14', 'BU06', '2025-12-13 00:53:15', '2025-12-13 00:53:19', 0, '01011113333'),
(3, 'test3', '1234', '신동엽', '신동엽', '1971-02-17', 'BU02', '2025-12-13 00:57:56', '2025-12-13 00:58:02', 0, '01011114444'),
(4, 'test4', '1234', '이효리', '효리네민박', '1979-05-10', 'BU03', NOW(), NULL, 0, '01011115555'),
(5, 'test5', '1234', '박명수', '거성', '1970-09-27', 'BU06', NOW(), NULL, 0, '01011116666'),
(6, 'test6', '1234', '아이유', '국힙원탑', '1993-05-16', 'BU01', NOW(), NULL, 0, '01011117777'),
(7, 'test7', '1234', '손흥민', '쏘니', '1992-07-08', 'BU07', NOW(), NULL, 0, '01011118888'),
(8, 'test8', '1234', '김연아', '퀸연아', '1990-09-05', 'BU07', NOW(), NULL, 0, '01011119999');

INSERT INTO activity (activity_id, max_personnel, start_date, end_date, status) VALUES
(1, 8, '2025-12-20', '2026-12-20', '모집 중'), 
(2, 4, '2025-12-13', '2025-12-13', '마감'),
(3, 2, '2025-12-13', '2025-12-13', '마감'),
(4, 4, '2025-12-15', '2026-02-25', '모집 중'), 
(5, 3, '2025-12-01', '2025-12-01', '기간만료'), 
(6, 6, '2025-12-15', '2025-12-15', '마감'),
(7, 10, '2025-12-20', '2026-12-20', '모집 중'),
(8, 4, '2025-12-24', '2025-12-24', '모집 중'),
(9, 4, '2025-12-14', '2025-12-14', '취소'),
(10, 4, '2025-12-15', '2025-12-15', '마감'),
(11, 6, '2025-12-21', '2025-12-21', '모집 중');

INSERT INTO board (board_id, fk_member_id, fk_activity_id, title, content, reg_date, modify_date, real_yn, hits, isDiffSelect) VALUES
(1, 1, 1, '20일 18시에 4대4 농구 인원 모집합니다', '장소: 백석대 정류장 앞 농구장\n시간: 2025.12.20 18시\n마감되면 개별 연락드리겠습니다!', '2025-12-13 00:48:57', NULL, 1, 13, 2),
(2, 1, 1, '20일(당일) 4대4 농구하실분!', '2명만 더 모이면 4대4 가능합니다!', '2025-12-20 00:51:35', NULL, 1, 21, 1),
(3, 2, 2, '오늘 점심 같이 먹을 사람', '혼밥 싫어하는 사람 어디없나?', '2025-12-13 00:56:07', NULL, 1, 8, NULL),
(4, 3, 3, '터미널에서 학교 택시 탈사람 1명 급구', 'ㅈㄱㄴ', '2025-12-13 00:59:21', NULL, 1, 8, NULL),
(5, 6, 4, '정보처리기사 실기 같이 준비하실 분', '혼자 하려니까 진도가 안 나가네요.\n 저녁에 도서관에서 같이 공부해요!', NOW(), NULL, 1, 25, 2),
(6, 4, 5, '12/1 아침 9시 천안역 가실 분~(만료됨)', '택시비 N빵 하실 분 구합니다.\n백석대 정문에서 8:50 집결.', NOW(), NULL, 1, 5, NULL),
(7, 5, 6, '오늘 저녁 삼겹살 파티 모집(마감)', '기분이 우울해서 고기 먹으러 갑니다.\n인원 다 차서 자동 마감되었습니다.', NOW(), NULL, 1, 42, NULL),
(8, 7, 7, '이번 주말 풋살 한 게임 뛰실 분', '상대 팀 섭외 완료되었습니다.\n몸만 오시면 됩니다. 초보 환영!', NOW(), NULL, 1, 10, 1),
(9, 2, 8, '크리스마스 이브에 에버랜드 가실 분 구해요', '남자 둘이서 가기 좀 그래서.. 같이 가실 학우분들 구합니다.\n자유이용권 할인 카드 필수!', NOW(), NULL, 1, 45, NULL),
(10, 3, 9, '오늘 학식 같이 드실 분 (취소됨)', '죄송합니다. 급한 사정이 생겨서 오늘 모임은 취소하겠습니다.\n다음에 다시 올릴게요.', NOW(), NULL, 1, 12, NULL),
(11, 6, 10, '천안역 4시 출발 택시 팟 (마감)', '4명 꽉 채워서 가려고 합니다.\n인원 다 차서 마감합니다!', NOW(), NULL, 1, 30, NULL),
(12, 7, 11, '주말 배드민턴 복식 경기 하실 분', '실내 체육관 예약했습니다.\n라켓 없으시면 빌려드려요.', NOW(), NULL, 1, 18, 1);

INSERT INTO board_categories (board_categories_id, fk_board_id, fk_category_id) VALUES
(1, 1, 1), (2, 2, 1), (3, 3, 3), (4, 4, 4),
(5, 5, 2), (6, 6, 4), (7, 7, 3), (8, 8, 1),
(9, 9, 5), (10, 10, 3), (11, 11, 4), (12, 12, 1);

INSERT INTO personnel (fk_activity_id, fk_member_id, join_date) VALUES
(1, 1, '2025-12-13 00:48:57'), (1, 2, NOW()), (1, 3, NOW()), (1, 7, NOW()), (1, 6, NOW()), (1, 8, NOW()),
(2, 1, NOW()), (2, 2, '2025-12-13 00:53:49'), 
(2, 3, '2025-12-13 00:56:07'), (2, 4, NOW()),
(3, 3, '2025-12-13 00:58:12'), (3, 1, '2025-12-13 00:59:38'),
(4, 6, NOW()), (4, 8, NOW()), 
(5, 4, NOW()), (5, 2, NOW()), 
(6, 5, NOW()), (6, 1, NOW()), (6, 3, NOW()), (6, 4, NOW()), (6, 2, NOW()), (6, 6, NOW()),
(7, 7, NOW()),
(8, 2, NOW()), (8, 1, NOW()),
(9, 3, NOW()),
(10, 6, NOW()), (10, 1, NOW()), (10, 2, NOW()), (10, 4, NOW()),
(11, 7, NOW()), (11, 5, NOW());


-- 4. 트리거 생성
CREATE TRIGGER UpdateStatusOnLogin
AFTER UPDATE ON members
FOR EACH ROW
    UPDATE activity
    SET status = 4
    WHERE end_date < CURDATE()
      AND status = 1
      AND NEW.login_yn = 1;