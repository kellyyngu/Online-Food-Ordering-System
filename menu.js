// Get all filter buttons
const filterButtons = document.querySelectorAll('.filter-buttons button');

// Get all menu categories
const menuCategories = document.querySelectorAll('.menu-categories > div');

// Add click event listeners to all filter buttons
filterButtons.forEach(button => {
    button.addEventListener('click', () => {
        const categoryName = button.getAttribute('data-name');

        // Remove 'active' class from all buttons
        filterButtons.forEach(btn => {
            btn.classList.remove('active');
        });

        // Add 'active' class to the clicked button
        button.classList.add('active');

        // Hide all menu categories
        menuCategories.forEach(category => {
            category.style.display = 'none';
        });

        // Show only the menu category that matches the clicked button's category
        if (categoryName === 'all') {
            menuCategories.forEach(category => {
                category.style.display = 'block';
            });
        } else {
            const categoryToDisplay = document.querySelector(`.menu-categories > div[data-name="${categoryName}"]`);
            if (categoryToDisplay) {
                categoryToDisplay.style.display = 'block';
            }
        }
    });
});
