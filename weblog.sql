CREATE DATABASE IF NOT EXISTS weblog;
USE weblog;

-- Bảng roles (quản lý vai trò người dùng)
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) UNIQUE NOT NULL
);

-- Bảng users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE,
  password VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  role_id INT,
  bio TEXT,
  avatar_path VARCHAR(255), 
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
);


-- Bảng status_orders (trạng thái đơn hàng)
CREATE TABLE status_orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  status VARCHAR(50) UNIQUE NOT NULL
);

-- Bảng status_descriptions (mô tả trạng thái)
CREATE TABLE status_descriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  status VARCHAR(50) UNIQUE NOT NULL
);

-- Bảng plans (chứa các gói subscription)
CREATE TABLE plans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) UNIQUE,
  price DECIMAL(10,2),
  duration INT, -- Số ngày gói đăng ký có hiệu lực
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng subscriptions (liên kết user với plan)
CREATE TABLE subscriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  plan_id INT,
  start_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  end_date DATETIME,
  status_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE CASCADE,
  FOREIGN KEY (status_id) REFERENCES status_descriptions(id) ON DELETE SET NULL
);

-- Bảng posts
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  title VARCHAR(200),
  content TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  views INT DEFAULT 0,
  premium BOOLEAN DEFAULT FALSE,
  thumbnail_path text NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng comments
CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  user_id INT,
  comment TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng logs
CREATE TABLE logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  action TEXT,
  ip_address VARCHAR(50),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng messages (hộp thư nội bộ)
CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT,
  receiver_id INT,
  subject VARCHAR(200),
  message TEXT,
  sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  is_read BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng orders (ghi nhận đơn hàng thanh toán)
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  plan_id INT,
  status_id INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  payment_method VARCHAR(50),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE CASCADE,
  FOREIGN KEY (status_id) REFERENCES status_orders(id) ON DELETE SET NULL
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
  added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


INSERT INTO roles (name) VALUES
('admin'),
('premium'),
('user');


INSERT INTO status_orders (id, status) VALUES
(1, 'completed'),
(2, 'pending'),
(3, 'cancelled');


INSERT INTO status_descriptions (status) VALUES
('active'),
('expired');

-- Thêm dữ liệu vào bảng plans
INSERT INTO plans (name, price, duration) VALUES
('Basic', 9.99, 30),
('Standard', 19.99, 90),
('Premium', 29.99, 180);

-- Thêm dữ liệu vào bảng users
INSERT INTO users (username, password, email, role_id, bio, avatar_path) VALUES
('admin', 'admin@123', 'admin@weblog.com', 1, 'Administrator of the website', 'assets/images/default_ava.png'),
('tnqa', 'tnqa@123', 'quocanh@weblog.com', 2, 'I love premium content', 'assets/images/default_ava.png'),
('huynhson', 'huynhson@123', 'predator@weblog.com', 3, 'Just a regular user', 'assets/images/default_ava.png'),
('john_doe', 'password123', 'john@weblog.com', 3, 'Tech enthusiast', 'assets/images/default_ava.png'),
('alice_wonder', 'alicepass', 'alice@weblog.com', 2, 'Premium subscriber', 'assets/images/default_ava.png'),
('bob_builder', 'bobpass', 'bob@weblog.com', 3, 'Love to blog', 'assets/images/default_ava.png');

-- Thêm nhiều bài viết
INSERT INTO posts (user_id, title, content, views, premium, thumbnail_path) VALUES
(1, 'Welcome to Weblog', 'This is the first post.', 100, FALSE, 'auth/uploads/admin/thumbnails/1.png'),
(2, 'Premium Content', 'Only for premium users.', 50, TRUE, 'auth/uploads/tnqa/thumbnails/2.png'),
(3, 'Tech Trends 2025', 'Upcoming technologies...', 200, FALSE, 'auth/uploads/huynhson/thumbnails/3.png');

-- Thêm nhiều bình luận
INSERT INTO comments (post_id, user_id, comment) VALUES
(1, 2, 'Great post!'),
(1, 3, 'Very informative!'),
(2, 1, 'I love this premium content!');

-- Thêm nhiều đơn hàng
INSERT INTO orders (user_id, plan_id, status_id, payment_method) VALUES
(1, 3, 1, 'Credit Card'),
(2, 2, 2, 'PayPal'),
(3, 1, 3, 'Bank Transfer');

-- Thêm nhiều subscriptions
INSERT INTO subscriptions (user_id, plan_id, end_date, status_id) VALUES
(1, 3, DATE_ADD(NOW(), INTERVAL 180 DAY), 1),
(2, 2, DATE_ADD(NOW(), INTERVAL 90 DAY), 1),
(3, 1, DATE_ADD(NOW(), INTERVAL 30 DAY), 2);

-- Thêm nhiều logs
INSERT INTO logs (user_id, action, ip_address) VALUES
(1, 'Logged in', '192.168.1.1'),
(2, 'Updated profile', '192.168.1.2'),
(3, 'Posted a comment', '192.168.1.3');

-- Thêm nhiều tin nhắn
INSERT INTO messages (sender_id, receiver_id, subject, message) VALUES
(1, 2, 'Welcome!', 'Glad to have you here.'),
(2, 3, 'Subscription Info', 'Your subscription is active.'),
(3, 1, 'Tech Talk', 'Let’s discuss tech trends.');



