<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Day - Admin Academy Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\academy-grounds.css">
    <link rel="stylesheet" href="CSS\academy-grounds-dashboard.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
    <?php 
    include 'header.php';
    include 'db.php';  // Include the database connection
    
    // Check if admin is logged in
    $is_admin = true; // Set to true for admin functionality
    
    if (!$is_admin) {
        // Redirect to login page if not admin
        header("Location: login.php");
        exit();
    }
    
    // Handle academy deletion if requested
    if (isset($_POST['delete_academy']) && isset($_POST['ac_id'])) {
        $ac_id = $_POST['ac_id'];
        $delete_sql = "DELETE FROM academys WHERE ac_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $ac_id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $delete_message = "Academy successfully deleted";
        } else {
            $delete_error = "Error deleting academy";
        }
        $stmt->close();
    }
    
    // Query to fetch academies
    $sql = "SELECT * FROM academys";
    $result = $conn->query($sql);
    ?>

     <!-- Hero Section -->
     <div class="hero">
        <div class="hero-shadow"></div>
        <h1 class="hero-text">Manage Academies <span class="admin-badge">ADMIN</span></h1>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Filters -->
        <div class="filter bento-item">
            <h2 class="filter-title">Filters</h2>
            
            <!-- Location Filter -->
            <div class="filter-type">
                <h3>
                    Location
                    <i class="fas fa-chevron-up filter-icon"></i>
                </h3>
                <div class="location">
                    <div class="location-option location-btn" data-location="all">
                        All
                    </div>
                    <div class="location-option location-btn" data-location="north-goa">
                        North Goa
                    </div>
                    <div class="location-option location-btn" data-location="south-goa">
                        South Goa
                    </div>
                </div>
            </div>
            
            <!-- Price Filter -->
            <div class="filter-type">
                <h3>
                    Price Range
                    <i class="fas fa-chevron-up filter-icon"></i>
                </h3>
                <div class="price-range">
                    <input type="range" min="1000" max="10000" value="5000" class="price-slider" id="priceRange">
                    <div class="price-display">
                        <span>₹1,000</span>
                        <span id="priceValue">₹5,000</span>
                        <span>₹10,000</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academy Listings -->
        <div style="flex: 1;">
            <!-- Admin Controls -->
            <div class="admin-controls">
                <button class="add-venue-btn" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New Academy
                </button>
                
                <!-- Search Bar -->
                <div class="search-bar">
                    <div class="search-bar-content">
                        <input type="text" placeholder="Search academies..." class="search-text">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
            </div>
            
            <?php if (isset($delete_message)): ?>
                <div style="background-color: #4caf50; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <?php echo $delete_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($delete_error)): ?>
                <div style="background-color: #f44336; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <?php echo $delete_error; ?>
                </div>
            <?php endif; ?>

            <!-- Academy Grid -->
            <div class="academy-grid">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // Get average rating from academy_reviews table
                        $ac_id = $row['ac_id'];
                        $rating_sql = "SELECT AVG(a_ratings) as avg_rating FROM academy_reviews WHERE ac_id = ?";
                        $rating_stmt = $conn->prepare($rating_sql);
                        $rating_stmt->bind_param("i", $ac_id);
                        $rating_stmt->execute();
                        $rating_result = $rating_stmt->get_result();
                        $rating_row = $rating_result->fetch_assoc();
                        $rating = number_format($rating_row['avg_rating'] ?? 4.5, 1); // Default to 4.5 if no ratings
                        $rating_stmt->close();
                ?>
                <div class="academy" data-price="<?php echo $row['ac_charges']; ?>" data-location="<?php echo strtolower($row['ac_location']); ?>">
                    <div class="academy-card">
                        <img src="<?php echo htmlspecialchars($row['admy_img']); ?>" alt="<?php echo htmlspecialchars($row['aca_nm']); ?>" style="width: 100%; height: 150px; object-fit: cover;">
                        <div class="top-icons">
                            <div class="rating">
                                <i class="fas fa-star star-icon"></i>
                                <span><?php echo $rating; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="academy-info">
                        <div class="turf-details"><?php echo htmlspecialchars($row['aca_nm']); ?></div>
                        <div class="turf-details"><?php echo htmlspecialchars($row['ac_location']); ?></div>
                        <div class="turf-details">₹<?php echo number_format($row['ac_charges']);?>/month</div>
                        <div class="admin-actions">
                            <button class="edit-btn" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this academy?');" style="flex: 1;">
                                <input type="hidden" name="ac_id" value="<?php echo $row['ac_id']; ?>">
                                <button type="submit" name="delete_academy" class="delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p>No academies found</p>";
                }
                ?>
            </div>

            <!-- Pagination -->
            <div class="pages">
                <button class="btn-no">Previous</button>
                <button class="btn-no active">1</button>
                <button class="btn-no">2</button>
                <button class="btn-no">3</button>
                <div class="ellipsis">...</div>
                <button class="btn-no">5</button>
                <button class="btn-no">Next</button>
            </div>
        </div>
    </div>
    
    <!-- Add Academy Modal -->
    <div id="addAcademyModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeAddModal()">&times;</span>
            <h2>Add New Academy</h2>
            <form action="process_academy.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="aca_nm">Academy Name</label>
                    <input type="text" id="aca_nm" name="aca_nm" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="ac_location">Location</label>
                    <input type="text" id="ac_location" name="ac_location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="ac_charges">Monthly Fee (₹)</label>
                    <input type="number" id="ac_charges" name="ac_charges" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="admy_img">Academy Image</label>
                    <input type="file" id="admy_img" name="admy_img" class="form-control" required>
                </div>
                <button type="submit" name="add_academy" class="submit-btn">Add Academy</button>
            </form>
        </div>
    </div>
    
    <!-- Edit Academy Modal -->
    <div id="editAcademyModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeEditModal()">&times;</span>
            <h2>Edit Academy</h2>
            <form action="process_academy.php" method="post" enctype="multipart/form-data">
                <input type="hidden" id="edit_ac_id" name="ac_id">
                <div class="form-group">
                    <label for="edit_aca_nm">Academy Name</label>
                    <input type="text" id="edit_aca_nm" name="aca_nm" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_ac_location">Location</label>
                    <input type="text" id="edit_ac_location" name="ac_location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_ac_charges">Monthly Fee (₹)</label>
                    <input type="number" id="edit_ac_charges" name="ac_charges" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_admy_img">Academy Image (leave empty to keep current image)</label>
                    <input type="file" id="edit_admy_img" name="admy_img" class="form-control">
                    <input type="hidden" id="current_img" name="current_img">
                </div>
                <div class="form-group">
                    <label>Current Image:</label>
                    <div>
                        <img id="academy_current_img" src="" alt="Current academy image" style="max-width: 200px; max-height: 100px; margin-top: 10px;">
                    </div>
                </div>
                <button type="submit" name="update_academy" class="submit-btn">Update Academy</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-col">
            <div class="footer-logo">GAME DAY</div>
            <p class="footer-text">The #1 platform for booking futsal grounds and finding the best playing experiences in your area.</p>
        </div>

        <div class="footer-col footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Find Grounds</a></li>
                <li><a href="#">Join Academy</a></li>
                <li><a href="#">Partner With Us</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">FAQs</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <div class="contact">
                <h3>Contact Us</h3>
                <div class="contact-info">
                    <p><i class="fas fa-envelope"></i> info@gameday.com</p>
                    <p><i class="fas fa-phone"></i> +1 234 567 8900</p>
                </div>
            </div>
            <div class="social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
        // JavaScript for enhanced user experience
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.navbar') && !event.target.closest('.mobile-menu-btn')) {
                    if (navLinks) {
                        navLinks.classList.remove('active');
                    }
                }
            });

            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    if(this.getAttribute('href') !== '#') {
                        e.preventDefault();
                        document.querySelector(this.getAttribute('href')).scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Location filter toggle
            const filterIcons = document.querySelectorAll('.filter-icon');
            filterIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const filterContent = this.parentElement.nextElementSibling;
                    if (filterContent.style.display === 'none') {
                        filterContent.style.display = 'flex';
                        this.classList.replace('fa-chevron-down', 'fa-chevron-up');
                    } else {
                        filterContent.style.display = 'none';
                        this.classList.replace('fa-chevron-up', 'fa-chevron-down');
                    }
                });
            });
            
            // Price slider functionality
            const priceSlider = document.getElementById('priceRange');
            const priceValue = document.getElementById('priceValue');
            
            if (priceSlider && priceValue) {
                priceSlider.addEventListener('input', function() {
                    const value = this.value;
                    priceValue.textContent = '₹' + numberWithCommas(value);
                    filterAcademiesByPrice(value);
                });
            }
            
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            
            function filterAcademiesByPrice(maxPrice) {
                const academies = document.querySelectorAll('.academy');
                
                academies.forEach(academy => {
                    const price = parseInt(academy.getAttribute('data-price'));
                    
                    if (price <= maxPrice) {
                        academy.classList.remove('price-filtered');
                    } else {
                        academy.classList.add('price-filtered');
                    }
                    
                    updateVisibility(academy);
                });
            }
            
            function updateVisibility(academy) {
                if (academy.classList.contains('location-filtered') || academy.classList.contains('price-filtered') || academy.classList.contains('search-filtered')) {
                    academy.style.display = 'none';
                } else {
                    academy.style.display = 'block';
                }
            }
        });
        
        // Modal Functions
        function openAddModal() {
            document.getElementById('addAcademyModal').style.display = 'block';
        }
        
        function closeAddModal() {
            document.getElementById('addAcademyModal').style.display = 'none';
        }
        
        function openEditModal(academyData) {
            const modal = document.getElementById('editAcademyModal');
            
            // Fill the form with academy data
            document.getElementById('edit_ac_id').value = academyData.ac_id;
            document.getElementById('edit_aca_nm').value = academyData.aca_nm;
            document.getElementById('edit_ac_location').value = academyData.ac_location;
            document.getElementById('edit_ac_charges').value = academyData.ac_charges;
            document.getElementById('current_img').value = academyData.admy_img;
            document.getElementById('academy_current_img').src = academyData.admy_img;
            
            modal.style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editAcademyModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addAcademyModal');
            const editModal = document.getElementById('editAcademyModal');
            
            if (event.target == addModal) {
                addModal.style.display = 'none';
            }
            
            if (event.target == editModal) {
                editModal.style.display = 'none';
            }
        }
        
        // Search functionality
        const searchInput = document.querySelector('.search-text');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const academies = document.querySelectorAll('.academy');
                
                academies.forEach(academy => {
                    const academyName = academy.querySelector('.turf-details').textContent.toLowerCase();
                    const academyLocation = academy.querySelectorAll('.turf-details')[1].textContent.toLowerCase();
                    
                    if (academyName.includes(searchTerm) || academyLocation.includes(searchTerm)) {
                        academy.classList.remove('search-filtered');
                    } else {
                        academy.classList.add('search-filtered');
                    }
                    
                    updateVisibility(academy);
                });
            });
        }

        // Location filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const locationButtons = document.querySelectorAll('.location-btn');
            
            // Set "All" as default active
            document.querySelector('.location-btn[data-location="all"]').classList.add('active');
            
            locationButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    locationButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Get selected location
                    const location = this.getAttribute('data-location');
                    
                    // Filter academies based on location
                    filterAcademiesByLocation(location);
                });
            });
            
            function filterAcademiesByLocation(location) {
                const academies = document.querySelectorAll('.academy');
                
                academies.forEach(academy => {
                    const academyLocation = academy.getAttribute('data-location').toLowerCase();
                    
                    if (location === 'all') {
                        academy.classList.remove('location-filtered');
                    } else if (location === 'north-goa' && academyLocation.includes('north goa')) {
                        academy.classList.remove('location-filtered');
                    } else if (location === 'south-goa' && academyLocation.includes('south goa')) {
                        academy.classList.remove('location-filtered');
                    } else {
                        academy.classList.add('location-filtered');
                    }
                    
                    updateVisibility(academy);
                });
            }
            
            // Function to update visibility based on all filters
            function updateVisibility(academy) {
                if (academy.classList.contains('location-filtered') || academy.classList.contains('price-filtered') || academy.classList.contains('search-filtered')) {
                    academy.style.display = 'none';
                } else {
                    academy.style.display = 'block';
                }
            }
            
            // Pagination functionality
            const pageButtons = document.querySelectorAll('.btn-no');
            const academiesPerPage = 6; // Number of academies to show per page
            
            pageButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (this.textContent === 'Previous' || this.textContent === 'Next') {
                        // Handle previous and next buttons
                        const currentPage = document.querySelector('.btn-no.active');
                        if (this.textContent === 'Previous' && currentPage.previousElementSibling && 
                            currentPage.previousElementSibling.classList.contains('btn-no') && 
                            !currentPage.previousElementSibling.classList.contains('ellipsis')) {
                            currentPage.previousElementSibling.click();
                        } else if (this.textContent === 'Next' && currentPage.nextElementSibling && 
                                   currentPage.nextElementSibling.classList.contains('btn-no') && 
                                   !currentPage.nextElementSibling.classList.contains('ellipsis')) {
                            currentPage.nextElementSibling.click();
                        }
                    } else {
                        // Handle number buttons
                        pageButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        
                        const page = parseInt(this.textContent);
                        showAcademiesForPage(page);
                    }
                });
            });
            
            function showAcademiesForPage(page) {
                const academies = document.querySelectorAll('.academy:not(.price-filtered):not(.location-filtered):not(.search-filtered)');
                const startIndex = (page - 1) * academiesPerPage;
                const endIndex = startIndex + academiesPerPage;
                
                academies.forEach((academy, index) => {
                    if (index >= startIndex && index < endIndex) {
                        academy.style.display = 'block';
                    } else {
                        academy.style.display = 'none';
                    }
                });
            }
            
            // Initialize by showing first page
            showAcademiesForPage(1);
        });
    </script>
</body>
</html>