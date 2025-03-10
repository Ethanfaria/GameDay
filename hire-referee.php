<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME DAY - Hire a Referee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\hire-referee.css">
    <link rel="stylesheet" href="CSS\main.css">
    <style>
        /* Hero Section */
        .hero {
            height: 300px;
            margin: 0 0 60px 0;
            border-radius: 30px;
            position: relative;
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 30px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 0 20px;
            max-width: 800px;
            margin: 0 auto;
            top: 50%;
            transform: translateY(-50%);
        }

        .hero h1 {
            font-size: 3.5em;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.2em;
            max-width: 600px;
            margin: 0 auto;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }

        /* Main Content */
        .content-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        /* Search Section */
        .search-section {
            background: rgba(0, 77, 61, 0.4);
            padding: 25px;
            border-radius: 30px;
            margin: -60px auto 60px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 3;
            max-width: 800px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .search-container {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 12px 25px;
            width: 100%;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .search-container i {
            color: var(--neon-green);
            font-size: 1.2em;
            margin-right: 15px;
        }

        .search-container input {
            background: transparent;
            border: none;
            color: white;
            width: 100%;
            font-size: 1.1em;
            outline: none;
        }

        .search-container input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Referee Grid Layout */
        .referees-wrapper {
            margin-top: 40px;
            margin-bottom: 60px;
        }

        .main-content {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 40px;
            width: 100%;
        }

        .referee-card {
            background-color: rgba(0, 77, 61, 0.8);
            border-radius: 30px;
            overflow: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .referee-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-color: rgba(185, 255, 0, 0.3);
        }

        .referee-image {
            position: relative;
            height: 220px;
        }

        .referee-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .referee-rating {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 8px 15px;
            border-radius: 30px;
            backdrop-filter: blur(5px);
        }

        .referee-rating i {
            color: var(--neon-green);
        }

        .referee-info {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .referee-name {
            font-size: 1.4em;
            font-weight: bold;
        }

        .referee-experience {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95em;
        }

        .referee-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.8);
        }

        .referee-meta > div {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .referee-meta i {
            color: var(--neon-green);
            font-size: 0.9em;
        }

        .referee-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin: 5px 0;
        }

        .badge {
            background-color: rgba(185, 255, 0, 0.1);
            color: var(--neon-green);
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 0.9em;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(185, 255, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .badge i {
            font-size: 0.8em;
        }

        .price {
            font-size: 1.2em;
            color: var(--neon-green);
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 5px 0;
        }

        .hire-button {
            width: 100%;
            background-color: var(--neon-green);
            color: var(--dark-green);
            border: none;
            padding: 12px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .hire-button:hover {
            opacity: 0.9;
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(185, 255, 0, 0.2);
        }

        @media (max-width: 1200px) {
            .referees-wrapper {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 0 20px;
            }
            .hero {
                height: 250px;
            }
            .hero h1 {
                font-size: 2.5em;
            }
            .search-section {
                margin-top: -40px;
                padding: 20px;
            }
            .main-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            .referee-image {
                height: 200px;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Professional Referees</h1>
            <p>Find and hire experienced referees for your matches and tournaments</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <!-- Search Section -->
        <div class="search-section">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search referees by name, certification, or expertise...">
            </div>
        </div>

        <!-- Referees Grid -->
        <div class="referees-wrapper">
            <!-- Main Content Grid -->
            <div class="main-content">
                <!-- Regular Referee Cards -->
                <div class="referee-card regular">
                    <div class="referee-image">
                        <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018" alt="Referee">
                        <div class="referee-rating">
                            <i class="fas fa-star"></i>
                            <span>4.7</span>
                        </div>
                    </div>
                    <div class="referee-info">
                        <div class="referee-name">Mike Wilson</div>
                        <div class="referee-experience">6 years of experience</div>
                        <div class="referee-badges">
                            <span class="badge">State Level</span>
                            <span class="badge">Tournament Expert</span>
                        </div>
                        <div class="price">₹1800 per match</div>
                        <button class="hire-button" onclick="window.location.href='referee-booking.html?name=Mike Wilson&experience=6&price=1800&rating=4.7'">HIRE NOW</button>
                    </div>
                </div>

                <div class="referee-card regular">
                    <div class="referee-image">
                        <img src="https://images.unsplash.com/photo-1560012754-635c8d4d9c52" alt="Referee">
                        <div class="referee-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                    </div>
                    <div class="referee-info">
                        <div class="referee-name">Emma Davis</div>
                        <div class="referee-experience">7 years of experience</div>
                        <div class="referee-badges">
                            <span class="badge">FIFA Licensed</span>
                            <span class="badge">Women's Football</span>
                        </div>
                        <div class="price">₹2200 per match</div>
                        <button class="hire-button" onclick="window.location.href='referee-booking.html?name=Emma Davis&experience=7&price=2200&rating=4.9'">HIRE NOW</button>
                    </div>
                </div>

                <div class="referee-card regular">
                    <div class="referee-image">
                        <img src="https://images.unsplash.com/photo-1551958219-acbc608c6377" alt="Referee">
                        <div class="referee-rating">
                            <i class="fas fa-star"></i>
                            <span>4.6</span>
                        </div>
                    </div>
                    <div class="referee-info">
                        <div class="referee-name">Carlos Rodriguez</div>
                        <div class="referee-experience">4 years of experience</div>
                        <div class="referee-badges">
                            <span class="badge">National Level</span>
                            <span class="badge">Futsal Expert</span>
                        </div>
                        <div class="price">₹1600 per match</div>
                        <button class="hire-button" onclick="window.location.href='referee-booking.html?name=Carlos Rodriguez&experience=4&price=1600&rating=4.6'">HIRE NOW</button>
                    </div>
                </div>

                <div class="referee-card regular">
                    <div class="referee-image">
                        <img src="https://images.unsplash.com/photo-1547941126-3d5322b218b0" alt="Referee">
                        <div class="referee-rating">
                            <i class="fas fa-star"></i>
                            <span>4.8</span>
                        </div>
                    </div>
                    <div class="referee-info">
                        <div class="referee-name">David Thompson</div>
                        <div class="referee-experience">5 years of experience</div>
                        <div class="referee-badges">
                            <span class="badge">State Level</span>
                            <span class="badge">Youth Games</span>
                        </div>
                        <div class="price">₹1400 per match</div>
                        <button class="hire-button" onclick="window.location.href='referee-booking.html?name=David Thompson&experience=5&price=1400&rating=4.8'">HIRE NOW</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced search functionality
        const searchInput = document.querySelector('.search-container input');
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const refereeCards = document.querySelectorAll('.referee-card');
            
            refereeCards.forEach(card => {
                const refereeName = card.querySelector('.referee-name').textContent.toLowerCase();
                const badges = Array.from(card.querySelectorAll('.badge')).map(badge => badge.textContent.toLowerCase());
                const experience = card.querySelector('.referee-experience').textContent.toLowerCase();
                
                const matches = refereeName.includes(searchTerm) || 
                              badges.some(badge => badge.includes(searchTerm)) ||
                              experience.includes(searchTerm);
                
                card.style.display = matches ? 'flex' : 'none';
            });
        });
    </script>
</body>
</html> 