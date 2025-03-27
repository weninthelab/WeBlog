CREATE DATABASE IF NOT EXISTS weblog;
USE weblog;

-- Bảng users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(100),
  email VARCHAR(100),
  role VARCHAR(20), -- admin, premium, user, editor
  bio TEXT,
  avatar_path VARCHAR(255), 
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng posts
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  title VARCHAR(200),
  content TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  views INT DEFAULT 0,
  premium BOOLEAN DEFAULT FALSE
);

-- Bảng comments
CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  user_id INT,
  comment TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng logs
CREATE TABLE logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  action TEXT,
  ip_address VARCHAR(50),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng messages (hộp thư nội bộ)
CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT,
  receiver_id INT,
  subject VARCHAR(200),
  message TEXT,
  sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  is_read BOOLEAN DEFAULT FALSE
);

-- Bảng user_avatars (lưu file avatar người dùng)
CREATE TABLE user_avatars (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  file_name VARCHAR(255),
  file_path VARCHAR(255),
  uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng subscriptions
CREATE TABLE subscriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  plan VARCHAR(50),
  price DECIMAL(10,2),
  start_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  end_date DATETIME,
  status VARCHAR(20) -- active, expired, cancelled
);

-- Bảng orders (ghi nhận đơn hàng thanh toán)
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  amount DECIMAL(10,2),
  status VARCHAR(20), -- pending, completed, failed
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  payment_method VARCHAR(50),
  card_number VARCHAR(50), -- deliberately insecure (plain text)
  card_expiry VARCHAR(10),
  card_cvv VARCHAR(10)
);

-- Bảng webhooks (lưu dữ liệu webhook từ payment gateway)
CREATE TABLE webhooks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  provider VARCHAR(50),
  payload TEXT,
  received_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng saved_cards (lưu thông tin thẻ cho auto-pay simulation)
CREATE TABLE saved_cards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  card_number VARCHAR(50),
  card_expiry VARCHAR(10),
  card_cvv VARCHAR(10),
  added_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Dữ liệu mẫu
INSERT INTO users (username, password, email, role, bio, avatar_path) VALUES 
('admin','admin123','admin@weblog.com','admin','I am the admin.', 'assets/images/default_ava.png'),
('tnqa','quocanh@123','tnqa@weblog.com','user','Cybersecurity student.', 'assets/images/default_ava.png'),
('huynhson','huynhson@123','huynhson@weblog.com','user','Software developer.', 'assets/images/default_ava.png'),
('premiumuser','premium@123','premium@weblog.com','premium','Premium User account', 'assets/images/default_ava.png');

INSERT INTO posts (user_id, title, content, premium, views) VALUES
(1, 'Welcome to WeBlog', 'This is our first post. Feel free to comment!', FALSE, 10),
(2, 'XSS Demo', '<script>alert("XSS")</script>', FALSE, 5),
(4, 'Premium Content: How to Hack', 'Only for premium members.', TRUE, 0);

INSERT INTO orders (user_id, amount, status, payment_method, card_number, card_expiry, card_cvv) VALUES
(4, 49.99, 'completed', 'Credit Card', '4111111111111111', '12/30', '123');

INSERT INTO subscriptions (user_id, plan, price, end_date, status) VALUES
(4, 'Premium 1 Year', 49.99, '2026-12-31', 'active');

INSERT INTO webhooks (provider, payload) VALUES
('Stripe', '{"event":"payment_succeeded","order_id":1,"user_id":4}');

INSERT INTO saved_cards (user_id, card_number, card_expiry, card_cvv) VALUES
(4, '4111111111111111', '12/30', '123');
