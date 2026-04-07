/**
 * Blog Pagination System
 * Features: 6 blogs per page, Previous/Next buttons, numbered pages, category filters
 */

const BLOGS_PER_PAGE = 6;

// Blog categories mapping
const BLOG_CATEGORIES = {
  'back-pain-physiotherapy-treatment-dhaka': 'Back & Spine',
  'neck-pain-physiotherapy-treatment-dhaka': 'Neck & Shoulder',
  'text-neck-syndrome-physiotherapy-treatment-dhaka': 'Neck & Shoulder',
  'frozen-shoulder-physiotherapy-treatment-dhaka': 'Neck & Shoulder',
  'carpal-tunnel-syndrome-physiotherapy-treatment-dhaka': 'Hand & Wrist',
  'dequervains-tenosynovitis-physiotherapy-treatment-dhaka': 'Hand & Wrist',
  'trigger-finger-physiotherapy-treatment-dhaka': 'Hand & Wrist',
  'tennis-elbow-physiotherapy-treatment-dhaka': 'Elbow & Arm',
  'knee-pain-physiotherapy-treatment-dhaka': 'Knee & Leg',
  'acl-injury-physiotherapy-treatment-dhaka': 'Knee & Leg',
  'piriformis-syndrome-physiotherapy-treatment-dhaka': 'Knee & Leg',
  'sciatica-pain-physiotherapy-treatment-dhaka': 'Knee & Leg',
  'foot-pain-physiotherapy-treatment-dhaka': 'Foot & Ankle',
  'plantar-fasciitis-physiotherapy-treatment-dhaka': 'Foot & Ankle',
  'haglunds-deformity-physiotherapy-treatment-dhaka': 'Foot & Ankle',
  'groin-pain-physiotherapy-treatment-dhaka': 'Hip & Groin',
  'postop-joint-stiffness-physiotherapy-management-dhaka': 'Post-Operative',
  'winter-joint-stiffness-physiotherapy-management-dhaka': 'Post-Operative',
  'best-stroke-physiotherapy-management-dhaka': 'Neurological',
  'bells-palsy-physiotherapy-treatment-dhaka': 'Neurological',
  'assisted-physiotherapy-dhaka': 'Neurological',
  'plid-physiotherapy-treatment-dhaka': 'Back & Spine',
  'advance-electrotherapy-adporc-dhaka': 'Treatment Methods',
  'best-physiotherapy-center-dhaka': 'About ADPORC',
  'best-physiotherapy-center-jatrbari': 'About ADPORC',
};

class BlogPagination {
  constructor() {
    this.blogList = document.querySelector('.blog-list');
    this.currentPage = 1;
    this.selectedCategory = 'all';
    this.allBlogs = [];
    this.filteredBlogs = [];
    
    this.init();
  }

  init() {
    if (!this.blogList) return;
    
    // Get all blog cards
    this.allBlogs = Array.from(this.blogList.querySelectorAll('.blog-card'));
    
    // Add category data attributes to blog cards
    this.addCategoryAttributes();
    
    // Create pagination controls
    this.createPaginationControls();
    
    // Display first page
    this.filterAndDisplay('all');
  }

  addCategoryAttributes() {
    this.allBlogs.forEach(card => {
      const link = card.querySelector('a[href*="/blogs/"]');
      if (link) {
        const href = link.getAttribute('href');
        const blogSlug = href.replace('/blogs/', '').replace('.html', '');
        const category = BLOG_CATEGORIES[blogSlug] || 'General';
        
        card.setAttribute('data-category', category);
      }
    });
  }

  createPaginationControls() {
    // Remove existing pagination if any
    const existing = document.querySelector('.blog-pagination-container');
    if (existing) existing.remove();

    // Create pagination container
    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'blog-pagination-container';

    // Create category filter
    const categoryFilter = document.createElement('div');
    categoryFilter.className = 'blog-category-filter';
    
    const categories = ['all', ...new Set(this.allBlogs.map(b => b.getAttribute('data-category')))];
    
    categories.forEach(category => {
      const btn = document.createElement('button');
      btn.className = 'category-btn';
      btn.setAttribute('data-category', category);
      btn.textContent = category === 'all' ? 'All Categories' : category;
      btn.addEventListener('click', () => this.filterAndDisplay(category));
      categoryFilter.appendChild(btn);
    });

    // Create pagination controls
    const controls = document.createElement('div');
    controls.className = 'blog-pagination-controls';

    // Previous button
    const prevBtn = document.createElement('button');
    prevBtn.className = 'pagination-btn pagination-prev';
    prevBtn.innerHTML = '← Previous';
    prevBtn.addEventListener('click', () => this.previousPage());

    // Page number container
    const pageNumbers = document.createElement('div');
    pageNumbers.className = 'pagination-numbers';
    this.pageNumbersContainer = pageNumbers;

    // Next button
    const nextBtn = document.createElement('button');
    nextBtn.className = 'pagination-btn pagination-next';
    nextBtn.innerHTML = 'Next →';
    nextBtn.addEventListener('click', () => this.nextPage());

    controls.appendChild(prevBtn);
    controls.appendChild(pageNumbers);
    controls.appendChild(nextBtn);

    paginationContainer.appendChild(categoryFilter);
    paginationContainer.appendChild(controls);

    // Insert after blog list
    this.blogList.parentElement.appendChild(paginationContainer);
  }

  filterAndDisplay(category) {
    this.selectedCategory = category;
    this.currentPage = 1;

    // Filter blogs by category
    if (category === 'all') {
      this.filteredBlogs = [...this.allBlogs];
    } else {
      this.filteredBlogs = this.allBlogs.filter(b => b.getAttribute('data-category') === category);
    }

    // Update category buttons
    document.querySelectorAll('.category-btn').forEach(btn => {
      btn.classList.remove('active');
      if (btn.getAttribute('data-category') === category) {
        btn.classList.add('active');
      }
    });

    this.updateDisplay();
  }

  updateDisplay() {
    const totalPages = Math.ceil(this.filteredBlogs.length / BLOGS_PER_PAGE);

    // Ensure current page is valid
    if (this.currentPage > totalPages) {
      this.currentPage = totalPages;
    }
    if (this.currentPage < 1) {
      this.currentPage = 1;
    }

    // Hide/show blog cards based on current page
    const startIndex = (this.currentPage - 1) * BLOGS_PER_PAGE;
    const endIndex = startIndex + BLOGS_PER_PAGE;

    this.allBlogs.forEach(card => {
      card.style.display = 'none';
    });

    this.filteredBlogs.slice(startIndex, endIndex).forEach(card => {
      card.style.display = 'flex';
    });

    // Update pagination buttons
    this.updatePaginationButtons(totalPages);
  }

  updatePaginationButtons(totalPages) {
    const pageNumbersContainer = this.pageNumbersContainer;
    pageNumbersContainer.innerHTML = '';

    // Create page number buttons
    const maxVisible = 5; // Show max 5 page numbers
    let startPage = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
    let endPage = Math.min(totalPages, startPage + maxVisible - 1);

    if (endPage - startPage < maxVisible - 1) {
      startPage = Math.max(1, endPage - maxVisible + 1);
    }

    // Add "First" button if needed
    if (startPage > 1) {
      const firstBtn = document.createElement('button');
      firstBtn.className = 'page-number';
      firstBtn.textContent = '1';
      firstBtn.addEventListener('click', () => this.goToPage(1));
      pageNumbersContainer.appendChild(firstBtn);

      if (startPage > 2) {
        const dots = document.createElement('span');
        dots.className = 'page-dots';
        dots.textContent = '...';
        pageNumbersContainer.appendChild(dots);
      }
    }

    // Add visible page numbers
    for (let i = startPage; i <= endPage; i++) {
      const btn = document.createElement('button');
      btn.className = 'page-number';
      if (i === this.currentPage) {
        btn.classList.add('active');
      }
      btn.textContent = i;
      btn.addEventListener('click', () => this.goToPage(i));
      pageNumbersContainer.appendChild(btn);
    }

    // Add "Last" button if needed
    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        const dots = document.createElement('span');
        dots.className = 'page-dots';
        dots.textContent = '...';
        pageNumbersContainer.appendChild(dots);
      }

      const lastBtn = document.createElement('button');
      lastBtn.className = 'page-number';
      lastBtn.textContent = totalPages;
      lastBtn.addEventListener('click', () => this.goToPage(totalPages));
      pageNumbersContainer.appendChild(lastBtn);
    }

    // Disable/enable Previous and Next buttons
    const prevBtn = document.querySelector('.pagination-prev');
    const nextBtn = document.querySelector('.pagination-next');

    if (this.currentPage === 1) {
      prevBtn.disabled = true;
    } else {
      prevBtn.disabled = false;
    }

    if (this.currentPage === totalPages) {
      nextBtn.disabled = true;
    } else {
      nextBtn.disabled = false;
    }
  }

  goToPage(pageNum) {
    this.currentPage = pageNum;
    this.updateDisplay();
    // Scroll to blog section
    this.blogList.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  nextPage() {
    const totalPages = Math.ceil(this.filteredBlogs.length / BLOGS_PER_PAGE);
    if (this.currentPage < totalPages) {
      this.goToPage(this.currentPage + 1);
    }
  }

  previousPage() {
    if (this.currentPage > 1) {
      this.goToPage(this.currentPage - 1);
    }
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  new BlogPagination();
});
