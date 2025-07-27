<?php
session_start();
include("db.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$venues = $conn->query("SELECT * FROM venues ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Venues</title>
    <link rel="stylesheet" href="style/style.css" />
    <style>
        body {
            background: linear-gradient(to right, #dfc5c5, #d5f2ff);
            font-family: 'Segoe UI', sans-serif;
            color: #333;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #006EA8;
            margin-bottom: 40px;
        }
        .venue-container {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .venue-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .venue-card:hover {
            transform: scale(1.03);
        }
        .venue-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        .venue-details {
            padding: 15px 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .venue-name {
            font-size: 1.5rem;
            color: #003a63;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .venue-location,
        .venue-capacity {
            font-size: 1rem;
            margin-bottom: 6px;
            color: #555;
        }
        .venue-description {
            margin-top: auto;
            font-size: 0.95rem;
            color: #666;
            line-height: 1.3;
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<h1>Our Venues</h1>

<div class="venue-container">
    <?php if ($venues && $venues->num_rows > 0): ?>
        <?php while ($venue = $venues->fetch_assoc()): ?>
            <div class="venue-card">
                <?php if (!empty($venue['image']) && file_exists($venue['image'])): ?>
                    <img src="<?= htmlspecialchars($venue['image']) ?>" alt="<?= htmlspecialchars($venue['name']) ?>" class="venue-image" />
                <?php else: ?>
                    <img src="images/placeholder.png" alt="No Image" class="venue-image" />
                <?php endif; ?>
                <div class="venue-details">
                    <div class="venue-name"><?= htmlspecialchars($venue['name']) ?></div>
                    <div class="venue-location"><strong>Location:</strong> <?= htmlspecialchars($venue['location']) ?></div>
                    <div class="venue-capacity"><strong>Capacity:</strong> <?= intval($venue['capacity']) ?> people</div>
                    <div class="venue-description"><?= nl2br(htmlspecialchars($venue['description'])) ?></div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No venues available at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>
