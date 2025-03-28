<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Day - Admin Ground Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\academy-grounds.css">
    
    <link rel="stylesheet" href="CSS\main.css">
    <style>
        .admin-controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .add-venue-btn {
            background-color: var(--neon-green);
            color: var(--dark-green);
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .admin-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .edit-btn, .delete-btn {
            flex: 1;
            padding: 8px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-weight: bold;
        }
        
        .edit-btn {
            background-color: #3498db;
            color: white;
        }
        
        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
        }
        
        .modal-content {
            background-color: var(--dark-green);
            border: 1px solid var(--neon-green);
            border-radius: 20px;
            width: 60%;
            max-width: 700px;
            margin: 5% auto;
            padding: 20px;
            position: relative;
            color: white;
        }
        
        .close-btn {
            color: var(--neon-green);
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .submit-btn {
            background-color: var(--neon-green);
            color: var(--dark-green);
            border: none;
            padding: 12px 25px;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }
        
        .admin-badge {
            background-color: var(--neon-green);
            color: var(--dark-green);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin-left: 15px;
        }
    </style>
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
    
    // Handle venue deletion if requested
    if (isset($_POST['delete_venue']) && isset($_POST['venue_id'])) {
        $venue_id = $_POST['venue_id'];
        $delete_sql = "DELETE FROM venue WHERE venue_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("s", $venue_id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $delete_message = "Venue successfully deleted";
        } else {
            $delete_error = "Error deleting venue";
        }
        $stmt->close();
    }
    
    // Query to fetch venues
    $sql = "SELECT * FROM venue";
    $result = $conn->query($sql);
    ?>

     <!-- Hero Section -->
     <div class="hero">
        <div class="hero-shadow"></div>
        <h1 class="hero-text">Manage Grounds <span class="admin-badge">ADMIN</span></h1>
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
                <div class="location-toggle">
                    <button class="location-btn" data-location="all">All</button>
                    <button class="location-btn" data-location="north-goa">Panjim</button>
                    <button class="location-btn" data-location="south-goa">Mapusa</button>
                    <button class="location-btn" data-location="south-goa">Margao</button>
                    <button class="location-btn" data-location="south-goa">Other</button>
                </div>
            </div>
        </div>

        <!-- Academy Listings -->
        <div style="flex: 1;">
            <!-- Admin Controls -->
            <div class="admin-controls">
                <button class="add-venue-btn" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New Ground
                </button>
                
                <!-- Search Bar -->
                <div class="search-bar">
                    <div class="search-bar-content">
                        <input type="text" placeholder="Search grounds..." class="search-text">
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
                        // Get average rating from venue_reviews table
                        $venue_id = $row['venue_id'];
                        $rating_sql = "SELECT AVG(v_ratings) as avg_rating FROM venue_reviews WHERE venue_id = '$venue_id'";
                        $rating_result = $conn->query($rating_sql);
                        $rating_row = $rating_result->fetch_assoc();
                        $rating = number_format($rating_row['avg_rating'] ?? 4.5, 1); // Default to 4.5 if no ratings
                ?>
                <div class="academy">
                    <div class="academy-card">
                        <img src="<?php echo htmlspecialchars($row['turf_img']); ?>" alt="<?php echo htmlspecialchars($row['venue_nm']); ?>" style="width: 100%; height: 150px; object-fit: cover;">
                        <div class="top-icons">
                            <div class="rating">
                                <i class="fas fa-star star-icon"></i>
                                <span><?php echo $rating; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="academy-info">
                        <div class="turf-details"><?php echo htmlspecialchars($row['venue_nm']); ?></div>
                        <div class="turf-details"><?php echo htmlspecialchars($row['location']); ?></div>
                        <div class="turf-details">₹<?php echo number_format($row['price']);?>/hr</div>
                        <div class="admin-actions">
                            <button class="edit-btn" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this venue?');" style="flex: 1;">
                                <input type="hidden" name="venue_id" value="<?php echo $row['venue_id']; ?>">
                                <button type="submit" name="delete_venue" class="delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p>No venues found</p>";
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
    
    <!-- Add Venue Modal -->
    <div id="addVenueModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeAddModal()">&times;</span>
            <h2>Add New Ground</h2>
            <form action="process_venue.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="venue_nm">Venue Name</label>
                    <input type="text" id="venue_nm" name="venue_nm" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="price">Price (₹/hr)</label>
                    <input type="number" id="price" name="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="turf_img">Venue Image</label>
                    <input type="file" id="turf_img" name="turf_img" class="form-control" required>
                </div>
                <button type="submit" name="add_venue" class="submit-btn">Add Ground</button>
            </form>
        </div>
    </div>
    
    <!-- Edit Venue Modal -->
    <div id="editVenueModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeEditModal()">&times;</span>
            <h2>Edit Ground</h2>
            <form action="process_venue.php" method="post" enctype="multipart/form-data">
                <input type="hidden" id="edit_venue_id" name="venue_id">
                <div class="form-group">
                    <label for="edit_venue_nm">Venue Name</label>
                    <input type="text" id="edit_venue_nm" name="venue_nm" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_location">Location</label>
                    <input type="text" id="edit_location" name="location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_price">Price (₹/hr)</label>
                    <input type="number" id="edit_price" name="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_turf_img">Venue Image (leave empty to keep current image)</label>
                    <input type="file" id="edit_turf_img" name="turf_img" class="form-control">
                    <input type="hidden" id="current_img" name="current_img">
                </div>
                <div class="form-group">
                    <label>Current Image:</label>
                    <div>
                        <img id="venue_current_img" src="" alt="Current venue image" style="max-width: 200px; max-height: 100px; margin-top: 10px;">
                    </div>
                </div>
                <button type="submit" name="update_venue" class="submit-btn">Update Ground</button>
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
        });
        
        // Modal Functions
        function openAddModal() {
            document.getElementById('addVenueModal').style.display = 'block';
        }
        
        function closeAddModal() {
            document.getElementById('addVenueModal').style.display = 'none';
        }
        
        function openEditModal(venueData) {
            const modal = document.getElementById('editVenueModal');
            
            // Fill the form with venue data
            document.getElementById('edit_venue_id').value = venueData.venue_id;
            document.getElementById('edit_venue_nm').value = venueData.venue_nm;
            document.getElementById('edit_location').value = venueData.location;
            document.getElementById('edit_price').value = venueData.price;
            document.getElementById('current_img').value = venueData.turf_img;
            document.getElementById('venue_current_img').src = venueData.turf_img;
            
            modal.style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editVenueModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addVenueModal');
            const editModal = document.getElementById('editVenueModal');
            
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
                const venues = document.querySelectorAll('.academy');
                
                venues.forEach(venue => {
                    const venueName = venue.querySelector('.turf-details').textContent.toLowerCase();
                    const venueLocation = venue.querySelectorAll('.turf-details')[1].textContent.toLowerCase();
                    
                    if (venueName.includes(searchTerm) || venueLocation.includes(searchTerm)) {
                        venue.style.display = 'block';
                    } else {
                        venue.style.display = 'none';
                    }
                });
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Pagination functionality
            const itemsPerPage = 15;
            const academyItems = document.querySelectorAll('.academy');
            const totalItems = academyItems.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            let currentPage = 1;
            
            // Get pagination elements
            const prevButton = document.querySelector('.pages .btn-no:first-child');
            const nextButton = document.querySelector('.pages .btn-no:last-child');
            const pageButtons = Array.from(document.querySelectorAll('.pages .btn-no:not(:first-child):not(:last-child)'));
            
            // Initialize pagination
            if (totalPages <= 5) {
                // If 5 or fewer pages, show all page numbers
                for (let i = 0; i < pageButtons.length; i++) {
                    if (i < totalPages) {
                        pageButtons[i].textContent = i + 1;
                        pageButtons[i].style.display = 'inline-block';
                    } else {
                        pageButtons[i].style.display = 'none';
                    }
                }
                document.querySelector('.ellipsis').style.display = 'none';
            } else {
                // More than 5 pages, show first 3, ellipsis, and last page
                pageButtons[0].textContent = '1';
                pageButtons[1].textContent = '2';
                pageButtons[2].textContent = '3';
                document.querySelector('.ellipsis').style.display = 'inline-block';
                pageButtons[3].textContent = totalPages;
            }
            
            // Update page display
            function updatePageDisplay() {
                // Hide all items
                academyItems.forEach(item => {
                    item.style.display = 'none';
                });
                
                // Show items for current page
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
                
                for (let i = startIndex; i < endIndex; i++) {
                    if (academyItems[i]) {
                        academyItems[i].style.display = 'block';
                    }
                }
                
                // Update pagination buttons
                pageButtons.forEach(button => {
                    button.classList.remove('active');
                    if (parseInt(button.textContent) === currentPage) {
                        button.classList.add('active');
                    }
                });
                
                // Update prev/next buttons
                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === totalPages;
                
                // Update pagination display
                if (totalPages > 5) {
                    if (currentPage <= 3) {
                        // Near start, show first 3, ellipsis, and last page
                        pageButtons[0].textContent = '1';
                        pageButtons[1].textContent = '2';
                        pageButtons[2].textContent = '3';
                        document.querySelector('.ellipsis').style.display = 'inline-block';
                        pageButtons[3].textContent = totalPages;
                    } else if (currentPage >= totalPages - 2) {
                        // Near end, show first page, ellipsis, and last 3
                        pageButtons[0].textContent = '1';
                        document.querySelector('.ellipsis').style.display = 'inline-block';
                        pageButtons[1].textContent = totalPages - 2;
                        pageButtons[2].textContent = totalPages - 1;
                        pageButtons[3].textContent = totalPages;
                    } else {
                        // Middle, show first page, ellipsis, current and neighbors, ellipsis, last page
                        pageButtons[0].textContent = '1';
                        document.querySelector('.ellipsis').style.display = 'inline-block';
                        pageButtons[1].textContent = currentPage - 1;
                        pageButtons[2].textContent = currentPage;
                        pageButtons[3].textContent = currentPage + 1;
                        pageButtons[4].textContent = totalPages;
                    }
                }
            }
            
            // Initial page display
            updatePageDisplay();
            
            // Add click event listeners to page buttons
            pageButtons.forEach(button => {
                button.addEventListener('click', function() {
                    currentPage = parseInt(this.textContent);
                    updatePageDisplay();
                });
            });
            
            // Add click event listeners to prev/next buttons
            prevButton.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    updatePageDisplay();
                }
            });
            
            nextButton.addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePageDisplay();
                }
            });
            
            // Integrate with existing filtering functionality
            function applyFiltersAndPagination() {
                // First apply filters
                const locationButtons = document.querySelectorAll('.location-btn');
                const activeLocationBtn = Array.from(locationButtons).find(btn => btn.classList.contains('active'));
                const location = activeLocationBtn ? activeLocationBtn.getAttribute('data-location') : 'all';
                
                // Reset visibility of all items before filtering
                academyItems.forEach(item => {
                    item.style.display = 'block';
                });
                
                // Apply location filter
                if (location !== 'all') {
                    academyItems.forEach(item => {
                        const itemLocation = item.querySelector('.turf-details:nth-child(2)').textContent.trim().toLowerCase();
                        const locationMap = {
                            'panjim': 'panjim',
                            'margao': 'margao',
                            'mapusa': 'mapusa'
                        };
                        
                        let shouldDisplay = false;
                        if (locationMap[location] && itemLocation.includes(locationMap[location])) {
                            shouldDisplay = true;
                        } else if (location === 'other' && 
                                  !itemLocation.includes('panjim') && 
                                  !itemLocation.includes('margao') && 
                                  !itemLocation.includes('mapusa')) {
                            shouldDisplay = true;
                        }
                        
                        if (!shouldDisplay) {
                            item.style.display = 'none';
                        }
                    });
                }
                
                // Apply search filter if search input exists
                const searchInput = document.querySelector('.search-text');
                if (searchInput && searchInput.value.trim() !== '') {
                    const searchTerm = searchInput.value.toLowerCase();
                    academyItems.forEach(item => {
                        if (item.style.display !== 'none') {
                            const itemName = item.querySelector('.turf-details:nth-child(1)').textContent.toLowerCase();
                            const itemLocation = item.querySelector('.turf-details:nth-child(2)').textContent.toLowerCase();
                            
                            if (!itemName.includes(searchTerm) && !itemLocation.includes(searchTerm)) {
                                item.style.display = 'none';
                            }
                        }
                    });
                }
                
                // Count visible items after filtering
                const visibleItems = Array.from(academyItems).filter(item => item.style.display !== 'none');
                const filteredTotalPages = Math.ceil(visibleItems.length / itemsPerPage);
                
                // Update pagination based on filtered results
                if (currentPage > filteredTotalPages && filteredTotalPages > 0) {
                    currentPage = 1;
                }
                
                // Apply pagination
                const startIndex = (currentPage - 1) * itemsPerPage;
                visibleItems.forEach((item, index) => {
                    if (index >= startIndex && index < startIndex + itemsPerPage) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Update pagination display
                updatePaginationDisplay(filteredTotalPages);
            }
            
            function updatePaginationDisplay(totalPages) {
                // Hide pagination if there's only one page
                if (totalPages <= 1) {
                    document.querySelector('.pages').style.display = 'none';
                    return;
                } else {
                    document.querySelector('.pages').style.display = 'flex';
                }
                
                // Update page buttons
                if (totalPages <= 5) {
                    // If 5 or fewer pages, show all page numbers
                    for (let i = 0; i < pageButtons.length; i++) {
                        if (i < totalPages) {
                            pageButtons[i].textContent = i + 1;
                            pageButtons[i].style.display = 'inline-block';
                        } else {
                            pageButtons[i].style.display = 'none';
                        }
                    }
                    document.querySelector('.ellipsis').style.display = 'none';
                } else {
                    // More than 5 pages, show first 3, ellipsis, and last page
                    pageButtons[0].textContent = '1';
                    pageButtons[1].textContent = '2';
                    pageButtons[2].textContent = '3';
                    document.querySelector('.ellipsis').style.display = 'inline-block';
                    pageButtons[3].textContent = totalPages;
                    pageButtons[4].style.display = 'none';
                }
                
                // Update active button
                pageButtons.forEach(button => {
                    button.classList.remove('active');
                    if (parseInt(button.textContent) === currentPage) {
                        button.classList.add('active');
                    }
                });
                
                // Update prev/next buttons
                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === totalPages;
            }
            
            // Connect filtering and pagination
            const locationButtons = document.querySelectorAll('.location-btn');
            locationButtons.forEach(button => {
                button.addEventListener('click', function() {
                    locationButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    currentPage = 1; // Reset to first page when filter changes
                    applyFiltersAndPagination();
                });
            });
            
            const searchInput = document.querySelector('.search-text');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    currentPage = 1; // Reset to first page when search changes
                    applyFiltersAndPagination();
                });
            }
            
            // Add modal and other previous JavaScript functions here
            // (previous modal functions, location filter toggle, etc.)
            
            // Initial call to setup pagination
            applyFiltersAndPagination();
        });
    </script>
</body>
</html>