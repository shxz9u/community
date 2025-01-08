<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "사용자 정보를 불러오는 데 실패했습니다.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bio = $_POST['bio'];

    $sql = "UPDATE users SET bio = ? WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$bio, $user_id]);

    $message = "프로필이 업데이트되었습니다!";
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>프로필</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .profile-header {
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            border-radius: 0.5rem;
            text-align: center;
        }
        .card {
            margin-top: 20px;
            border-radius: 0.5rem;
        }
        .card-header {
            background-color: #f1f1f1;
            font-weight: bold;
        }
        .bio-textarea {
            resize: vertical;
            height: 150px;
        }
        .alert-success {
            text-align: center;
        }
        nav.navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        nav.navbar .container-fluid {
            padding-top: 0;
            padding-bottom: 0;
        }

        body {
            padding-top: 70px;
        }
    </style>
</head>
<body>

    <!-- 내브바 -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="board.php">커뮤니티</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php"><?= htmlspecialchars($_SESSION['username']) ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">로그아웃</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="create_post.php">글쓰기</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">로그인</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="sign_up.php">회원가입</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="profile-header">
            <h1>사용자 프로필</h1>
        </div>

        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">사용자 정보</div>
            <div class="card-body">
                <p><strong>사용자명:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>이메일:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Bio:</strong> <?= htmlspecialchars($user['bio']) ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">프로필 업데이트</div>
            <div class="card-body">
                <form method="POST" action="profile.php">
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio 수정</label>
                        <textarea class="form-control bio-textarea" id="bio" name="bio" required><?= htmlspecialchars($user['bio']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">프로필 업데이트</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
