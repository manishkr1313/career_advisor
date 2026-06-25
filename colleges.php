<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

requireLogin();

$colleges = [];

$result = $conn->query("SELECT * FROM colleges ORDER BY created_at DESC");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $colleges[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colleges - Career Advisor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/colleges.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>
    <!-- Header Section -->
    <div class="course-header mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold mb-2 text-info">
                Explore Top Colleges
            </h1>
            <p style=" font-size: 1.1rem; color: rgba(255,255,255,0.9); margin-bottom: 0;">
                Discover government colleges with excellent courses and facilities
            </p>
        </div>
        </class=>

    </div>

    <div class="container pb-5">
        <!-- Search & Filter Section -->
        <div class="row g-3 mb-5">
            <div class="col-md-6">
                <div style="position: relative;">
                    <i class="fas fa-search"
                        style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                    <input type="text" class="form-control dark-input" id="searchInput"
                        placeholder="Search colleges by name..."
                        style="padding-left: 40px; border: 2px solid var(--border-color);">
                </div>
            </div>

            <div class="col-md-6">
                <div style="position: relative;">
                    <i class="fas fa-map-marker-alt"
                        style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                    <select class="form-select dark-input" id="locationFilter"
                        style="padding-left: 40px; border: 2px solid var(--border-color);">
                        <option value="">All Locations</option>
                        <!-- <option value="Delhi">Delhi</option>
                        <option value="Mumbai">Mumbai</option>
                        <option value="Bangalore">Bangalore</option>
                        <option value="Chennai">Chennai</option> -->
                        <option value="bihar">Bihar</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Colleges Grid -->
        <div class="row g-4" id="collegesContainer">

            <?php foreach ($colleges as $college): ?>

            <div class="col-md-6 college-card " data-location="<?php echo htmlspecialchars($college['location']); ?>"
                data-name="<?php echo strtolower(htmlspecialchars($college['college_name'])); ?>">

                <div class="card h-100">

                    <!-- College Image -->
                    <div class="card-img-container">
                        <img src="<?php echo !empty($college['image']) ? htmlspecialchars($college['image']) : 'img/college1.jpg'; ?>"
                            class="college-img" alt="<?php echo htmlspecialchars($college['college_name']); ?>">
                    </div>

                    <!-- College Details -->
                    <div class="card-body">

                        <h5 class="card-title text-white fw-bold">
                            <?php echo htmlspecialchars($college['college_name']); ?>
                        </h5>

                        <p style="color: var(--text-secondary);">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                            <?php echo htmlspecialchars($college['location']); ?>
                        </p>

                        <!-- <div class="mb-3">
                            <p class="fw-bold">
                                <i class="fas fa-book text-success"></i> Courses <br>
                                <?php echo htmlspecialchars($college['courses_available']); ?>
                            </p>
                        </div> -->

                        <!-- <div class="mb-3">
                            <p class="fw-bold">
                                <i class="fas fa-chart-line text-warning"></i> Expected Cutoff
                            </p>

                            <span class="badge bg-primary">
                                <?php echo htmlspecialchars($college['cutoff']); ?>
                            </span>
                        </div> -->

                        <!-- <div class="mb-3">
                            <p class="fw-bold">
                                <i class="fas fa-star text-info"></i> Facilities
                            </p>

                            <p>
                                <?php echo htmlspecialchars($college['facilities']); ?>
                            </p>
                        </div> -->

                        <a href="college_details.php?id=<?php echo $college['id']; ?>" class="btn btn-primary w-100">
                            View Full Details
                        </a>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

        <!-- No Results Message -->
        <div id="noResults" class="alert alert-info d-none" style="border-radius: 12px;">
            <i class="fas fa-info-circle me-2"></i>
            No colleges found matching your search criteria. Try adjusting your filters.
        </div>

        <!-- Back Button -->
        <div class="mt-5 pt-3 back-btn">
            <a href="dashboard.php" class="btn btn-primary">
                Back to Dashboard
            </a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    const searchInput = document.getElementById('searchInput');
    const locationFilter = document.getElementById('locationFilter');
    const collegeCards = document.querySelectorAll('.college-card');
    const noResults = document.getElementById('noResults');

    function filterColleges() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedLocation = locationFilter.value.toLowerCase().trim();
        let visibleCount = 0;

        collegeCards.forEach(card => {
            const collegeName = card.dataset.name.toLowerCase().trim();
            const collegeLocation = card.dataset.location.toLowerCase().trim();

            const matchesSearch = collegeName.includes(searchTerm);
            const matchesLocation = !selectedLocation || collegeLocation.includes(selectedLocation);

            if (matchesSearch && matchesLocation) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        noResults.classList.toggle('d-none', visibleCount > 0);
    }

    searchInput.addEventListener('input', filterColleges);
    locationFilter.addEventListener('change', filterColleges);
    </script>

</body>

</html>