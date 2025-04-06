<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\academy-grounds.css">
    <link rel="stylesheet" href="CSS\main.css">
</head>
<body>
    <?php 
    include 'header.php';
    include 'db.php'; 

    // Query to fetch academies
    $sql = "SELECT * FROM academys";
    $result = $conn->query($sql);
    ?>

     <!-- Hero Section -->
     <div class="hero">
        <div class="hero-shadow"></div>
        <h1 class="hero-text">Find. Book. Play</h1>
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
                    <button class="location-btn" data-location="panjim">Panjim</button>
                    <button class="location-btn" data-location="mapusa">Mapusa</button>
                    <button class="location-btn" data-location="margao">Margao</button>
                    <button class="location-btn" data-location="other">Other</button>
                </div>
            </div>
        </div>

        <!-- Academy Listings -->
        <div style="flex: 1;">
            <!-- Search Bar -->
            <div class="search-bar">
                <div class="search-bar-content">
                    <input type="text" placeholder="Enter name of the academy..." class="search-text">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>

            <!-- Academy Grid -->
            <div class="academy-grid">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // Get average rating from academy_reviews table
                        $academy_id = $row['ac_id'];
                        $rating_sql = "SELECT AVG(a_ratings) as avg_rating FROM academy_reviews WHERE ac_id = '$academy_id'";
                        $rating_result = $conn->query($rating_sql);
                        $rating_row = $rating_result->fetch_assoc();
                        $rating = number_format($rating_row['avg_rating'] ?? 0, 1);
                ?>
                <div class="academy">
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
                        <button class="enroll-button" onclick="window.location.href='bookacademy.php?ac_id=<?php echo $row['ac_id']; ?>'">₹<?php echo number_format($row['ac_charges']); ?>/month</button>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p>No academies found</p>";
                }
                $conn->close();
                ?>
            </div>

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

    <?php include 'footer.php'; ?>
    <script>
        // JavaScript for enhanced user experience
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            
            mobileMenuBtn.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.navbar') && !event.target.closest('.mobile-menu-btn')) {
                    navLinks.classList.remove('active');
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
        });
        
        document.addEventListener('DOMContentLoaded', function() {
        // Location filter functionality
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
                filterAcademies(location);
            });
        });
    
        function filterAcademies(location) {
            const academies = document.querySelectorAll('.academy');
            
            academies.forEach(academy => {
                const academyLocation = academy.querySelector('.turf-details:nth-child(2)').textContent.trim().toLowerCase();
                
                if (location === 'all') {
                    academy.style.display = 'block';
                } else {
                    // Match the data-location value with the actual location text
                    const locationMap = {
                        'panjim': 'panjim',
                        'margao': 'margao', 
                        'mapusa': 'mapusa'
                    };
                    
                    // Check if location matches or if it's "other" and doesn't match any defined location
                    if (locationMap[location] && academyLocation.includes(locationMap[location])) {
                        academy.style.display = 'block';
                    } else if (location === 'other' && 
                            !academyLocation.includes('panjim') && 
                            !academyLocation.includes('margao') && 
                            !academyLocation.includes('mapusa')) {
                        academy.style.display = 'block';
                    } else {
                        academy.style.display = 'none';
                    }
                }
            });
        }
    
        // Add search functionality
        const searchInput = document.querySelector('.search-text');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const academies = document.querySelectorAll('.academy');
            
            academies.forEach(academy => {
                const academyName = academy.querySelector('.turf-details:nth-child(1)').textContent.toLowerCase();
                const academyLocation = academy.querySelector('.turf-details:nth-child(2)').textContent.toLowerCase();
                
                if (academyName.includes(searchTerm) || academyLocation.includes(searchTerm)) {
                    academy.style.display = 'block';
                } else {
                    academy.style.display = 'none';
                }
            });
        });
    
        // Toggle filter sections
        const filterIcon = document.querySelector('.filter-icon');
        const locationToggle = document.querySelector('.location-toggle');
        
        filterIcon.addEventListener('click', function() {
            locationToggle.classList.toggle('collapsed');
            this.classList.toggle('fa-chevron-up');
            this.classList.toggle('fa-chevron-down');
        });
    });
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
    
    // Initial call to setup pagination
    applyFiltersAndPagination();
});
    </script>
</body>
</html>