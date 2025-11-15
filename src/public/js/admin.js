// Xác nhận xóa
document.querySelectorAll('[onclick*="confirm"]').forEach(element => {
    element.addEventListener('click', function(e) {
        if (!confirm('Bạn có chắc chắn muốn thực hiện thao tác này?')) {
            e.preventDefault();
        }
    });
});

// Tìm kiếm người dùng
const searchUser = document.getElementById('searchUser');
if (searchUser) {
    searchUser.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

// Lọc theo vai trò
const filterRole = document.getElementById('filterRole');
if (filterRole) {
    filterRole.addEventListener('change', function() {
        const role = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            if (role === '') {
                row.style.display = '';
            } else {
                const roleCell = row.querySelector('td:nth-child(5)');
                const roleText = roleCell.textContent.toLowerCase();
                row.style.display = roleText.includes(role) ? '' : 'none';
            }
        });
    });
}

// Hiệu ứng loading
function showLoading() {
    const loader = document.createElement('div');
    loader.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center';
    loader.style.backgroundColor = 'rgba(0,0,0,0.5)';
    loader.style.zIndex = '9999';
    loader.innerHTML = '<div class="spinner-border text-light" role="status"></div>';
    document.body.appendChild(loader);
}

console.log('Admin panel loaded successfully!');
